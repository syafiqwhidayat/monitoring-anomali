<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\IdenAnadesModel;
use App\Models\IdenKategorikModel;

class Identifikasi extends BaseController
{
    protected $anadesModel;
    protected $kategorikModel;
    protected $db;

    public function __construct()
    {
        $this->anadesModel = new IdenAnadesModel();
        $this->kategorikModel = new IdenKategorikModel();
        $this->db = \Config\Database::connect();
    }

    public function anades()
    {
        $idKegiatan = session()->get('aktif_kegiatan') ?? 1;

        // 1. Tangkap filter dari GET request
        $selectedWilayah = $this->request->getGet('selected-wilayah');
        $selectedVariabel = $this->request->getGet('selected-variabel');

        // 2. Ambal semua daftar variabel unik untuk dropdown filter
        // (Biar user bisa milih variabel apa saja yang tersedia untuk kegiatan ini)
        $listVariabel = $this->anadesModel->where('id_kegiatan', $idKegiatan)->findAll();

        // 3. Ambil data spesifik berdasarkan filter wilayah & variabel yang dipilih
        $activeAnades = null;
        $listAnomaliSampel = null;

        if ($selectedWilayah && $selectedVariabel) {
            $activeAnades = $this->anadesModel
                ->select('a.*, kategori_anomali.kode_anomali, kategori_anomali.detil_anomali')
                ->from('identifikasi_anades a') // <--- Sesuaikan jika nama tabel asli di model Anda bukan 'anades'
                ->join('kategori_anomali', 'kategori_anomali.id = a.id_kategori_anomali', 'left')
                ->where([
                    'a.id_kegiatan'  => $idKegiatan,
                    'a.kode_wilayah' => $selectedWilayah,
                    'a.id'           => $selectedVariabel
                ])
                ->first();

            // Jika data anades ditemukan dan terhubung dengan kategori anomali, ambil sampel data dari tabel transaksi
            if ($selectedWilayah && $selectedVariabel) {
                // 1. Join untuk mendapatkan data activeAnades lengkap dengan kode & detail anomali
                $activeAnades = $this->anadesModel
                    ->select('anades.*, kategori_anomali.kode_anomali, kategori_anomali.detil_anomali')
                    ->from('identifikasi_anades anades')
                    ->join('kategori_anomali', 'kategori_anomali.id = anades.id_kategori_anomali', 'left')
                    ->where([
                        'anades.id_kegiatan'  => $idKegiatan,
                        'anades.kode_wilayah' => $selectedWilayah,
                        'anades.id'           => $selectedVariabel
                    ])
                    ->first();

                // 2. Ambil data sampel anomali jika kategori anomali tertaut
                if ($activeAnades && $activeAnades['id_kategori_anomali']) {
                    // $db = \Config\Database::connect();

                    // Bangun query builder sesuai dengan struktur join riil Anda
                    $queryBuilder = $this->db->table('anomali');
                    $queryBuilder->select('
                            anomali.id AS id_anomali, anomali.id_wilayah, anomali.isi_fasih, anomali.konfirmasi, anomali.is_lap, anomali.is_insert, anomali.date_updated,
                            art.id AS id_assignment_obj, art.kd_assigment, art.nm_krt, art.nm_art, art.nm_nrt,art.kd_krt,
                            k.kode_anomali, k.detil_anomali, k.level_anomali, k.flag
                        ')
                        ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                        ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                        // Ditambahkan prefix 'anomali.' untuk menghindari ambiguitas kolom di database
                        ->where('anomali.id_kategori_anomali', $activeAnades['id_kategori_anomali']);

                    // Jika ingin menyaring data sampel berdasarkan wilayah yang dipilih, silakan aktifkan baris ini:
                    // ->where('anomali.id_wilayah', $selectedWilayah);

                    // Hitung total baris sebelum di-limit/offset
                    $totalRows = $queryBuilder->countAllResults(false);

                    // Ambil halaman aktif berdasarkan group assignment pager Anda
                    $page = $this->request->getVar('page_anomali_group') ?? 1;
                    $perPage = 10;
                    $offset = ($page - 1) * $perPage;

                    // Ambil data dengan metode segmentasi get($perPage, $offset)
                    $listAnomaliSampel = $queryBuilder->get($perPage, $offset)->getResultArray();

                    // Buat string HTML Pagination menggunakan template 'my_pager' dan group 'anomali_group'
                    $pagerService = \Config\Services::pager();
                    $pagerHtml = $pagerService->makeLinks($page, $perPage, $totalRows, 'my_pager', 0, 'anomali_group');
                }
            }
        }

        // Dropdown list anomali untuk modal config edit
        // $db = \Config\Database::connect();
        $listAnomali = $this->db->table('kategori_anomali')
            ->where('id_kegiatan', $idKegiatan)
            ->get()->getResultArray();

        $data = [
            'title'             => 'Analisis Deskriptif & Distribusi Variabel',
            'listVariabel'      => $listVariabel,
            'activeAnades'      => $activeAnades,
            'listAnomaliSampel' => $listAnomaliSampel ?? [],
            'pager'             => $pagerHtml ?? '',
            'listAnomali'       => $listAnomali,
            'selectedWilayah'   => $selectedWilayah,
            'selectedVariabel'  => $selectedVariabel
        ];

        return view('identifikasi/anades', $data);
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            // Memperbaiki validasi agar sesuai dengan field name di form HTML modal
            $rules = [
                'kode_variabel' => 'required',
                'deskripsi'     => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['status' => false, 'msg' => 'Validasi gagal!']);
            }

            $updateData = [
                'kode_variabel'       => $this->request->getPost('kode_variabel'),
                'deskripsi'           => $this->request->getPost('deskripsi'),
                'id_kategori_anomali' => $this->request->getPost('id_kategori_anomali') ?: null
            ];

            if ($this->anadesModel->update($id, $updateData)) {
                return $this->response->setJSON(['status' => true, 'msg' => 'Variabel berhasil diperbarui!']);
            }

            return $this->response->setJSON(['status' => false, 'msg' => 'Gagal mengupdate database.']);
        }
    }

    public function kategorik()
    {
        $idKegiatan = session()->get('aktif_kegiatan') ?? 1;

        // 1. Tangkap parameter filter dari GET request
        $selectedWilayah  = $this->request->getGet('selected-wilayah');
        $selectedVariabel = $this->request->getGet('selected-variabel');

        // 2. Ambil semua daftar variabel kategori berdasarkan kegiatan aktif
        $listVariabel = $this->kategorikModel->where('id_kegiatan', $idKegiatan)->findAll();

        $activeKategori    = null;
        $listAnomaliSampel = [];
        $pagerHtml         = '';

        // 3. Eksekusi data jika Filter Wilayah & Variabel Kategori Terpilih
        if ($selectedWilayah && $selectedVariabel) {

            // Query Tunggal: Mengambil detail dari tabel identifikasi_kategori
            $activeKategori = $this->kategorikModel
                ->select('identifikasi_kategori.*, kategori_anomali.kode_anomali, kategori_anomali.detil_anomali, kategori_anomali.definisi_anomali, kesimpulan_anomali.hasil_kesimpulan')
                ->join('kategori_anomali', 'kategori_anomali.id = identifikasi_kategori.id_kategori_anomali', 'left')
                ->join('kesimpulan_anomali', 'kesimpulan_anomali.id_kategori_anomali = identifikasi_kategori.id_kategori_anomali AND kesimpulan_anomali.kode_wilayah = identifikasi_kategori.kode_wilayah', 'left')
                ->where([
                    'identifikasi_kategori.id_kegiatan'  => $idKegiatan,
                    'identifikasi_kategori.kode_wilayah' => $selectedWilayah,
                    'identifikasi_kategori.id'           => $selectedVariabel
                ])
                ->first();

            // 4. Ambil Sampel Kasus Lapangan dari tabel anomali
            if ($activeKategori && $activeKategori['id_kategori_anomali']) {

                $queryBuilder = $this->db->table('anomali');
                $queryBuilder->select('
                        anomali.id AS id_anomali, anomali.id_wilayah, anomali.isi_fasih, anomali.konfirmasi, anomali.is_lap, anomali.is_insert,
                        art.id AS id_assignment_obj, art.kd_assigment, art.nm_krt, art.nm_art, art.kd_krt
                    ')
                    ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                    ->where('anomali.id_kategori_anomali', $activeKategori['id_kategori_anomali']);

                // Filter lingkup wilayah kerja
                if (!empty($selectedWilayah)) {
                    $queryBuilder->like('anomali.id_wilayah', $selectedWilayah, 'after');
                }

                // Kalkulasi data untuk Pager
                $totalRows = $queryBuilder->countAllResults(false);

                // Pagination (Maksimal 25 baris per halaman)
                $page    = $this->request->getVar('page_anomali_group') ?? 1;
                $perPage = 25;
                $offset  = ($page - 1) * $perPage;

                $listAnomaliSampel = $queryBuilder->get($perPage, $offset)->getResultArray();

                // Render HTML Links Pager
                $pagerService = \Config\Services::pager();
                $pagerHtml    = $pagerService->makeLinks($page, $perPage, $totalRows, 'my_pager', 0, 'anomali_group');
            }
        }

        // 5. Mengambil list master kategori anomali untuk drop-down modal
        $listAnomali = $this->db->table('kategori_anomali')
            ->where('id_kegiatan', $idKegiatan)
            ->get()->getResultArray();
        // dd($listAnomali);

        $data = [
            'title'             => 'Analisis Statistik Kategorikal',
            'listVariabel'      => $listVariabel,
            'activeKategori'    => $activeKategori,
            'listAnomaliSampel' => $listAnomaliSampel,
            'pager'             => $pagerHtml,
            'listAnomali'       => $listAnomali,
            'selectedWilayah'   => $selectedWilayah,
            'selectedVariabel'  => $selectedVariabel
        ];

        return view('identifikasi/kategorik', $data);
    }

    public function update_kategorik()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $id_kategori_anomali = $this->request->getPost('id_kategori_anomali');

            $updateData = [
                'deskripsi'           => $this->request->getPost('deskripsi'),
                'id_kategori_anomali' => !empty($id_kategori_anomali) ? $id_kategori_anomali : null
            ];

            // Melakukan update spesifik ke tabel identifikasi_kategori
            $this->db->table('identifikasi_kategori')
                ->where('id', $id)
                ->update($updateData);

            return $this->response->setJSON([
                'status' => true,
                'msg'    => 'Aturan analisis kategorikal berhasil diperbarui!'
            ]);
        }
        return $this->response->setStatusCode(404);
    }
}
