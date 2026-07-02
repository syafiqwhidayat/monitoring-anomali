<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\LogUploadModel;
// use App\Models\AnomaliKegiatanWilayahTugasModel;
use Config\Services;
use Faker\Provider\Lorem;

use function PHPUnit\Framework\returnArgument;

class Anom extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;
    protected $akwtModel;
    protected $validation;
    protected $logModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
        $this->logModel = new LogUploadModel();
        // $this->akwtModel = new AnomaliKegiatanWilayahTugasModel();
        $this->validation = \Config\Services::validation();
    }

    public function list($isEdit = '0')
    {
        // cek is edit jika ada inisial value
        $userWilayah = auth()->user()->wilayah_kerja;
        $data['isEdit'] = $isEdit == '1' ?: ($this->request->getGet('isEdit') == '1' ?? '0');
        $data['filterWilayah'] = $this->request->getGet('fil-wilayah') ?? ($userWilayah !== '1300' ? $userWilayah : '');
        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        $data['filterKategori'] = $this->request->getGet('fil-kategori') ?? '';
        $data['filterFlag'] = $this->request->getGet('fil-flag') ?? '';
        $data['message'] = null;
        $isRT = (session()->get('is_rt') == 1);

        $data['title'] = 'List Anomali';
        $data['listLevel'] = [
            [
                'id' => '',
                'nama' => "Semua Anomali",
            ],
        ];
        $data['listWilayah'] = [
            [
                'value' => '1300',
                'nama' => "Sumatera Barat",
            ],
        ];
        $data['listSelFlag'] = [];
        $data['listSelKdAnom'] = [
            [
                'id' => '',
                'nama' => 'Semua Anomali',
            ],
        ];

        $listSelKdAnom = $this->anomaliModel->getKdAnomaliByUser() ?? [];
        $listSelFlag = $this->anomaliModel->getFlagByUser() ?? [];
        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];
        $listSelWilayah = $this->anomaliModel->getWilayahAnomByUser() ?? [];

        $data['listSelKdAnom'] = array_merge($data['listSelKdAnom'], $listSelKdAnom ?? []);
        $data['listSelFlag'] = array_merge($data['listSelFlag'], $listSelFlag ?? []);
        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel ?? []);
        $data['listWilayah'] = $listSelWilayah;

        // memunculkan isian
        if (true) {
            try {
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah(
                    $data['filterWilayah'],
                    $data['isEdit'],
                    $data['filterKategori'],
                    $data['filterFlag'],
                    $data['filterLevel'],
                    $isRT
                );
            } catch (\Throwable $th) {
                $data['listAnom'] = [];
                $data['message'] = "Gagal memuat data: " . $th->getMessage();
            };
        } else {
            $data['listAnom'] = $this->akwtModel->getAnomaliByWilayah(
                $data['filterWilayah'],
                $data['isEdit'],
                $data['filterKategori'],
                $data['filterFlag'],
                $data['filterLevel'],
                $isRT
            );
        }


        return view('anomali/listAnomali', $data);
    }

    public function listDetil()
    {
        // fungsi yang di panggil dari js accordion
        $data['title'] = 'List Anomali';
        $data['listAnom'] = null;
        $data['id'] = null;
        $data['jenis'] = null;

        // apakah base rumahtangga
        $isRT = ((int)session()->get('is_rt') === 1);
        $level_wilayah = (int)session()->get('level_wilayah');

        // Mendapatkan nilai filter dari request GET
        $filterLevel    = $this->request->getGet('fil-level') ?? '';
        $id             = $this->request->getGet('id-anomali') ?? '';
        $filterKategori = $this->request->getGet('fil-kategori') ?? '';
        $filterFlag     = $this->request->getGet('fil-flag') ?? '';
        $isEdit         = $this->request->getGet('is-edit') ?? '';

        // Deteksi keberadaan separator '_' untuk mengenali entitas jeroan ruta/art
        $parts = explode('_', $id);
        $countParts = count($parts);

        if ($isRT) {
            // --- JALUR BASE RUMAH TANGGA / SOSIAL ---
            if ($countParts === 1) {
                // Jika belum ada underscore, berarti masih proses drill-down wilayah murni
                if (strlen($id) === $level_wilayah) {
                    $data['jenis'] = 'Ruta'; // Menampilkan daftar perusahaan/entitas usaha
                } else {
                    switch (strlen($id)) {
                        case 4:
                            $data['jenis'] = 'Kec';
                            break;
                        case 7:
                            $data['jenis'] = 'Des';
                            break;
                        case 10:
                            $data['jenis'] = 'SLS';
                            break;
                        case 16:
                            $data['jenis'] = 'Ruta';
                            break; // Batas wilayah maksimal (SLS)
                        default:
                            $data['jenis'] = 'Kec';
                            break;
                    }
                }
            } else {
                // Jika sudah ada underscore (Proses masuk ke jeroan Ruta/ART)
                if ($countParts === 2) {
                    // Format: [kdwilayah]_[kdAssigment] -> Menampilkan daftar Anggota Rumah Tangga (Roster)
                    $data['jenis'] = 'Art';
                } elseif ($countParts === 3) {
                    // Format: [kdwilayah]_[kdAssigment]_[kdRoster] -> MENAMPILKAN DETAIL DAFTAR ANOMALI INDIVIDU
                    $data['jenis'] = 'Anom';
                    return $this->listAnom($id, $isEdit, $filterKategori, $filterFlag, $filterLevel);
                }
            }
        } else {
            // dd($countParts);
            // --- JALUR NON Roster RUMAH TANGGA / EKONOMI ---
            if ($countParts === 1) {
                $len = strlen($id);
                if ($len === $level_wilayah) {
                    $data['jenis'] = 'Ruta'; // Menampilkan daftar perusahaan/entitas usaha
                } else {
                    switch ($len) {
                        case 4:
                            $data['jenis'] = 'Kec';
                            break;
                        case 7:
                            $data['jenis'] = 'Des';
                            break;
                        case 10:
                            $data['jenis'] = 'SLS';
                            break;
                        default:
                            $data['jenis'] = 'Kec';
                            break;
                    }
                }
            } else {
                // Jika non-RT memiliki underscore (Format: [kdwilayah]_[kdAssigment])
                // Berarti sudah klik pada entitas usaha tersebut, langsung tampilkan rincian kesalahan/anomali
                $data['jenis'] = 'Anom';
                return $this->listAnom($id, $isEdit, $filterKategori, $filterFlag, $filterLevel);
            }
        }

        try {
            // mengambail anomali menurut wilayah
            $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah(
                $id,
                $isEdit,
                $filterKategori,
                $filterFlag,
                $filterLevel,
                $isRT
            );
        } catch (\Throwable $e) {
            $data['listAnom'] = null;
        }

        // dd($data['listAnom']);
        return view('anomali/listAnomaliPart', $data);
    }

    public function listAnom($idArt, $isEdit, $filterKategori, $filterFlag, $filterLevel)
    {

        $data['listAnom'] = $this->anomaliModel->getListAnomali($idArt, $isEdit, $filterKategori, $filterFlag, $filterLevel);
        // dd($data['list_anom']);


        return view('anomali/listAnomaliDetil', $data);
    }

    public function upload()
    {
        $data = [
            "title" => "Upload Anomali"
        ];
        return view('anomali/upload', $data);
    }

    public function updateKonfirmasi()
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        if ($this->request->getPost('bulk')) {
            return $this->updateKonfirmasiBulk();
        }

        $datum['id'] = $this->request->getPost('id');
        $datum['konfirmasi'] = $this->request->getVar('konfirmasi');
        $datum['is_lap'] = $this->request->getVar('kondisi_lapangan') ?? 0;
        $rules = [
            'id'           => 'required|is_natural_no_zero',
            'konfirmasi' => [
                'required',
                'trim',
                'regex_match[/^[A-Za-z0-9~!#\$%&\*\-_+=\|:\., ]+$/]', // Menggunakan pipa di sini sepenuhnya aman
                'min_length[5]'
            ]
        ];
        $this->validation->reset();
        $this->validation->setRules($rules);
        if (!$this->validation->run($datum)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $this->validation->getError('konfirmasi') // Kirim pesan error spesifik
            ]);
        }
        $id = $datum['id'];
        unset($datum['id']);
        $this->anomaliModel->update($id, $datum);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Konfirmasi berhasil disimpan.',
            'id_updated' => $id
        ]);
    }

    public function updateKonfirmasiBulk()
    {
        // filter terpilih
        $data['filterAnomali'] = $this->request->getVar('id-kat') ?? null;
        $data['sel_prov'] = $this->request->getVar('kd-prov') ?? 13;
        $data['sel_kab'] = ($data['sel_prov']) ? $this->request->getVar('kd-kab') ?? null : null;
        $data['sel_kec'] = ($data['sel_kab']) ? $this->request->getVar('kd-kec') ?? null : null;
        $data['sel_des'] = ($data['sel_kec']) ? $this->request->getVar('kd-des') ?? null : null;
        $idWilayah = $data['sel_prov'] . $data['sel_kab'] . $data['sel_kec'] . $data['sel_des'];
        $len = strlen($idWilayah);

        $data['konfirmasi'] = $this->request->getVar('konfirmasi');
        $data['is_lap'] = $this->request->getVar('kondisi_lapangan') ?? 0;
        $data['id_kode_anomali'] = $this->request->getVar('id-kat');
        $data['konfirmasi'] = strip_tags($data['konfirmasi']);
        $rules = [
            'konfirmasi' => 'required|trim|alpha_numeric_punct|min_length[5]'
        ];
        $this->validation->reset();
        $this->validation->setRules($rules);
        if (!$this->validation->run($data)) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $jumlah = 0;

        $hasil = $this->anomaliModel
            ->where("left(id_wilayah,$len)", $idWilayah)
            ->where('konfirmasi', '')
            ->where('id_kategori_anomali', $data['id_kode_anomali']);
        $hasil
            ->set(['konfirmasi' => $data['konfirmasi'], 'is_lap' => $data['is_lap']])
            ->update();
        if ($hasil) {
            $jumlah = $this->anomaliModel->db->affectedRows();
        } else {
            dd("gagal");
        }

        return redirect()->to(base_url('/anomali/konfirmasiBulk'))->with('message', 'Konfirmasi berhasil disimpan. Berhasil mempengaruhi ' . $jumlah . ' anomali');

        // dd($konfirmasi);
    }

    public function tanyaAnom()
    {
        $data = [
            'title' => 'Tanya Anomali',
        ];

        return view('anomali/tanya', $data);
    }


    public function konfirmasiBulk()
    {
        $userWialyah = auth()->user()->wilayah_kerja;
        $userWialyah = substr($userWialyah, -2);

        $data = [
            'title' => 'Konfirmasi Bulk Anomali',
        ];

        // filter terpilih
        $data['filterAnomali'] = $this->request->getGet('fil-anomali') ?? null;
        $oldFilter = $this->request->getGet('fil-anomali-old') ?? null;

        // filter wilayah
        $data['sel_prov'] = $this->request->getGet('sel-prov') ?? 13;
        $data['sel_kab'] = ($userWialyah === '00') ? $data['sel_prov'] ? $this->request->getGet('sel-kab') ?? null : null : $userWialyah;
        // reset filter wilayah ketika ganti filter
        if ($oldFilter !== $data['filterAnomali']) {
            $data['sel_kab'] = ($userWialyah === '00') ? $data['sel_prov'] ? $this->request->getGet('sel-kab') ?? null : null : $userWialyah;
        }
        $data['sel_kec'] = $data['sel_kab'] ? $this->request->getGet('sel-kec') ?? null : null;
        $data['sel_des'] = $data['sel_kec'] ? $this->request->getGet('sel-des') ?? null : null;



        $data['listKodeAnom'] = [[
            'id' => '',
            'nama' => "Pilih Kode Anomali",
            'level' => ''
        ]];
        $listAnom = $this->anomaliModel->getKdAnomaliByUser() ?? [];

        $data['listKodeAnom'] = array_merge($data['listKodeAnom'], $listAnom);

        // jika bukan provinsi
        if ($userWialyah !== '00') {
            $data['list_kab'] = $this->anomaliModel->getWilayah('kab', $data['filterAnomali'], $data['sel_prov'], $data['sel_kab']);
        } else {
            $data['list_kab'] = $this->anomaliModel->getWilayah('kab', $data['filterAnomali'], $data['sel_prov']);
        }
        $data['list_kec'] = $this->anomaliModel->getWilayah('kec', $data['filterAnomali'], $data['sel_prov'], $data['sel_kab']);
        $data['list_des'] = $this->anomaliModel->getWilayah('des', $data['filterAnomali'], $data['sel_prov'], $data['sel_kab'], $data['sel_kec']);

        // jika tidak ada anomali yg terpilih, maka mengembalikan data null
        if (!empty($data['filterAnomali'])) {
            $data['data'] = $this->katAnomaliModel->find($data['filterAnomali']);

            $idWilayah = $data['sel_prov'] . $data['sel_kab'] . $data['sel_kec'] . $data['sel_des'];
            $len = strlen($idWilayah);
            $hasil = $this->anomaliModel->builder()
                ->where("left(id_wilayah,$len)", $idWilayah)
                ->where('konfirmasi', '')
                ->where('id_kategori_anomali', $data['filterAnomali'])
                ->countAllResults();
            // dd($this->anomaliModel->getLastQuery());

            $data['jumlah_anomali'] = $hasil;
        } else {
            $data['data'] = null;
            $data['jumlah_anomali'] = 0;
        }

        return view('anomali/konfirmasiBulk', $data);
    }

    public function hasAksesView($wilayahAnomali = null): bool
    {
        $currentUser = auth()->user();
        if ($currentUser->inGroup('superadmin')) {
            // jika super admin punya akses untuk seluruh wilayah dan seluruh level anomali.
            return true;
        } else {
            if ($currentUser->wilayah_kerja === $wilayahAnomali) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function hasAksesEdit($levelAnomali, $wilayahAnomali)
    {
        $currentUser = auth()->user();
        if ($currentUser->inGroup('superadmin')) {
            return true;
        } elseif ($currentUser->inGroup('admin')) {
            if ($currentUser->wilayah_kerja == $wilayahAnomali) {
                return true;
            } else {
                return false;
            }
        } elseif ($currentUser->inGroup('operator', 'mitra')) {
            return false;
        }
    }

    public function konfirFasih()
    {
        $userWilayah = auth()->user()->wilayah_kerja;

        // Aturan Hak Akses Wilayah (Jika 1300 bebas pilih, jika kab/kota kunci ke wilayahnya)
        if ($userWilayah !== '1300') {
            $data['filterWilayah'] = $userWilayah;
            $data['isKunciWilayah'] = true;
        } else {
            $data['filterWilayah'] = $this->request->getGet('fil-wilayah') ?? '';
            $data['isKunciWilayah'] = false;
        }

        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        $data['message'] = null;
        $data['title'] = 'Konfirmasi Anomali Fasih';

        // Setup Dropdown Filter sesuai standar fungsi list() Anda
        $data['listLevel'] = [
            ['id' => '', 'nama' => "Semua Level Anomali"]
        ];
        $data['listWilayah'] = [];

        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];
        $listSelWilayah = $this->anomaliModel->getWilayahAnomByUser() ?? [];

        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel);
        $data['listWilayah'] = $listSelWilayah;

        // Cek apakah request untuk download Excel
        $isExport = $this->request->getGet('export') === 'excel';

        try {
            // Menggunakan cara aman: Panggil builder langsung dari instance model
            $builder = $this->anomaliModel->builder();

            $builder->select('anomali.id AS id_anomali, anomali.id_wilayah, anomali.konfirmasi, anomali.date_updated, 
                                  art.kd_assigment, art.nm_krt, art.nm_art, art.nm_nrt,
                                  k.kode_anomali, k.detil_anomali, k.level_anomali')
                ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                ->join('wilayah_tugas wt', 'wt.id_wilayah = anomali.id_wilayah AND wt.id_kegiatan = k.id_kegiatan', 'left');

            // LOGIKA UTAMA EVALUASI:
            // Konfirmasi terisi, is_lap = 0, tetapi masih muncul di update terbaru (is_insert = 1)
            $builder->where('anomali.konfirmasi IS NOT NULL')
                ->where('anomali.konfirmasi !=', '')
                ->where('anomali.is_lap', 0)
                ->where('anomali.is_insert', 1);

            // Filter Level Anomali jika dipilih
            if (!empty($data['filterLevel'])) {
                $builder->where('k.level_anomali', $data['filterLevel']);
            }

            // Filter Wilayah Kerja tingkat Kab/Kota (dari filter harian atau lock akun organik)
            if (!empty($data['filterWilayah'])) {
                $builder->like('anomali.id_wilayah', $data['filterWilayah'], 'after');
            }

            // Menyesuaikan dengan standar pengecekan role/group di aplikasi Anda
            $currentUser = auth()->user();
            if ($currentUser->inGroup('mitra') || session('aktif_role') === 'mitra') {
                $idUser = $currentUser->id;

                $builder->groupStart()
                    ->where('wt.id_ppl', $idUser)
                    ->orWhere('wt.id_pml', $idUser)
                    ->groupEnd();
            }

            $builder->orderBy('anomali.id_wilayah', 'ASC')
                ->orderBy('k.kode_anomali', 'ASC');

            if ($isExport) {
                // Ambil semua data tanpa limit halaman untuk dilempar ke Excel
                $data['listAnom'] = $builder->get()->getResultArray();
                return view('anomali/excelKonfirFasih', $data);
            } else {
                // Cara native CI4 mendistribusikan custom query ke pagination bawaan model
                $data['listAnom'] = $this->anomaliModel->paginate(15, 'group_catatan');
                $data['pager'] = $this->anomaliModel->pager;
            }
        } catch (\Throwable $th) {
            $data['listAnom'] = [];
            $data['message'] = "Gagal memuat data catatan evaluasi: " . $th->getMessage();
        }

        return view('anomali/konfirFasih', $data);
    }

    public function rekapAnomali()
    {
        $userWilayah = auth()->user()->wilayah_kerja;

        // Filter Wilayah Kerja (1300 bisa pilih, Kab/Kota dikunci)
        if ($userWilayah !== '1300') {
            $data['filterWilayah'] = $userWilayah;
            $data['isKunciWilayah'] = true;
        } else {
            $data['filterWilayah'] = $this->request->getGet('fil-wilayah') ?? '';
            $data['isKunciWilayah'] = false;
        }

        // Ambil filter lainnya sesuai standar list() Anda
        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        $data['filterKategori'] = $this->request->getGet('fil-kategori') ?? '';
        $data['filterFlag'] = $this->request->getGet('fil-flag') ?? '';
        $data['filterKonfirmasi'] = $this->request->getGet('fil-konfirmasi') ?? ''; // Filter Baru
        $data['filterStatus'] = $this->request->getGet('fil-status') ?? ''; // Filter Baru

        $data['message'] = null;
        $data['title'] = 'Rekap Jawaban Anomali per Assignment';

        // Persiapan data dropdown filter dari model
        $data['listLevel'] = [['id' => '', 'nama' => "Semua Level"]];
        $data['listWilayah'] = [];
        $data['listSelKdAnom'] = [['id' => '', 'nama' => 'Semua Kode Anomali']];
        $data['listSelFlag'] = [];

        $data['listLevel'] = array_merge($data['listLevel'], $this->anomaliModel->getLevelAnomByUser() ?? []);
        $data['listWilayah'] = $this->anomaliModel->getWilayahAnomByUser() ?? [];
        $data['listSelKdAnom'] = array_merge($data['listSelKdAnom'], $this->anomaliModel->getKdAnomaliByUser() ?? []);
        $data['listSelFlag'] = array_merge($data['listSelFlag'], $this->anomaliModel->getFlagByUser() ?? []);

        $isExport = $this->request->getGet('export') === 'excel';
        $isTemplate = $this->request->getGet('export') === 'template';

        try {
            $builder = $this->anomaliModel->builder();

            // SELECT dengan tambahan nama PPL dan PML dari tabel master user/petugas Anda
            $builder->select('anomali.id AS id_anomali, anomali.id_wilayah, anomali.konfirmasi, anomali.is_lap, anomali.is_insert, anomali.date_updated,
                              art.id AS id_assignment_obj, art.kd_assigment, art.nm_krt, art.nm_art, art.nm_nrt,
                              k.kode_anomali, k.detil_anomali, k.level_anomali, k.flag,
                              u_ppl.username as nama_ppl, u_pml.username as nama_pml') // Sesuaikan field nama di tabel user Anda (misal: 'nama' atau 'username')
                ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                ->join('wilayah_tugas wt', 'wt.id_wilayah = anomali.id_wilayah AND wt.id_kegiatan = k.id_kegiatan', 'left')
                // Join mencari nama PPL dan PML
                ->join('users u_ppl', 'u_ppl.id = wt.id_ppl', 'left')
                ->join('users u_pml', 'u_pml.id = wt.id_pml', 'left');

            // === FILTER WAJIB: HANYA MENGAMBIL KEGIATAN YANG SEDANG AKTIF ===
            $builder->where('k.id_kegiatan', session()->get('aktif_kegiatan'));
            // ===============================================================

            // --- Logika Filter Baru: Isi Konfirmasi ---
            if ($data['filterKonfirmasi'] === 'terisi') {
                $builder->where('anomali.konfirmasi IS NOT NULL')
                    ->where('anomali.konfirmasi !=', '');
            } elseif ($data['filterKonfirmasi'] === 'kosong') {
                $builder->groupStart()
                    ->where('anomali.konfirmasi', null)
                    ->orWhere('anomali.konfirmasi', '')
                    ->groupEnd();
            }

            // --- Logika Filter Baru: Status Anomali (is_insert) ---
            if ($data['filterStatus'] === 'aktif') {
                $builder->where('anomali.is_insert', 1);
            } elseif ($data['filterStatus'] === 'clean') {
                $builder->where('anomali.is_insert', 0);
            }

            // Kondisi Filter Dinamis
            if (!empty($data['filterLevel'])) {
                $builder->where('k.level_anomali', $data['filterLevel']);
            }
            if (!empty($data['filterKategori'])) {
                $builder->where('k.id', $data['filterKategori']);
            }
            if (!empty($data['filterFlag'])) {
                $builder->where('k.flag', $data['filterFlag']);
            }
            if (!empty($data['filterWilayah'])) {
                $builder->like('anomali.id_wilayah', $data['filterWilayah'], 'after');
            }

            // Proteksi Jika Akun adalah Mitra Lapangan
            if (session()->get('aktif_role') === 'mitra') {
                $idUser = auth()->user()->id;
                $builder->groupStart()
                    ->where('wt.id_ppl', $idUser)
                    ->orWhere('wt.id_pml', $idUser)
                    ->groupEnd();
            }

            // Diurutkan berdasarkan objek tugas (assignment) agar rowspan bekerja sempurna di view
            $builder->orderBy('art.id', 'ASC')
                ->orderBy('k.kode_anomali', 'ASC');

            if ($isExport) {
                $data['listAnom'] = $builder->get()->getResultArray();
                return view('anomali/excelRekapAnom', $data);
            } elseif ($isTemplate) {
                $data['listAnom'] = $builder->get()->getResultArray();
                return view('anomali/excelRekapTemplate', $data);
            } else {
                // Menghitung total data asli yang lolos filter
                $totalRows = $builder->countAllResults(false);

                $page = $this->request->getVar('page_group_assignment') ?? 1;
                $perPage = 25;
                $offset = ($page - 1) * $perPage;

                $data['listAnom'] = $builder->get($perPage, $offset)->getResultArray();

                // makeLinks() ini menghasilkan STRING HTML dari template 'my_pager'
                $pager = \Config\Services::pager();
                $data['pager'] = $pager->makeLinks($page, $perPage, $totalRows, 'my_pager', 0, 'group_assignment');
            }
        } catch (\Throwable $th) {
            $data['listAnom'] = [];
            $data['message'] = "Gagal memuat rekap assignment: " . $th->getMessage();
        }
        // dd($data['listAnom']);
        return view('anomali/rekapAnom', $data);
    }

    public function log()
    {
        $data['title'] = "Upload Konfirmasi";
        $userWilayah = auth()->user()->wilayah_kerja;

        // filter
        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        // data filter
        $data['listLevel'] = [
            [
                'id' => '',
                'nama' => "Semua Anomali",
            ],
        ];
        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];
        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel ?? []);

        $data['logs'] = [];

        $datalog = $this->logModel->select('log_upload.*,idn.secret AS email')
            ->join('auth_identities idn', 'id_user = idn.user_id')
            ->where('id_kegiatan', session()->get('aktif_kegiatan'))
            ->where('jenis', 'konfirmasi')
            ->orderBy('created_at', 'DESC');

        if ($data['filterLevel'] != '') {
            $datalog->where('log_upload.wilayah', $data['filterLevel']);
        }
        if ($userWilayah !== '1300') {
            $datalog->where('wilayah', $userWilayah);
        }

        $data['logs'] = $datalog->findAll();


        return view('anomali/logUploadKonfirmasi', $data);
    }

    public function store()
    {
        // dd('ini dijalankan');
        $file = $this->request->getFile('file_anomali');

        if ($file->isValid() && !$file->hasMoved()) {
            // 1. Simpan file ke folder writable/uploads
            $oldName = $file->getName();
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $idKegiatan = session()->get('aktif_kegiatan');
            $isRT = session()->get('is_rt');
            $wilayah = auth()->user()->wilayah_kerja;

            // 2. Buat catatan awal di tabel upload_logs (status: pending)
            $logId = $this->logModel->insert([
                'nama_file' => $newName,
                'nama_file_awal' => $oldName,
                'status'    => 'pending',
                'id_user'   => auth()->id(), // Siapa yang upload
                'id_kegiatan' => session()->get('aktif_kegiatan'),
                'jenis' => 'konfirmasi',
                'wilayah' => auth()->user()->wilayah_kerja,
            ]);

            // 3. PANGGIL COMMAND DI BACKGROUND
            // Kita kirimkan Nama File dan ID Log sebagai parameter
            // Tanda '&' di akhir perintah adalah kunci agar berjalan di background
            // $command = "php " . FCPATH . "../spark proses:wilayah " . $newName . " " . $logId . " > /dev/null 2>&1 &"; //command linux
            // $command = "start /B php " . FCPATH . "../spark proses:anomali " . $newName . " " . $logId . " " . $idKegiatan .  " " . $levelAnom; //command windows
            // $command = "start /B php " . FCPATH . "../spark proses:anomali " . $newName . " " . $logId . " " . $idKegiatan . " " . $levelAnom . " > NUL 2> NUL";
            // shell_exec($command);
            $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:konfirmasi ' . $newName . ' ' . $logId . ' ' . $wilayah . ' > NUL 2>&1"';
            // 2. Gunakan blok try-catch untuk menangkap jika fungsi dilarang
            try {
                if (function_exists('popen')) {
                    \pclose(\popen($command, "r"));
                } elseif (function_exists('shell_exec')) {
                    \shell_exec($command);
                } else {
                    // Catat di log jika semua fungsi eksekusi mati
                    log_message('error', 'Semua fungsi eksekusi shell (popen, shell_exec) dinonaktifkan di server.');

                    // buat agar pesan akan di proses secara berkala
                    $this->logModel->update($logId, [
                        'status' => 'pending',
                        'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => 'File akan di proses secara berkala sesuai antrian']])
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
            }

            return redirect()->to('/anomali/log')->with('message', 'Upload berhasil! Sistem sedang memproses data di latar belakang.');
        }
    }

    public function logDetil($id)
    {
        $log = $this->logModel->find($id);
        $errors = json_decode($log['error_details'], true);
        // dd($errors);
        $data['errors'] = $errors;

        if (empty($errors)) {
            return '
        <div class="text-center p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            <p class="text-secondary">Tidak ada rincian kesalahan untuk ID ini atau proses berhasil 100%.</p>
        </div>';
        }

        // Susun Tabel HTML dengan format Tabler
        $html = '<div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th class="w-1">Baris</th>
                            <th>Nama/Data</th>
                            <th>Keterangan Error</th>
                        </tr>
                    </thead>
                    <tbody>';

        // dd($errors);
        foreach ($errors as $err) {
            $pesanTampil = '';
            if (is_array($err['messages'])) {
                $pesanTampil = implode(', ', $err['messages']);
            } else {
                $pesanTampil = $err['messages'];
            }
            $html .= '<tr>
                    <td><span class="badge bg-red-lt">' . $err['baris'] . '</span></td>
                    <td class="small fw-bold text-uppercase">' . $err['data'] . '</td>
                    <td class="text-danger small">' . $pesanTampil . '</td>
                  </tr>';
        }

        $html .= '    </tbody>
                </table>
            </div>';

        return view('log_upload/log_comp_error', $data);
    }
}
