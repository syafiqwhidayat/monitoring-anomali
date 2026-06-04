<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class FasihHelpdesk extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        // Memastikan folder upload otomatis terbentuk dengan aman jika belum ada
        if (!is_dir(FCPATH . 'uploads/helpdesk/diskusi')) {
            mkdir(FCPATH . 'uploads/helpdesk/diskusi', 0777, true);
        }
        if (!is_dir(FCPATH . 'uploads/helpdesk/knowledge')) {
            mkdir(FCPATH . 'uploads/helpdesk/knowledge', 0777, true);
        }
    }

    // ====================================================================
    // JALUR BACK-END INTERNAL (MENGGUNAKAN LAYOUT DASHBOARD NORMAL)
    // ====================================================================

    // A. Manajemen & Daftar FAQ Master Internal
    public function index()
    {
        $cari = $this->request->getGet('cari') ?? '';
        $builder = $this->db->table('knowledge_base kb')
            ->select('kb.*, u.username as pembuat')
            ->join('users u', 'u.id = kb.created_by', 'left');

        if (!empty($cari)) {
            $builder->groupStart()
                ->like('kb.judul', $cari)
                ->orLike('kb.masalah', $cari)
                ->orLike('kb.solusi', $cari)
                ->groupEnd();
        }

        $data['knowledges'] = $builder->orderBy('kb.date_updated', 'DESC')->get()->getResultArray();
        $data['filterCari'] = $cari;
        $data['title'] = "Kelola Knowledge Base FASIH";

        return view('helpdesk/v_knowledge_list', $data);
    }

    // B. Simpan FAQ Baru Secara Manual oleh Admin
    public function storeKnowledge()
    {
        $this->db->table('knowledge_base')->insert([
            'judul'      => $this->request->getPost('judul'),
            'kategori'   => $this->request->getPost('kategori'),
            'masalah'    => $this->request->getPost('masalah'),
            'solusi'     => $this->request->getPost('solusi'),
            'created_by' => session()->get('id_user') ?? 1 // Sesuaikan sistem login session Anda
        ]);

        return redirect()->to('/fasihhelpdesk')->with('success', 'FAQ Master baru berhasil diterbitkan.');
    }

    // C. Edit Data FAQ Master
    public function updateKnowledge($id = null)
    {
        if (session()->get('role') === 'mitra') return redirect()->back();

        // Jika $id di URL kosong, ambil alternatif dari input post hidden
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        // Ambil data lama untuk cek foto
        $dataLama = $this->db->table('knowledge_base')->where('id', $id)->get()->getRowArray();
        if (!$dataLama) {
            return redirect()->back()->with('error', 'Data FAQ tidak ditemukan.');
        }

        $fileFoto = $this->request->getFile('foto_knowledge');
        $namaFotoUpdate = $dataLama['foto'];

        // Jika admin upload foto baru
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Hapus file fisik foto lama di server
            if (!empty($dataLama['foto'])) {
                $pathFotoLama = ROOTPATH . 'public/uploads/helpdesk/knowledge/' . $dataLama['foto'];
                if (file_exists($pathFotoLama)) {
                    unlink($pathFotoLama);
                }
            }

            // Rename acak gambar baru
            $namaFotoUpdate = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/uploads/helpdesk/knowledge', $namaFotoUpdate);
        }

        // Update DB
        $this->db->table('knowledge_base')->where('id', $id)->update([
            'judul'        => $this->request->getPost('judul'),
            'kategori'     => $this->request->getPost('kategori'),
            'masalah'      => $this->request->getPost('masalah'),
            'solusi'       => $this->request->getPost('solusi'),
            'foto'         => $namaFotoUpdate,
            'date_updated' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Referensi FAQ Master berhasil diperbarui!');
    }

    // D. Mengakses Semua Daftar Tiket Kendala Masuk
    public function listLaporan()
    {
        $builder = $this->db->table('laporan_kendala l')
            ->select('l.*, u.username as nama_pelapor')
            ->join('users u', 'u.id = l.id_user', 'left');

        // Jika akun login bertipe mitra, batasi agar hanya bisa melihat laporannya sendiri
        if (session()->get('role') === 'mitra') {
            $builder->where('l.id_user', session()->get('id_user'));
        }

        $data['laporan'] = $builder->orderBy('l.status', 'ASC')->orderBy('l.date_updated', 'DESC')->get()->getResultArray();
        $data['title'] = "Daftar Kendala Lapangan FASIH";
        return view('helpdesk/v_laporan_list', $data);
    }

    // E. Kirim Laporan Baru oleh PPL (Disimpan ke Folder Diskusi)
    public function storeLaporan()
    {
        $fileFoto = $this->request->getFile('foto_kendala');
        $namaFoto = null;

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/uploads/helpdesk/diskusi', $namaFoto);
        }

        $this->db->table('laporan_kendala')->insert([
            'id_user'       => session()->get('id_user') ?? 2,
            'judul_kendala' => $this->request->getPost('judul_kendala'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'id_wilayah'    => session()->get('id_wilayah') ?? '1311',
            'foto'          => $namaFoto,
            'status'        => 'open'
        ]);

        return redirect()->to('/fasihhelpdesk/listLaporan')->with('success', 'Aduan kendala berhasil dikirim ke Admin.');
    }

    // F. Ruang Chat Obrolan (PPL & Admin)
    public function detailLaporan($idLaporan)
    {
        $data['laporan'] = $this->db->table('laporan_kendala l')
            ->select('l.*, u.username as pelapor')
            ->join('users u', 'u.id = l.id_user', 'left')
            ->where('l.id', $idLaporan)->get()->getRowArray();

        $data['diskusi'] = $this->db->table('laporan_diskusi d')
            ->select('d.*, u.username')
            ->join('users u', 'u.id = d.id_user', 'left')
            ->where('d.id_laporan', $idLaporan)->orderBy('d.date_created', 'ASC')->get()->getResultArray();

        $data['masterKnowledge'] = $this->db->table('knowledge_base')->get()->getResultArray();
        $data['title'] = "Diskusi Masalah #" . $idLaporan;

        return view('helpdesk/v_laporan_detail', $data);
    }

    // G. Kirim Balasan Chat Obrolan (Otomatis Menyalin Konten Teks Jika Tautkan FAQ)
    public function balasDiskusi($idLaporan)
    {
        $pesan = $this->request->getPost('pesan');
        $idKnowledge = $this->request->getPost('id_knowledge_terkait');
        $fileFoto = $this->request->getFile('foto_balasan');

        // Jika Admin membalas sembari memilih template FAQ rujukan, salin isinya ke dalam chat
        if (!empty($idKnowledge)) {
            $kb = $this->db->table('knowledge_base')->where('id', $idKnowledge)->get()->getRowArray();
            if ($kb) {
                $catatanTambahan = !empty($pesan) ? "Pesan Tambahan Admin:\n" . $pesan . "\n\n" : "";
                $pesan = $catatanTambahan . "💡 **SOLUSI REFERENSI UTAMA [FAQ]:**\n_" . $kb['judul'] . "_\n\n*Masalah:* " . $kb['masalah'] . "\n\n*Solusi:* " . $kb['solusi'];
            }
        }

        $namaFoto = null;
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(ROOTPATH . 'public/uploads/helpdesk/diskusi', $namaFoto);
        }

        $this->db->transStart();
        $this->db->table('laporan_diskusi')->insert([
            'id_laporan' => $idLaporan,
            'id_user'    => session()->get('id_user') ?? 1,
            'pesan'      => $pesan,
            'foto'       => $namaFoto
        ]);

        if (!empty($idKnowledge)) {
            $this->db->table('laporan_kendala')->where('id', $idLaporan)->update(['id_knowledge_terkait' => $idKnowledge]);
        }
        $this->db->transComplete();

        return redirect()->to('/fasihhelpdesk/detailLaporan/' . $idLaporan)->with('success', 'Balasan dikirim.');
    }

    // H. FITUR KUNCI (FLAG SELESAI): Kloning Data Diskusi Menjadi FAQ Permanen
    public function closeDanJadikanKnowledge($idLaporan)
    {
        $laporan = $this->db->table('laporan_kendala')->where('id', $idLaporan)->get()->getRowArray();
        $diskusiTerakhir = $this->db->table('laporan_diskusi')->where('id_laporan', $idLaporan)->orderBy('id', 'DESC')->get()->getRowArray();

        $solusiTeks = $diskusiTerakhir ? $diskusiTerakhir['pesan'] : 'Telah dikonfirmasi selesai oleh Admin Kabupaten.';
        $fotoSumber = ($diskusiTerakhir && !empty($diskusiTerakhir['foto'])) ? $diskusiTerakhir['foto'] : $laporan['foto'];
        $fotoKnowledgeBaru = null;

        // Proses Duplikasi Berkas ke Ruang Arsip Permanen (Knowledge Folder)
        if ($fotoSumber) {
            $pathSumber = ROOTPATH . 'public/uploads/helpdesk/diskusi/' . $fotoSumber;
            if (file_exists($pathSumber)) {
                $fotoKnowledgeBaru = 'KB_DUPLIKAT_' . time() . '_' . $fotoSumber;
                copy($pathSumber, ROOTPATH . 'public/uploads/helpdesk/knowledge/' . $fotoKnowledgeBaru);
            }
        }

        $this->db->transStart();
        // Daftarkan arsip terstruktur baru
        $this->db->table('knowledge_base')->insert([
            'judul'      => '[Kasus Lapangan] ' . $laporan['judul_kendala'],
            'kategori'   => 'Kasus Lapangan',
            'masalah'    => $laporan['deskripsi'],
            'solusi'     => $solusiTeks,
            'foto'       => $fotoKnowledgeBaru,
            'created_by' => session()->get('id_user') ?? 1
        ]);

        $idKBBaru = $this->db->insertID();

        // Kunci status laporan tiket jadi closed
        $this->db->table('laporan_kendala')->where('id', $idLaporan)->update([
            'status' => 'closed',
            'id_knowledge_terkait' => $idKBBaru
        ]);
        $this->db->transComplete();

        return redirect()->to('/fasihhelpdesk/detailLaporan/' . $idLaporan)->with('success', 'Laporan ditutup dan diduplikasi aman ke Master FAQ.');
    }


    // ====================================================================
    // JALUR LUAR LOGIN (VIEW PUBLIK MINIMALIS CLEAN - COCOK UNTUK SHARE WA)
    // ====================================================================

    // A. Landing Page Utama FAQ Publik (Bisa Search Tanpa Login)
    public function publikIndex()
    {
        $cari = $this->request->getGet('cari') ?? '';
        $builder = $this->db->table('knowledge_base kb');

        if (!empty($cari)) {
            $builder->groupStart()
                ->like('kb.judul', $cari)
                ->orLike('kb.masalah', $cari)
                ->orLike('kb.solusi', $cari)
                ->groupEnd();
        }

        $data['knowledges'] = $builder->orderBy('kb.date_updated', 'DESC')->get()->getResultArray();
        $data['filterCari'] = $cari;
        $data['title'] = "Pusat Bantuan FAQ FASIH Mobile";

        return view('helpdesk/v_knowledge_publik', $data);
    }

    // B. Direct Link Tunggal Pembacaan FAQ Publik Tanpa Login (Bahan Share Grup WhatsApp)
    public function publikDetail($id)
    {
        $data['kb'] = $this->db->table('knowledge_base')->where('id', $id)->get()->getRowArray();
        if (!$data['kb']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Link FAQ Kedaluwarsa atau Salah.");
        }
        $data['title'] = $data['kb']['judul'];
        return view('helpdesk/v_knowledge_publik_detail', $data);
    }

    // OPSI 1: Hanya menutup laporan secara reguler (Kasus biasa/tidak direkomendasikan jadi FAQ)
    public function closeLaporanBiasa($idLaporan)
    {
        if (session()->get('role') === 'mitra') return redirect()->back()->with('error', 'Akses ditolak!');

        // Cukup update status menjadi 'closed' saja tanpa utak-atik tabel knowledge_base
        $this->db->table('laporan_kendala')->where('id', $idLaporan)->update([
            'status' => 'closed'
        ]);

        return redirect()->to('/fasihhelpdesk/detailLaporan/' . $idLaporan)->with('success', 'Tiket kendala berhasil diselesaikan (Arsip Reguler).');
    }
}
