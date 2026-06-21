<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\SeMonitoringModel;
use App\Models\SeLogModel;
use App\Models\SeDuplikatModel;
use App\Models\SeNgibarModel;
use App\Models\SeListNgibarModel;
use CodeIgniter\Shield\Database\Migrations\CreateAuthTables;
use Config\Services;
use Faker\Provider\Lorem;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SeMonitoring extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;
    protected $seMonitoringModel;
    protected $seLogModel;
    protected $duplikatModel;
    protected $seNgibarModel;
    protected $db;
    protected $listNgibarModel;
    protected $idKegiatanPetugas;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
        $this->seMonitoringModel = new SeMonitoringModel();
        $this->seLogModel = new SeLogModel();
        $this->duplikatModel = new SeDuplikatModel();
        $this->seNgibarModel = new SeNgibarModel();
        $this->db = \Config\Database::connect();
        $this->listNgibarModel = new SeListNgibarModel();
        // Set zona waktu agar sinkron dengan ekspor data
        date_default_timezone_set('Asia/Jakarta');
        $this->idKegiatanPetugas = 4;
    }

    public function index()
    {
        $dataHead = $this->seMonitoringModel->jumlahSeluruhWilayah();
        $submitED = $dataHead[0]['jml_ed'];
        $sumbitNED = $dataHead[0]['jml_submit'] + $dataHead[0]['tbh_submit'] - $dataHead[0]['jml_ed'];
        $totalSubmit = $dataHead[0]['jml_submit'] + $dataHead[0]['tbh_submit'];
        $open = $dataHead[0]['jml_open'] + $dataHead[0]['tbh_open'];
        $ngibar = $dataHead[0]['tbh_submit'];
        $data = [
            "title" => "Monitoring Progress Sensus Ekonomi",
            "dataStatusKab" => $this->dataChartNgibar(),
            "dataHead" => [
                'total_submit' => $totalSubmit,
                'total_ED' => $submitED,
                'total_NED' => $sumbitNED
            ],
            "dataProgresNgibar" => [
                'label' => json_encode(['Submit Tambahan', 'Submit Prelist', 'Open']),
                'nilai' => json_encode([$ngibar, ($totalSubmit - $ngibar), $open]),
            ],
            "dataProgresFasih" => [
                'label' => json_encode(['Submit Ekonomi Digital', 'Submit Non Ekonomi Digital', 'Open']),
                'nilai' => json_encode([$submitED, $sumbitNED, $open]),
            ],
            "dataProsesSubmit" => [
                'label' => json_encode(['Submit Ekonomi Digital', 'Submit Non Ekonomi Digital']),
                'nilai' => json_encode([$submitED, $sumbitNED]),
            ],
            "dataPotensiKab" => $this->dataPotensiKab(),
            "dataNgibar" => $this->dataChartJmlAnom(),
            "dataTimeline" => $this->dataTimeline(),
        ];
        $data['dateUpdated'] = $this->seMonitoringModel->select("MAX(created_at) as terbaru")->get()->getRowArray()['terbaru'];
        return view('seMonitoring/monitoringAll', $data);
    }

    public function logs()
    {
        $data['title'] = 'Log Upload Monitoring SE';
        $data['logs'] = [
            (object) [
                'id'            => 1,
                'nama_file'     => 'daftar_ppl_kec_pulau_punjung.xlsx',
                'status'        => 'selesai',
                'total_baris'   => 150,
                'berhasil'      => 145,
                'gagal'         => 5,
                'error_details' => json_encode([
                    ['baris' => 12, 'data' => 'Budi Santoso', 'pesan' => ['Email bukan domain @bps.go.id']],
                    ['baris' => 45, 'data' => 'Susi Susanti', 'pesan' => ['Wilayah kerja tidak terdaftar']]
                ]),
                'created_at'    => '2026-04-27 09:00:00'
            ],
            (object) [
                'id'            => 2,
                'nama_file'     => 'data_pml_dharmasraya_final.xlsx',
                'status'        => 'proses',
                'total_baris'   => 500,
                'berhasil'      => 210,
                'gagal'         => 0,
                'error_details' => '[]',
                'created_at'    => '2026-04-27 10:15:30'
            ],
            (object) [
                'id'            => 3,
                'nama_file'     => 'wilayah_tugas_mitra_2026.xlsx',
                'status'        => 'gagal',
                'total_baris'   => 0,
                'berhasil'      => 0,
                'gagal'         => 0,
                'error_details' => json_encode([
                    ['baris' => '-', 'data' => 'Sistem', 'pesan' => ['File corrupt atau format tidak sesuai']]
                ]),
                'created_at'    => '2026-04-26 14:20:00'
            ],
            (object) [
                'id'            => 4,
                'nama_file'     => 'rekap_desa_nagari.xlsx',
                'status'        => 'pending',
                'total_baris'   => 85,
                'berhasil'      => 0,
                'gagal'         => 0,
                'error_details' => '[]',
                'created_at'    => '2026-04-27 10:30:00'
            ],
        ];

        $data['list_jenis'] = ['keseluruhan', 'ngibar', 'ngibar duplikat', 'daftar ngibar'];

        $datalog = $this->seLogModel->select('se_upload_log.*,idn.secret AS email')
            ->join('auth_identities idn', 'id_user = idn.user_id')
            ->where('id_kegiatan', session()->get('aktif_kegiatan'))->orderBy('created_at', 'DESC');

        if (! auth()->user()->inGroup('superadmin')) {
            $datalog->where('id_user', auth()->user()->id);
        }

        $data['logs'] = $datalog->findAll();

        return view('seMonitoring/logUpload', $data);
    }
    public function logsNgibar()
    {
        $data['title'] = 'Log Upload Monitoring Ngibar';

        $datalog = $this->seLogModel->select('se_upload_log.*')
            ->where('id_kegiatan', session()->get('aktif_kegiatan'))->orderBy('created_at', 'DESC');

        if (! auth()->user()->inGroup('superadmin')) {
            $datalog->where('id_user', auth()->user()->id);
        }

        $data['logs'] = $datalog->findAll();

        return view('seMonitoring/logUpload', $data);
    }

    public function downloadTemplate()
    {
        return $this->response->download(FCPATH . 'assets\templates\template_monitoring.xlsx', null);
    }

    public function store()
    {
        $file = $this->request->getFile('file_monitoring');
        $jenis = $this->request->getVar('sel-jenis');

        // membaca file
        $spreadsheet = IOFactory::load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        // simpan di log
        $logData['id_kegiatan'] = session()->get('aktif_kegiatan');
        $logData['nama_file'] = $file->getName();
        $logData['total_baris'] = count($sheetData) - 1;
        $logData['id_user'] = auth()->user()->id;
        $logData['jenis'] = $jenis;
        $idLog = $this->seLogModel->insert($logData);

        // cek validitas
        if (!$file->isValid() && $file->hasMoved()) return redirect()->back()->with('message_errors', 'File tidak valid');

        switch ($jenis) {
            case 'keseluruhan': //monitoring keseluruhan
                // Validasi
                $validation = \Config\Services::validation();
                $rules = [
                    'kd_wilayah' => 'required|in_list[1301,1302,1303,1304,1305,1306,1307,1308,1309,1310,1311,1312,1371,1372,1373,1374,1375,1376,1377]'
                ];
                $messages = [
                    'kd_wilayah' => ['in_list' => 'Wilayah tidak match dengan wilayah Sumatera Barat']
                ];

                $errors = []; // Tempat menampung error per baris
                $totalSuccess = 0;

                foreach ($sheetData as $index => $row) {
                    if ($index === 0) continue;
                    $rowNum = $index + 1; // Untuk penanda baris di pesan error

                    $data['kd_wilayah'] = $row[0];
                    $data['id_log'] = $idLog;
                    $data['jml_open'] = $row[1];
                    $data['jml_submit'] = $row[2];
                    $data['tbh_open'] = $row[3];
                    $data['tbh_submit'] = $row[4];
                    $data['jml_ed'] = $row[5];
                    // dd($validation->setRules($rules, $messages)->run($data));

                    if (!$validation->setRules($rules, $messages)->run($data)) {
                        // 2. Jika gagal, simpan pesan error berdasarkan nomor baris
                        $errors[] = [
                            'baris' => $rowNum,
                            'kd_wilayah' => $data['kd_wilayah'],
                            'messages' => $validation->getErrors()
                        ];
                    } else {
                        $idMonitoring = $this->seMonitoringModel->insert($data);
                        $totalSuccess++;
                    }
                }

                // --- LOGIKA UPDATE AKHIR (DI LUAR LOOP) ---
                $finalStatus = (count($errors) > 0) ? 'gagal' : 'selesai';
                if ($finalStatus === 'gagal') {
                    // Hapus data monitoring yang sempat masuk jika ada satu saja yang gagal
                    $this->seMonitoringModel->where('id_log', $idLog)->delete();
                }

                $logData['error_details'] = json_encode($errors);
                $logData['berhasil'] = $totalSuccess;
                $logData['gagal'] = count($errors);
                $logData['status'] = $finalStatus;

                $this->seLogModel->update($idLog, $logData);

                $msgType = ($finalStatus === 'selesai') ? 'message' : 'message_error';
                $msgBody = ($finalStatus === 'selesai') ? 'Berhasil Menambahkan Data Monitoring' : 'Gagal upload, cek detailnya di log';

                return  redirect()->back()->with($msgType, $msgBody);
                break;
            case 'ngibar': //monitoring ngibar
                return $this->prosesUploadNgibar($idLog);
                break;
            case 'ngibar duplikat': //daftar assgiement ngibar duplikat
                if (isset($sheetData[1][4])) {
                    $idWilayahTarget = $sheetData[1][4];

                    // KUNCI UTAMA: Hapus semua data lama khusus untuk wilayah ini saja!
                    $this->db->table('se_cekduplikat')->where('id_wilayah', $idWilayahTarget)->delete();
                } else {
                    // Jika file kosong atau salah format, gagalkan transaksi
                    $this->db->transRollback();
                    return redirect()->back()->with('error', 'Format file Excel tidak sesuai atau data kosong.');
                }
                //Baca file Excel/CSV (Menggunakan PhpSpreadsheet)
                $totalBaris = 0;

                // Sesuaikan indeks kolom berdasarkan output file Python kamu
                // Asumsi Baris 0 = Header. Struktur: id_mirip, id_assignment, kode_identitas, nama_usaha, id_wilayah, alamat_usaha, email, status, date_updated
                for ($i = 1; $i < count($sheetData); $i++) {
                    if (empty($sheetData[$i][2])) continue; // Skip jika kode_identitas kosong

                    $totalBaris++;

                    $this->db->table('se_cekduplikat')->insert([
                        'id_mirip'       => $sheetData[$i][0], // Hasil mapping dari python
                        'id_assignment'  => $sheetData[$i][1], // Hasil mapping dari python
                        'kode_identitas' => $sheetData[$i][2],
                        'nama_usaha'     => $sheetData[$i][3],
                        'id_wilayah'     => $sheetData[$i][4], // Bernilai sama dengan $idWilayahTarget
                        'alamat_usaha'   => $sheetData[$i][5],
                        'email'          => $sheetData[$i][6],
                        'status'         => $sheetData[$i][7],
                        'date_updated'   => date('Y-m-d H:i:s', strtotime($sheetData[$i][8])),
                        'flag_status'    => 'Pending' // Kembali ke status awal untuk di-flag ulang oleh operator
                    ]);
                }

                // Update status log menjadi selesai
                $this->db->table('se_upload_log')->where('id', $idLog)->update([
                    'status'      => 'selesai',
                    'total_baris' => $totalBaris,
                    'berhasil' => $totalBaris,
                ]);

                $this->db->transComplete();

                return redirect()->to(base_url('se/upload'))->with('message', "Berhasil mengunggah {$totalBaris} data duplikat hasil analisis Python.");
                break;
            case 'daftar ngibar':
                // Ambil id_wilayah dari baris pertama data (Baris indeks 1, Kolom indeks 1)
                // Sesuaikan indeks kolom [1] ini dengan letak kolom 'id_wilayah' di file Excel kamu
                if (isset($sheetData[1][1])) {
                    $idWilayahTarget = $sheetData[1][1];

                    // KUNCI UTAMA: Hapus semua data lama di se_list_ngibar yang memiliki kode wilayah yang sama
                    $this->db->table('se_list_ngibar')->where('id_wilayah', $idWilayahTarget)->delete();
                } else {
                    $this->db->transRollback();
                    return redirect()->back()->with('message_error', 'Format file Excel salah atau kolom Wilayah kosong.');
                }

                $totalBaris = 0;

                // 3. Looping untuk memasukkan data baru dari Excel
                // Asumsi Struktur Kolom Excel: 
                // [0] jenis_kegiatan, [1] id_wilayah, [2] nama_usaha, [3] kode_identitas, [4] alamat_usaha, [5] email, [6] status, [7] date_updated
                for ($i = 1; $i < count($sheetData); $i++) {
                    if (empty($sheetData[$i][3])) continue; // Skip jika kode_identitas kosong

                    $totalBaris++;

                    $this->db->table('se_list_ngibar')->insert([
                        'id_log'         => $idLog,
                        'jenis_kegiatan' => $sheetData[$i][0],
                        'id_wilayah'     => $sheetData[$i][1], // Nilainya pasti sama dengan $idWilayahTarget
                        'nama_usaha'     => $sheetData[$i][2],
                        'kode_identitas' => $sheetData[$i][3],
                        'alamat_usaha'   => $sheetData[$i][4],
                        'email'          => $sheetData[$i][5],
                        'status'         => $sheetData[$i][6],
                        // Simpan date_updated dari lapangan, jika kosong gunakan waktu sekarang
                        'date_updated'   => !empty($sheetData[$i][7]) ? date('Y-m-d H:i:s', strtotime($sheetData[$i][7])) : date('Y-m-d H:i:s')
                    ]);
                }

                // 4. Update status log upload menjadi selesai
                $this->db->table('se_upload_log')->where('id', $idLog)->update([
                    'status'      => 'selesai',
                    'total_baris' => $totalBaris,
                    'berhasil' => $totalBaris,
                ]);

                $this->db->transComplete();


                return redirect()->back()->with('message', "Data lama se_list_ngibar wilayah {$idWilayahTarget} berhasil dibersihkan dan diganti dengan {$totalBaris} data baru.");
                break;

            default:
                return redirect()->back()->with('message_errors', 'Jenis Monitoting tidak terdefinisi');
                break;
        }
    }

    public function hapus()
    {
        $idLog = $this->request->getPost('id');
        $this->seMonitoringModel->where('id_log', $idLog)->delete();
        $this->seLogModel->delete($idLog);
        return redirect()->back()->with('message', 'Berhasail hapus data');
    }

    public function manajemenAction()
    {
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if ($action === "toggle") {
            $is_show = $this->request->getVar('is_show');
            $data = ['is_show' => !$is_show];
            $this->katAnomaliModel->update($id, $data);
            return redirect()->back()->with('message', 'Status Lihat diubah');
        } elseif ($action === "delete") {
            $this->katAnomaliModel->delete($id);
            return redirect()->back()->with('message', 'anomali berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'aksi tidak valid');
        }
        // return view('anomali/manajemen');
    }

    public function edit($id)
    {
        $data = [
            "title" => "Upload Anomali",
            "data" => [
                'id' => $id,
                'kode_anomali' => 'AN21',
                'definisi_anomali' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam nesciunt voluptatem dolorem pariatur reiciendis aspernatur provident itaque! Eveniet necessitatibus laboriosam, omnis excepturi sed architecto dolores repudiandae quisquam expedita facere. Sapiente.',
                'detil_anomali' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita, impedit quasi earum facilis officia est enim sit distinctio repellat recusandae!',
                'is_show' => true,
            ]

        ];

        $data['data'] = $this->katAnomaliModel->find($id);
        // dd($data);

        return view('ManajAnom/edit', $data);
    }

    public function updateKategori()
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        $data = $this->request->getPost();

        if ($data['is_show'] == "show_id_" . $data['id']) {
            $data['is_show'] = true;
        } else {
            $data['is_show'] = false;
        }

        if ($this->katAnomaliModel->save($data) === false) {
            session()->setFlashdata($this->katAnomaliModel->errors());
            return redirect()->back()
                ->withInput()
                ->with('message_errors', 'Gagal Simpan Data');
        }

        return redirect()->to(base_url('/manajemen-anomali/list'))->with('message', 'data berhasil di update');
    }

    public function upload()
    {
        $data = [
            "title" => "Upload Anomali"
        ];
        return view('manajAnom/upload', $data);
    }

    public function template()
    {
        $template = "ini adalah template";
        dd($template);
    }

    public function dataChartJmlAnom($id_kat = null)
    {
        $dataModel = null;
        $judulBaris = null;

        $dataModel = $this->seMonitoringModel->jumlahKonfirmasiByWiayah();
        $judulBaris = array_column($dataModel, 'kd_wilayah');
        $jumlah_ed = array_column($dataModel, 'jumlah_ED');
        $jumlah_submit = array_column($dataModel, 'jumlah_submit');
        $jumlah_total = array_column($dataModel, 'jumlah_total');
        $tampil_submit = array_map(fn($a, $b) => $a - $b, $jumlah_submit, $jumlah_ed);
        $tampil_sisa = array_map(fn($a, $b) => $a - $b, $jumlah_total, $jumlah_submit);
        $data =  [
            'labels' => json_encode($judulBaris),
            'datesets' => [
                [
                    'label' => json_encode(['Submit Ekonomi Digital']),
                    'nilai' => json_encode($jumlah_ed),
                ],
                [
                    'label' => json_encode(['Submit Non Ekonomi Digital']),
                    'nilai' => json_encode($tampil_submit),
                ],
                [
                    'label' => json_encode(['Open']),
                    'nilai' => json_encode($tampil_sisa),
                ],
            ],
        ];
        return $data;
    }

    public function dataPotensiKab($id_kat = null)
    {
        $dataModel = null;
        $judulBaris = null;
        $idPotensiED = 2;
        $dataPotensi = $this->anomaliModel;

        $dataModel = $this->seMonitoringModel->jumlahKonfirmasiByWiayah();
        $judulBaris = array_column($dataModel, 'kd_wilayah');
        $jumlah_ed = array_column($dataModel, 'jumlah_ED');
        $jumlah_submit = array_column($dataModel, 'jumlah_submit');
        $jumlah_total = array_column($dataModel, 'jumlah_total');
        $tampil_submit = array_map(fn($a, $b) => $a - $b, $jumlah_submit, $jumlah_ed);
        $tampil_sisa = array_map(fn($a, $b) => $a - $b, $jumlah_total, $jumlah_submit);
        $potensiDigital = ['15', '31', '16', '45', '32', '48', '32', '68', '14', '22', '50', '44', '35', '25', '42', '43', '35', '25', '43', '32',];
        $potensiDigitalfalse = ['12', '65', '24', '43', '25', '42', '35', '15', '63', '45', '22', '14', '34', '25', '34', '51', '25', '34', '26', '17',];
        $submitBukan = ['34', '24', '25', '52', '24', '35', '50', '15', '37', '15', '35', '42', '35', '15', '15', '17', '64', '32', '38', '31',];
        $data =  [
            'labels' => json_encode($judulBaris),
            'datesets' => [
                [
                    'label' => json_encode(['Submit Ekonomi Digital']),
                    'nilai' => json_encode($jumlah_ed),
                ],
                [
                    'label' => json_encode(['Potensi Ekonomi Digital True']),
                    'nilai' => json_encode($potensiDigital),
                ],
                [
                    'label' => json_encode(['Potensi Ekonomi Digital False']),
                    'nilai' => json_encode($potensiDigitalfalse),
                ],
                [
                    'label' => json_encode(['Submit Bukan Ekonomi Digital']),
                    'nilai' => json_encode($submitBukan),
                ],
            ],
        ];
        return $data;
    }

    public function dataChartNgibar($id_kat = null)
    {
        $dataModel = null;
        $judulBaris = null;

        $dataModel = $this->seMonitoringModel->getDataTerbaru();
        $judulBaris = array_column($dataModel, 'kd_wilayah');
        $jml_submit = array_column($dataModel, 'jml_submit');
        $jml_open = array_column($dataModel, 'jml_open');
        $tbh_submit = array_column($dataModel, 'tbh_submit');
        $tbh_open = array_column($dataModel, 'tbh_open');
        $jml_ed = array_column($dataModel, 'jml_ed');
        $jumlah_total = array_column($dataModel, 'jumlah_total');
        $open = array_map(fn($a, $b) => $a + $b, $tbh_open, $jml_open);
        $data =  [
            'labels' => json_encode($judulBaris),
            'datesets' => [
                [
                    'label' => json_encode(['Submit Ngibar/Mandiri']),
                    'nilai' => json_encode($tbh_submit),
                ],
                [
                    'label' => json_encode(['Submit Petugas']),
                    'nilai' => json_encode($jml_submit),
                ],
                [
                    'label' => json_encode(['Open']),
                    'nilai' => json_encode($open),
                ],
            ],
        ];
        return $data;
    }

    public function dataChartProses($string = "proses", $id_kat = '')
    {
        $array = [];
        // $array = $this->anomaliModel->jumlahProses("non_public");
        // dd($array);
        switch ($string) {
            case "proses":
                $array = $this->anomaliModel->jumlahProses("", $id_kat);
                break;
            case "public":
                $array = $this->anomaliModel->jumlahProses("public", $id_kat);
                break;
            case "non_public":
                $array = $this->anomaliModel->jumlahProses("non_public", $id_kat);
                break;
            case "flag1":
                $array = $this->anomaliModel->jumlahProses("flag1", $id_kat);
                break;
            case "flag2":
                $array = $this->anomaliModel->jumlahProses("flag2", $id_kat);
                break;
            case "flag3":
                $array = $this->anomaliModel->jumlahProses("flag3", $id_kat);
                break;
            default:
                $array = $this->anomaliModel->jumlahProses("", $id_kat);
                break;
        }
        // cek if na
        $data = [$array[0]['jumlah_terisi'], $array[0]['jumlah_total'] - $array[0]['jumlah_terisi']];

        $data = array_map(function ($val) {
            return ($val === null) ? 0 : $val;
        }, $data);
        return ($data);
    }

    public function dataTimeline($id_kat = '')
    {
        // $hasil = $this->anomaliModel->jumlahByTanggal($id_kat);
        $hasil = $this->seMonitoringModel->jumlahByTanggal();



        $data = [
            'labels' => json_encode(array_column($hasil, 'tanggal')),
            'datasets' => [
                [
                    'label' => json_encode(['Submited ekonomi digital']),
                    'nilai' => json_encode(array_column($hasil, 'jumlah_ed'))
                ],
                [
                    'label' => json_encode(['Submited assigment']),
                    'nilai' => json_encode(array_column($hasil, 'jumlah_submit'))
                ],
                [
                    'label' => json_encode(['Total Assigment']),
                    'nilai' => json_encode(array_column($hasil, 'jumlah_total'))
                ],
            ]
        ];
        return ($data);
    }

    public function dataWordCloud($id_kat = '')
    {
        $data = $this->anomaliModel->wordCloudKonfirmasi($id_kat);
        // dd($data);
        return ($data);
    }

    public function listDuplikat()
    {
        $db = \Config\Database::connect();
        $idWilayah = '1311';

        // 1. Mengambil Tanggal dan Waktu Upload Terakhir dari Log (Jenis: ngibar duplikat)
        $lastUploadLog = $db->table('se_upload_log')
            ->where('jenis', 'ngibar duplikat')
            ->where('status', 'selesai')
            ->orderBy('created_at', 'DESC')
            ->get()->getRowArray();

        $waktuUploadTerakhir = $lastUploadLog ? $lastUploadLog['created_at'] : null;

        // 2. Konfigurasi Pagination Kelompok Kasus
        $pager = \Config\Services::pager();
        $page  = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1;
        $perPage = 5;

        $totalGroups = $db->table('se_cekduplikat')
            ->select('id_mirip')
            ->where('id_wilayah', $idWilayah)
            ->groupBy('id_mirip')
            ->get()->getNumRows();

        $offset = ($page - 1) * $perPage;
        $activeGroupsRaw = $db->table('se_cekduplikat')
            ->select('id_mirip')
            ->where('id_wilayah', $idWilayah)
            ->groupBy('id_mirip')
            ->orderBy('id_mirip', 'ASC')
            ->limit($perPage, $offset)
            ->get()->getResultArray();

        $groupedAssignments = [];

        if (!empty($activeGroupsRaw)) {
            $activeGroupIds = array_column($activeGroupsRaw, 'id_mirip');

            $rawAssignments = $db->table('se_cekduplikat')
                ->whereIn('id_mirip', $activeGroupIds)
                ->orderBy('id_mirip', 'ASC')
                ->get()->getResultArray();

            foreach ($rawAssignments as $row) {
                $groupedAssignments[$row['id_mirip']][] = $row;
            }
        }

        $pagerLinks = $pager->makeLinks($page, $perPage, $totalGroups, 'default_full');

        return view('seMonitoring/duplikat_view', [
            'title'              => 'Validasi & Flag Duplikasi Wilayah ' . $idWilayah,
            'idWilayah'          => $idWilayah,
            'groupedAssignments' => $groupedAssignments,
            'pagerLinks'         => $pagerLinks, // Link navigasi halaman
            'waktuUploadTerakhir' => $waktuUploadTerakhir // Dikirim ke view
        ]);
    }

    // FUNGSI UNTUK MEM-FLAG PILIHAN OPERATOR
    public function flagDuplikat($idCek, $idMirip)
    {
        $userName = auth()->user()->name;
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Set entri yang dipilih menjadi 'Dipilih', dan pasangannya di grup yang sama menjadi 'Dieliminasi'
        $db->table('se_cekduplikat')
            ->where('id_mirip', $idMirip)
            ->update(['flag_status' => 'Dieliminasi', 'updated_by' => $userName]); // Asumsi user login

        $db->table('se_cekduplikat')
            ->where('id', $idCek)
            ->update(['flag_status' => 'Dipilih', 'updated_by' => $userName]);

        // NB: Di sini kamu juga bisa menyelipkan query untuk menghapus/menonaktifkan 
        // data yang tidak terpilih di tabel utama `se_list_ngibar` jika diperlukan.

        $db->transComplete();

        return redirect()->back()->with('message', 'Keputusan flag berhasil disimpan dan diperbarui.');
    }

    // Proses Upload dan Timpa Data Wilayah
    public function uploadDuplikat()
    {
        $file = $this->request->getFile('file_excel');
        $idWilayah = $this->request->getPost('id_wilayah');

        if (!$idWilayah) {
            return redirect()->back()->with('error', 'Wilayah kerja harus ditentukan!');
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {

            // Baca Excel dengan PhpSpreadsheet
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Mulai Transaksi Database agar aman
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. HAPUS DATA LAMA DI WILAYAH INI SEBELUMNYA
            $this->duplikatModel->where('id_wilayah', $idWilayah)->delete();

            // 2. INSERT DATA BARU (Mulai baris ke-2 jika baris 1 adalah header)
            $dataInsert = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                if (empty($sheetData[$i][0])) continue; // Skip jika id_mirip kosong

                $dataInsert[] = [
                    'id_mirip'       => $sheetData[$i][0], // Kolom A
                    'kode_identitas' => $sheetData[$i][1], // Kolom B
                    'nama_usaha'     => $sheetData[$i][2], // Kolom C
                    'id_wilayah'     => $idWilayah,
                ];
            }

            if (!empty($dataInsert)) {
                $this->duplikatModel->insertBatch($dataInsert);
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Gagal memperbarui data duplikat.');
            }

            return redirect()->to(base_url('se/duplikat?wilayah=' . $idWilayah))->with('message', 'Data duplikat wilayah berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'File tidak valid atau gagal diunggah.');
    }

    public function monitorNgibar()
    {
        $latestLogId = $this->seNgibarModel->getLatestLogId();

        // Ambil data datetime terupdate dari log
        $logRow = $this->db->table('se_upload_log')->where('id', $latestLogId)->get()->getRow();
        $dateUpdated = $logRow ? date('d M Y H:i', strtotime($logRow->created_at)) . ' WIB' : 'Belum ada data';

        // Penyusunan Data Line Chart Timeline harian
        $timelineRaw = $this->seNgibarModel->getTimelineData();
        $dataTimeline = ['labels' => [], 'submitted' => [], 'rejected' => [], 'draft' => [], 'open' => []];
        foreach ($timelineRaw as $t) {
            $dataTimeline['labels'][]    = $t['label'];
            $dataTimeline['submitted'][] = (int)$t['submitted'];
            $dataTimeline['rejected'][]  = (int)$t['rejected'];
            $dataTimeline['draft'][]     = (int)$t['draft'];
            $dataTimeline['open'][]      = (int)$t['open'];
        }
        // dd(implode(',', $this->seNgibarModel->getPieData($latestLogId)));

        return view('seMonitoring/monitoringNgibar', [
            'title'        => 'Monitoring Pengisian Mandiri (Ngibar) SE2026',
            'dateUpdated'  => $dateUpdated,
            'dataHead'     => $this->seNgibarModel->getLatestSummary($latestLogId),
            'dataPie'      => [
                'keseluruhan' => $this->seNgibarModel->getPieData($latestLogId),
                'se2026_l'    => $this->seNgibarModel->getPieData($latestLogId, 'SE2026-L'),
                'se2026_ub'   => $this->seNgibarModel->getPieData($latestLogId, 'SE2026-UB'),
            ],
            'dataBar'      => [
                'keseluruhan' => $this->seNgibarModel->getBarData($latestLogId),
                'se2026_l'    => $this->seNgibarModel->getBarData($latestLogId, 'SE2026-L'),
                'se2026_ub'   => $this->seNgibarModel->getBarData($latestLogId, 'SE2026-UB'),
            ],
            'dataTimeline' => $dataTimeline
        ]);
    }

    // FUNGSI UNTUK MENYIMPAN IMPORT EXCEL KE DATABASE
    public function prosesUploadNgibar($logId)
    {
        $file = $this->request->getFile('file_monitoring');
        if ($file && $file->isValid() && !$file->hasMoved()) {

            $this->db->transStart(); // Amankan dengan database transaction

            // 2. Baca file Excel menggunakan IOFactory::load
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();

            $insertBuffer = [];
            // Looping baris data (Lewati baris 1 karena merupakan header kolom)
            for ($i = 1; $i < count($sheetData); $i++) {
                if (empty($sheetData[$i][0])) continue; // Lewati jika baris kosong

                $insertBuffer[] = [
                    'id_log'             => $logId,
                    'jenis_kegiatan'     => $sheetData[$i][0], // Kolom A
                    'kode_wilayah'       => $sheetData[$i][1], // Kolom B
                    'status'             => $sheetData[$i][2], // Kolom C
                    'jumlah_assignment'  => (int)$sheetData[$i][3], // Kolom D
                ];
            }

            if (!empty($insertBuffer)) {
                $this->seNgibarModel->insertBatch($insertBuffer);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return redirect()->to(base_url('se/upload'))->with('message_error', 'Gagal memproses berkas Excel.');
            }
            return redirect()->to(base_url('se/upload'))->with('message', 'Data pemantauan Ngibar berhasil diperbarui.');
        }

        return redirect()->to(base_url('se/upload'))->with('message_error', 'Berkas unggahan tidak valid.');
    }

    public function listNgibar()
    {
        $userWilayah = auth()->user()->wilayah_kerja;
        // Tangkap parameter filter status dari URL (jika ada)
        $sel_status = $this->request->getGet('sel_status') ?? '';
        $sel_wilayah = $this->request->getGet('sel_wilayah') ?? $userWilayah;
        $listWilayah = [];

        // Ambil data dari model
        $assignments = $this->listNgibarModel->getListByWilayah($sel_wilayah, $sel_status);

        $listStatus = [
            '' => 'Keseluruhan',
            'Draft' => 'Draft',
            'Submitted Respondent' => 'Submitted',
            'Rejected Admin' => 'Rejected',
        ];
        if ($sel_wilayah === '1300') {
            $listWilayah = $this->listNgibarModel->getAvailabelWilayah();
        } else {
            $listWilayah = [['id_wilayah' => $sel_wilayah]];
        }

        return view('seMonitoring/detail_ngibar_view', [
            'title'         => 'Daftar Kendali Mandiri Wilayah ' . $sel_wilayah,
            'idWilayah'     => $sel_wilayah,
            'sel_status' => $sel_status,
            'assignments'   => $assignments,
            'list_status' => $listStatus,
            'list_wilayah' => $listWilayah,
        ]);
    }

    // +++++++++++++++++++++++++++++++++++++++
    // Fungsi untuk monitoring Progres
    // +++++++++++++++++++++++++++++++++++++++
    public function monitoringUB()
    {
        $selectedWilayah = $this->request->getGet('wilayah') ?? '1300'; // 1300 = Seluruh Prov Sumbar
        $filterTanggal    = $this->request->getGet('tanggal') ?? '';

        // Ambil halaman saat ini untuk keperluan nomor urut tabel (default halaman 1)
        $currentPage      = $this->request->getGet('page') ?? 1;
        $perPage          = 15;

        $data['title'] = "Monitoring UB";

        // Master daftar Kabupaten/Kota di Sumatera Barat
        $data['kab_kota'] = [
            '1301' => 'Kep. Mentawai',
            '1302' => 'Pesisir Selatan',
            '1303' => 'Solok',
            '1304' => 'Sijunjung',
            '1305' => 'Tanah Datar',
            '1306' => 'Padang Pariaman',
            '1307' => 'Agam',
            '1308' => 'Lima Puluh Kota',
            '1309' => 'Pasaman',
            '1310' => 'Pasaman Barat',
            '1311' => 'Dharmasraya',
            '1312' => 'Solok Selatan',
            '1371' => 'Padang',
            '1372' => 'Solok Kota',
            '1373' => 'Sawahlunto',
            '1374' => 'Padang Panjang',
            '1375' => 'Bukittinggi',
            '1376' => 'Payakumbuh',
            '1377' => 'Pariaman'
        ];

        $data['selected_wilayah'] = $selectedWilayah;
        $data['filter_tanggal']    = $filterTanggal;

        // --- Definisi SQL String Pengelompokan Status Berdasarkan Aturan Baru ---
        // Karena rejected_by_pengawas masuk ke draft dan submitted, kita hitung di kedua kondisi secara adil
        $sqlDraft     = "SUM(CASE WHEN status IN ('draft', 'rejected_by_pengawas') THEN 1 ELSE 0 END)";
        $sqlSubmitted = "SUM(CASE WHEN status IN ('submitted_by_pencacah', 'submitted_respondent', 'rejected_by_pengawas') THEN 1 ELSE 0 END)";
        $sqlApproved  = "SUM(CASE WHEN status IN ('approved_by_pengawa', 'rejected_by_admin_kabupaten', 'revoked_by_pengawas') THEN 1 ELSE 0 END)";
        $sqlOpen      = "SUM(CASE WHEN status IN ('open') THEN 1 ELSE 0 END)";


        // --- 1. DATA PIE CHART (STATUS TERBARU DENGAN MAPPING) ---
        $builderPie = $this->db->table('se_list_se26_ub')
            ->select("
            {$sqlDraft} as DRAFT,
            {$sqlSubmitted} as SUBMITTED,
            {$sqlApproved} as APPROVED,
            {$sqlOpen} as OPEN
        ");

        if ($selectedWilayah !== '1300') {
            $builderPie->where('id_wilayah', $selectedWilayah);
        }
        $pieQuery = $builderPie->get()->getRowArray();

        $pieData = [
            'APPROVED'  => (int)($pieQuery['APPROVED'] ?? 0),
            'SUBMITTED' => (int)($pieQuery['SUBMITTED'] ?? 0),
            'DRAFT'     => (int)($pieQuery['DRAFT'] ?? 0),
            'OPEN'      => (int)($pieQuery['OPEN'] ?? 0)
        ];
        $data['pie_json'] = json_encode(array_values($pieData));
        $data['cards'] = [
            'total' => $pieData['DRAFT'] + $pieData['APPROVED'] + $pieData['SUBMITTED'] + $pieData['OPEN'],
            'open' => $pieData['OPEN'],
            'approved' => $pieData['APPROVED'],
            'submitted' => $pieData['SUBMITTED'],
            'draft' => $pieData['DRAFT'],
        ];


        // --- 2. DATA BAR CHART (WILAYAH ATAU TIM PJ) ---
        $barLabels = [];
        $barSeries = ['DRAFT' => [], 'APPROVED' => [], 'SUBMITTED' => [], 'OPEN' => []];

        if ($selectedWilayah === '1300') {
            // Tampilkan Bar Chart per Kabupaten
            foreach ($data['kab_kota'] as $kode => $nama) {
                $barLabels[] = $nama;

                $kabQuery = $this->db->table('se_list_se26_ub')
                    ->select("
                    {$sqlDraft} as DRAFT,
                    {$sqlApproved} as APPROVED,
                    {$sqlSubmitted} as SUBMITTED,
                    {$sqlOpen} as OPEN
                ")
                    ->where('id_wilayah', $kode)
                    ->get()->getRowArray();

                $barSeries['DRAFT'][]     = (int)($kabQuery['DRAFT'] ?? 0);
                $barSeries['APPROVED'][]  = (int)($kabQuery['APPROVED'] ?? 0);
                $barSeries['SUBMITTED'][] = (int)($kabQuery['SUBMITTED'] ?? 0);
                $barSeries['OPEN'][]      = (int)($kabQuery['OPEN'] ?? 0);
            }
        } else {
            // Tampilkan Bar Chart per Tim Penanggung Jawab di Kab Terpilih
            $pjQuery = $this->db->table('se_list_se26_ub')
                ->select('IFNULL(tim_pj, "Belum Ada PJ") as pj')
                ->where('id_wilayah', $selectedWilayah)
                ->groupBy('tim_pj')
                ->get()->getResultArray();

            foreach ($pjQuery as $pjRow) {
                $barLabels[] = $pjRow['pj'];

                $pjQueryCount = $this->db->table('se_list_se26_ub')
                    ->select("
                    {$sqlDraft} as DRAFT,
                    {$sqlSubmitted} as SUBMITTED,
                    {$sqlApproved} as APPROVED,
                    {$sqlOpen} as OPEN
                ")
                    ->where('id_wilayah', $selectedWilayah);

                if ($pjRow['pj'] === "Belum Ada PJ") {
                    $pjQueryCount->where('tim_pj IS NULL');
                } else {
                    $pjQueryCount->where('tim_pj', $pjRow['pj']);
                }

                $pjRes = $pjQueryCount->get()->getRowArray();

                $barSeries['DRAFT'][]     = (int)($pjRes['DRAFT'] ?? 0);
                $barSeries['SUBMITTED'][] = (int)($pjRes['SUBMITTED'] ?? 0);
                $barSeries['APPROVED'][]  = (int)($pjRes['APPROVED'] ?? 0);
                $barSeries['OPEN'][]      = (int)($pjRes['OPEN'] ?? 0);
            }
        }
        $data['bar_json'] = json_encode([
            'labels' => $barLabels,
            'datasets' => [
                ['label' => 'APPROVED', 'data' => $barSeries['APPROVED'], 'backgroundColor' => '#94C11F'],
                ['label' => 'SUBMITTED', 'data' => $barSeries['SUBMITTED'], 'backgroundColor' => '#0369A1'],
                ['label' => 'DRAFT', 'data' => $barSeries['DRAFT'], 'backgroundColor' => '#EE8911'],
                ['label' => 'OPEN', 'data' => $barSeries['OPEN'], 'backgroundColor' => '#B3B3B3'],
            ]
        ]);


        // --- 3. DATA LINE CHART HISTORIS (Mengambil dari se_mon_ub dengan aturan baru) ---
        $builderLine = $this->db->table('se_mon_ub')->select('tanggal')->groupBy('tanggal')->orderBy('tanggal', 'ASC');
        if ($selectedWilayah !== '1300') {
            $builderLine->where('id_wilayah', $selectedWilayah);
        }
        $tglQuery = $builderLine->get()->getResultArray();

        $lineLabels = [];
        $lineSeries = ['DRAFT' => [], 'APPROVED' => [], 'SUBMITTED' => [], 'OPEN' => []];

        // Query Aggregation Khusus untuk se_mon_ub karena datanya sudah berbentuk rows per-status mentah
        $sqlMonDraft     = "SUM(CASE WHEN status IN ('draft', 'rejected_by_pengawas') THEN jumlah ELSE 0 END)";
        $sqlMonApproved  = "SUM(CASE WHEN status IN ('approved_by_pengawa', 'rejected_by_admin_kabupaten', 'revoked_by_pengawas') THEN jumlah ELSE 0 END)";
        $sqlMonSubmitted = "SUM(CASE WHEN status IN ('submitted_by_pencacah', 'submitted_respondent', 'rejected_by_pengawas') THEN jumlah ELSE 0 END)";
        $sqlMonOpen      = "SUM(CASE WHEN status IN ('open') THEN jumlah ELSE 0 END)";

        foreach ($tglQuery as $tRow) {
            $lineLabels[] = date('d M Y', strtotime($tRow['tanggal']));

            $sumQuery = $this->db->table('se_mon_ub')
                ->select("
                {$sqlMonDraft} as DRAFT,
                {$sqlMonApproved} as APPROVED,
                {$sqlMonSubmitted} as SUBMITTED,
                {$sqlMonOpen} as OPEN
            ")
                ->where('tanggal', $tRow['tanggal']);

            if ($selectedWilayah !== '1300') {
                $sumQuery->where('id_wilayah', $selectedWilayah);
            }

            $res = $sumQuery->get()->getRowArray();

            $lineSeries['DRAFT'][]     = (int)($res['DRAFT'] ?? 0);
            $lineSeries['APPROVED'][]  = (int)($res['APPROVED'] ?? 0);
            $lineSeries['SUBMITTED'][] = (int)($res['SUBMITTED'] ?? 0);
            $lineSeries['OPEN'][]      = (int)($res['OPEN'] ?? 0);
        }

        $data['line_json'] = json_encode([
            'labels'   => $lineLabels,
            'draft'    => $lineSeries['DRAFT'],
            'approved' => $lineSeries['APPROVED'],  // Dialihkan memetakan data APPROVED agar masuk ke struktur Line Chart Anda sebelumnya
            'submited' => $lineSeries['SUBMITTED'], // Tetap pakai key lama view Anda 'submited' agar chart tidak broken
            'open'     => $lineSeries['OPEN']
        ]);


        // --- 4. LIST TABEL DENGAN PAGINATION MANUAL (TANPA MODEL) ---
        $builderList = $this->db->table('se_list_se26_ub');
        if ($selectedWilayah !== '1300') {
            $builderList->where('id_wilayah', $selectedWilayah);
        }
        if (!empty($filterTanggal)) {
            $builderList->where('DATE(fasih_modified_at)', $filterTanggal);
        }

        // --- 5. DATA PIE CHART (STATUS IDENTIFIKASI) ---
        $builderPieIden = $this->db->table('se_list_se26_ub')
            ->select("
            SUM(CASE WHEN keberadaan IS NULL 
                   OR TRIM(keberadaan) = '' 
                   OR LOWER(keberadaan) = 'null' THEN 1 ELSE 0 END) as BELUM,
            SUM(CASE WHEN keberadaan = 'ditemukan' THEN 1 ELSE 0 END) as DITEMUKAN,
            SUM(CASE WHEN keberadaan = 'tidak ditemukan' THEN 1 ELSE 0 END) as `TAK-DITEMUKAN`,
            SUM(CASE WHEN keberadaan = 'tutup' THEN 1 ELSE 0 END) as TUTUP,
            SUM(CASE WHEN keberadaan = 'ganda' THEN 1 ELSE 0 END) as GANDA
        ", false);

        if ($selectedWilayah !== '1300') {
            $builderPieIden->where('id_wilayah', $selectedWilayah);
        }
        $pieIdenQuery = $builderPieIden->get()->getRowArray();

        $pieIdenData = [
            'DITEMUKAN'  => (int)($pieIdenQuery['DITEMUKAN'] ?? 0),
            'TAK-DITEMUKAN' => (int)($pieIdenQuery['TAK-DITEMUKAN'] ?? 0),
            'GANDA'      => (int)($pieIdenQuery['GANDA'] ?? 0),
            'TUTUP'      => (int)($pieIdenQuery['TUTUP'] ?? 0),
            'BELUM'     => (int)($pieIdenQuery[' BELUM'] ?? 0),
        ];
        $data['pieIden_json'] = json_encode(array_values($pieIdenData));
        $data['cards_iden'] = [
            'total' => $pieIdenData['BELUM'] + $pieIdenData['DITEMUKAN'] + $pieIdenData['TAK-DITEMUKAN'] + $pieIdenData['TUTUP'] + $pieIdenData['GANDA'],
            'belum' => $pieIdenData['BELUM'],
            'ditemuukan' => $pieIdenData['DITEMUKAN'],
            'tak_ditemukan' => $pieIdenData['TAK-DITEMUKAN'],
            'tutup' => $pieIdenData['TUTUP'],
            'ganda' => $pieIdenData['GANDA'],
        ];

        // Hitung total baris sebelum di-limit untuk dasar perhitungan pagination
        $totalRows = $builderList->countAllResults(false);

        // Ambil data sesuai offset halaman saat ini
        $offset = ($currentPage - 1) * $perPage;
        $data['assignments'] = $builderList->orderBy('fasih_modified_at', 'DESC')->limit($perPage, $offset)->get()->getResultArray();

        // Kirim metadata pagination ke View
        $data['pager_info'] = [
            'current_page' => (int)$currentPage,
            'per_page'     => $perPage,
            'total_rows'   => $totalRows,
            'total_pages'  => ceil($totalRows / $perPage)
        ];

        return view('seMonitoring/se26_ub_view', $data);
    }

    public function uploadSEUB()
    {
        $file = $this->request->getFile('file_fasih');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau gagal diunggah.');
        }

        // Ubah pembacaan file: gunakan koma (,) sebagai delimiter standar CSV
        $handle = fopen($file->getTempName(), "r");
        $insertedOrUpdated = 0;

        $this->db->transStart();

        while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
            // 1. Skip jika baris kosong
            if (empty($row) || !isset($row[0])) {
                continue;
            }

            // 2. Skip jika baris merupakan komentar Fasih (diawali tanda #)
            if (strpos(trim($row[0]), '#') === 0) {
                continue;
            }

            // 3. Skip baris header kolom secara aman (pastikan indeks 1 eksis dulu)
            if ($row[0] === 'no' || (isset($row[1]) && $row[1] === 'id_sls')) {
                continue;
            }

            // Antisipasi jika baris data tidak lengkap/rusak di tengah file
            if (!isset($row[7]) || !isset($row[34])) {
                continue;
            }

            // Pemetaan Kolom Berdasarkan File CSV Real Fasih
            $sampleId     = trim($row[7]);  // sample_id
            $idSls        = trim($row[1]);  // id_sls
            $statusDok    = strtoupper(trim($row[2])); // status_dok
            $pencacah     = trim($row[3]);  // nama pencacah
            $namaUsaha    = !empty($row[13]) ? trim($row[13]) : trim($row[14]); // data1 / data2
            $alamatUsaha  = trim($row[15]); // data3 (Jorong/Jalan)
            $emailUsaha   = !empty($row[18]) ? trim($row[18]) : null; // data6 (Email)
            $idWilayah    = substr(trim($row[34]), 0, 4); // regionCode2 (4 digit Kabupaten/Kota)
            $modifiedStr  = trim($row[6]);  // modified timestamp

            if (empty($sampleId)) continue;

            // Standarisasi Status Dokumen agar Match dengan ENUM DB
            if (strpos($statusDok, 'SUBMIT') !== false) {
                $statusDok = 'SUBMITED';
            } elseif (strpos($statusDok, 'REJECT') !== false) {
                $statusDok = 'REJECTED';
            } elseif ($statusDok !== 'DRAFT') {
                $statusDok = 'OPEN';
            }

            // Parsing Waktu Modifikasi Fasih ke format MySQL Y-m-d H:i:s
            $cleanedDate = str_replace(['at ', 'WITA', 'WIB', 'WIT'], ['', '', '', ''], $modifiedStr);
            $fasihModifiedAt = date('Y-m-d H:i:s', strtotime(trim($cleanedDate)));

            // Cek Eksistensi Data (Upsert Strategy)
            $existing = $this->db->table('se_list_se26_ub')->where('sample_id', $sampleId)->get()->getRow();

            $saveData = [
                'id_sls'            => $idSls,
                'id_wilayah'        => $idWilayah,
                'nama_usaha'        => $namaUsaha,
                'alamat_usaha'      => $alamatUsaha,
                'email_usaha'       => $emailUsaha,
                'pencacah'          => $pencacah,
                'status'            => $statusDok,
                'fasih_modified_at' => $fasihModifiedAt,
                'uploaded_at'       => date('Y-m-d H:i:s')
            ];

            if ($existing) {
                // Update tanpa mengubah field tim_pj yang sudah diisi manual oleh admin
                $this->db->table('se_list_se26_ub')->where('sample_id', $sampleId)->update($saveData);
            } else {
                $saveData['sample_id'] = $sampleId;
                $saveData['tim_pj']    = null;
                $this->db->table('se_list_se26_ub')->insert($saveData);
            }
            $insertedOrUpdated++;
        }
        fclose($handle);

        // --- HITUNG DAN SIMPAN SNAPSHOT HISTORIS KE TABEL se_mon_ub ---
        $today = date('Y-m-d');
        $snapshot = $this->db->table('se_list_se26_ub')
            ->select('id_wilayah, status, COUNT(*) as jml')
            ->groupBy('id_wilayah, status')
            ->get()->getResultArray();

        foreach ($snapshot as $snap) {
            $this->db->table('se_mon_ub')->replace([
                'id_wilayah' => $snap['id_wilayah'],
                'tanggal'    => $today,
                'status'     => $snap['status'],
                'jumlah'     => $snap['jml']
            ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses transaksi database.');
        }

        return redirect()->to(base_url('se/monitoring-ub'))->with('success', "Berhasil memproses file. {$insertedOrUpdated} baris data Usaha Besar diperbarui.");
    }

    public function updatePJSEUB()
    {
        $sampleId = $this->request->getPost('sample_id');
        $namaPj   = $this->request->getPost('tim_pj');

        if (!empty($sampleId)) {
            $this->db->table('se_list_se26_ub')
                ->where('sample_id', $sampleId)
                ->update(['tim_pj' => empty(trim($namaPj)) ? null : trim($namaPj)]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Tim Penanggung Jawab berhasil diperbarui.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengubah data.'], 400);
    }

    public function updateKeberadaan()
    {
        $sampleId   = $this->request->getPost('sample_id');
        $keberadaan = $this->request->getPost('keberadaan');

        if (empty($sampleId)) {
            return $this->response->setJSON([
                'status'  => 400,
                'message' => 'Sample ID wajib disertakan.'
            ])->setStatusCode(400);
        }

        $valueToUpdate = ($keberadaan === '') ? null : trim($keberadaan);

        $db = \Config\Database::connect();

        try {
            $updated = $db->table('se_list_se26_ub')
                ->where('sample_id', $sampleId)
                ->update([
                    'keberadaan' => $valueToUpdate,
                    'uploaded_at' => date('Y-m-d H:i:s')
                ]);

            if ($updated) {
                return $this->response->setJSON([
                    'status'  => 200,
                    'message' => 'Status keberadaan sampel berhasil diperbarui.'
                ])->setStatusCode(200);
            } else {
                return $this->response->setJSON([
                    'status'  => 404,
                    'message' => 'Data sampel tidak ditemukan atau tidak ada perubahan data.'
                ])->setStatusCode(404);
            }
        } catch (\Throwable $th) {
            return $this->response->setJSON([
                'status'  => 500,
                'message' => 'Gagal memperbarui database: ' . $th->getMessage()
            ])->setStatusCode(500);
        }
    }
    // +++++++++++++++++++++++++++++++++++++++
    // Fungsi untuk monitoring Progres
    // +++++++++++++++++++++++++++++++++++++++
    public function dashboardProgres()
    {
        // Parameter Filter Utama
        $selectedKab = $this->request->getGet('kabupaten') ?? 'SUMBAR';
        $offsetHari  = (int)($this->request->getGet('offset_hari') ?? 0);

        // Ambil tanggal terbaru
        $maxTanggalRow = $this->db->table('se_progres_subsls')->selectMax('tanggal')->get()->getRow();
        $targetTanggal = $maxTanggalRow->tanggal ?? date('Y-m-d');

        // 2. QUERY KARTU RINGKASAN (TOTAL & PERSENTASE)
        $builderCards = $this->db->table('se_progres_subsls')->where('tanggal', $targetTanggal);
        if ($selectedKab !== 'SUMBAR') {
            $builderCards->like('id_subsls', $selectedKab, 'after');
        }
        $cardsData = $builderCards->select('
            SUM(total) as total,
            SUM(open) as open,
            SUM(draft + rejected_by_pengawas) as draft,
            SUM(approved_by_pengawas) as approved,
            SUM(submitted_by_pencacah + submitted_respondent + revoked_by_pengawas + rejected_by_admin_kabupaten) as submitted
        ')->get()->getRowArray();

        // 3. QUERY PROGRESS BAR (1 Level Wilayah di Bawahnya - Detail Status)
        $lenGroup = ($selectedKab === 'SUMBAR') ? 4 : 7;
        $builderProgress = $this->db->table('se_progres_subsls')->where('tanggal', $targetTanggal);
        if ($selectedKab !== 'SUMBAR') {
            $builderProgress->like('id_subsls', $selectedKab, 'after');
        }
        $progressRows = $builderProgress->select("
        LEFT(id_subsls, {$lenGroup}) as kode_wilayah,
        SUM(total) as total,
        SUM(open) as open,
        SUM(draft + rejected_by_pengawas) as draft,
        SUM(approved_by_pengawas) as approved,
        SUM(COALESCE(submitted_by_pencacah, 0) + COALESCE(submitted_respondent, 0) + COALESCE(revoked_by_pengawas, 0) + COALESCE(rejected_by_admin_kabupaten, 0)) as submitted")
            ->groupBy("LEFT(id_subsls, {$lenGroup})")
            ->get()
            ->getResultArray();

        // 4. QUERY HISTORIS (Maksimal 7 Hari ke Belakang dengan Navigasi Offset)
        // Menghitung ketersediaan tanggal unik di database untuk kontrol tombol disable
        $allDates = $this->db->table('se_progres_subsls')->select('tanggal')->distinct()->orderBy('tanggal', 'DESC')->get()->getResultArray();
        $totalHariTersedia = count($allDates);

        // Ambil potongan 7 hari sesuai offset berjalan
        $startChunk = $offsetHari * 7;
        $activeDatesChunk = array_slice(array_column($allDates, 'tanggal'), $startChunk, 7);
        $activeDatesChunk = array_reverse($activeDatesChunk); // Urutkan dari tanggal terlama ke terbaru untuk grafik

        $historicalData = [];
        if (!empty($activeDatesChunk)) {
            $builderHist = $this->db->table('se_progres_subsls')->whereIn('tanggal', $activeDatesChunk);
            if ($selectedKab !== 'SUMBAR') {
                $builderHist->like('id_subsls', $selectedKab, 'after');
            }
            $histRows = $builderHist->select('
                tanggal,
                SUM(open) as open,
                SUM(draft + rejected_by_pengawas) as draft,
                SUM(approved_by_pengawas) as approved,
                SUM(submitted_by_pencacah + submitted_respondent + revoked_by_pengawas + rejected_by_admin_kabupaten) as submitted
            ')->groupBy('tanggal')->orderBy('tanggal', 'ASC')->get()->getResultArray();

            // Format ulang agar siap dikonsumsi Chart JSON
            foreach ($histRows as $r) {
                $historicalData['categories'][] = date('d M', strtotime($r['tanggal']));
                $historicalData['open'][]       = (int)$r['open'];
                $historicalData['draft'][]      = (int)$r['draft'];
                $historicalData['submitted'][]  = (int)$r['submitted'];
                $historicalData['approved'][]   = (int)$r['approved'];
            }
        }

        // 5. QUERY PAGINATION UNTUK TABEL SUB-SLS (Hanya ambil 50 data per halaman)
        $pager = \Config\Services::pager();
        $page  = (int)($this->request->getGet('page') ?? 1);
        $perPage = 50;

        $builderTable = $this->db->table('se_progres_subsls')->where('tanggal', $targetTanggal);
        if ($selectedKab !== 'SUMBAR') {
            $builderTable->like('id_subsls', $selectedKab, 'after');
        }

        // Kloning builder untuk menghitung total baris sebelum di-limit
        $totalRowsForPager = $builderTable->countAllResults(false);

        $tableData = $builderTable->select('
            id_subsls, total, open, draft, approved_by_pengawas,
            (submitted_by_pencacah + submitted_respondent) as submitted,
            rejected_by_pengawas, revoked_by_pengawas
        ')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        // Buat HTML pagination khas Tabler/Bootstrap
        $pagerLinks = $pager->makeLinks($page, $perPage, $totalRowsForPager, 'default_full');

        // ==========================================
        // ADDON: QUERY TOP & BOTTOM 5 PETUGAS (PPL)
        // ==========================================
        $baseChampions = $this->db->table('se_progres_subsls' . ' s')
            ->join('wilayah_tugas w', 's.id_subsls = w.id_wilayah', 'inner')
            ->join('users u', 'w.id_ppl = u.id', 'inner') // Sesuaikan nama tabel user Anda
            ->where('s.tanggal', $targetTanggal)
            ->where('w.id_kegiatan', $this->idKegiatanPetugas);

        if ($selectedKab !== 'SUMBAR') {
            $baseChampions->like('s.id_subsls', $selectedKab, 'after');
        }

        // Kloning builder untuk Top 5 (Urutan Terbanyak Approved + Submitted)
        $builderTop = clone $baseChampions;
        $topPetugas = $builderTop->select('u.name as nama_petugas, u.wilayah_kerja as wilayah,
            SUM(s.approved_by_pengawas) as approved,
            SUM(s.submitted_by_pencacah + s.submitted_respondent) as submitted')
            ->groupBy('w.id_ppl')
            ->orderBy('SUM(s.approved_by_pengawas)', 'DESC')
            ->orderBy('SUM(s.submitted_by_pencacah + s.submitted_respondent)', 'DESC')
            ->limit(5)->get()->getResultArray();

        // Kloning builder untuk Bottom 5 (Urutan Terendah Approved + Submitted)
        $builderBottom = clone $baseChampions;
        $bottomPetugas = $builderBottom->select('u.name as nama_petugas, u.wilayah_kerja as wilayah, 
            SUM(s.approved_by_pengawas) as approved,
            SUM(s.submitted_by_pencacah + s.submitted_respondent) as submitted')
            ->groupBy('w.id_ppl')
            ->orderBy('SUM(s.approved_by_pengawas)', 'ASC')
            ->orderBy('SUM(s.submitted_by_pencacah + s.submitted_respondent)', 'ASC')
            ->limit(5)->get()->getResultArray();

        // ==========================================
        // ADDON: ROOT KOSEKA UNTUK AKORDION AWAL
        // ==========================================
        if ($selectedKab !== "SUMBAR") {
            $builderKoseka = $this->db->table('se_progres_subsls' . ' s')
                ->join('wilayah_tugas w', 's.id_subsls = w.id_wilayah', 'left')
                ->join('users u', 'w.id_koseka = u.id', 'left')
                ->where('s.tanggal', $targetTanggal);

            // Filter kegiatan hanya berlaku jika baris wilayah_tugas ditemukan
            $builderKoseka->groupStart()
                ->where('w.id_kegiatan', $this->idKegiatanPetugas)
                ->orWhere('w.id_kegiatan IS NULL')
                ->groupEnd();

            if ($selectedKab !== 'SUMBAR') {
                $builderKoseka->like('s.id_subsls', $selectedKab, 'after');
            }
            $kosekaData = $builderKoseka->select('
                IFNULL(w.id_koseka, 0) as id_koseka, 
                IFNULL(u.name, "Belum Ada Koseka") as nama_koseka,
                SUM(s.total) as total, SUM(s.open) as open, SUM(s.draft) as draft,
                SUM(s.approved_by_pengawas) as approved,
                SUM(s.submitted_by_pencacah + s.submitted_respondent) as submitted
            ')
                ->groupBy('IFNULL(w.id_koseka, 0)')->get()->getResultArray();
        } else {
            $kosekaData = null;
        }

        return view('seMonitoring/monitoring_progres', [
            'title' => "monitoring progres",
            'selectedKab'   => $selectedKab,
            'offsetHari'    => $offsetHari,
            'cards'         => $cardsData,
            'progressRows'  => $progressRows,
            'chartData'     => json_encode($historicalData),
            'targetTanggal' => $targetTanggal,
            'totalHari'      => $totalHariTersedia,

            // Data Tambahan untuk Tabel Baru
            'tableData'     => $tableData,
            'pagerLinks'    => $pagerLinks,

            'topPetugas'    => $topPetugas,
            'bottomPetugas' => $bottomPetugas,
            'kosekaData'    => $kosekaData,
        ]);
    }

    public function getTabelProgres()
    {
        $selectedKab   = $this->request->getGet('kabupaten') ?? 'SUMBAR';
        $page          = (int)($this->request->getGet('page') ?? 1);
        $perPage       = 50;

        $maxTanggalRow = $this->db->table('se_progres_subsls')->selectMax('tanggal')->get()->getRow();
        $targetTanggal = $maxTanggalRow->tanggal ?? date('Y-m-d');

        $builder = $this->db->table('se_progres_subsls')->where('tanggal', $targetTanggal);
        if ($selectedKab !== 'SUMBAR') {
            $builder->like('id_subsls', $selectedKab, 'after');
        }

        // Hitung total data untuk kalkulasi jumlah halaman di JS
        $totalRows = $builder->countAllResults(false);
        $totalPages = ceil($totalRows / $perPage);

        // Ambil segmen data saat ini
        $tableData = $builder->select('
            id_subsls, total, open, draft, rejected_by_pengawas, revoked_by_pengawas,
            (submitted_by_pencacah + submitted_respondent) as submitted,
            approved_by_pengawas as approved
        ')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'data'         => $tableData,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'total_rows'   => $totalRows
        ]);
    }

    public function downloadExcelProgres()
    {
        $selectedKab = $this->request->getGet('kabupaten') ?? 'SUMBAR';

        $maxTanggalRow = $this->db->table('se_progres_subsls')->selectMax('tanggal')->get()->getRow();
        $targetTanggal = $maxTanggalRow->tanggal ?? date('Y-m-d');

        $builder = $this->db->table('se_progres_subsls')->where('tanggal', $targetTanggal);
        if ($selectedKab !== 'SUMBAR') {
            $builder->like('id_subsls', $selectedKab, 'after');
        }

        $query = $builder->select('
            id_subsls, total, open, draft, 
            (submitted_by_pencacah + submitted_respondent) as submitted,
            approved_by_pengawas, rejected_by_pengawas, revoked_by_pengawas
        ')->get();

        $filename = "Progres_SE2026_" . $selectedKab . "_" . $targetTanggal . ".csv";

        // Set header browser agar langsung mendownload berkas Excel/CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Membuka output stream untuk menulis data baris per baris (Sangat Hemat RAM!)
        $output = fopen('php://output', 'w');

        // Atur bom agar karakter dibaca rapi di Excel, gunakan separator titik koma (;)
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Baris Header Excel
        fputcsv($output, ['KODE SUBSLS', 'TOTAL', 'OPEN', 'DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'REVOKED'], ';');

        // Masukkan semua baris data (semua 17.675 baris akan tertulis di sini)
        foreach ($query->getResultArray() as $row) {
            fputcsv($output, [
                $row['id_subsls'] . ' ', // Tambah spasi agar kode SLS panjang tidak berubah jadi format scientific (E+) di Excel
                $row['total'],
                $row['open'],
                $row['draft'],
                $row['submitted'],
                $row['approved_by_pengawas'],
                $row['rejected_by_pengawas'],
                $row['revoked_by_pengawas']
            ], ';');
        }

        fclose($output);
        exit;
    }

    // ==========================================
    // API ENDPOINT 1: LAZY LOAD PML BY KOSEKA
    // ==========================================
    public function getPmlByKoseka()
    {
        $idKoseka = $this->request->getGet('id_koseka');
        $kdKab = $this->request->getGet('kd_kab');

        $maxTanggalRow = $this->db->table('se_progres_subsls')->selectMax('tanggal')->get()->getRow();
        $targetTanggal = $maxTanggalRow->tanggal ?? date('Y-m-d');

        $builder = $this->db->table('se_progres_subsls' . ' s')
            ->join('wilayah_tugas w', 's.id_subsls = w.id_wilayah', 'left')
            ->join('users u', 'w.id_pml = u.id', 'left')
            ->where('s.tanggal', $targetTanggal)
            ->where('IFNULL(w.id_koseka, 0)', $idKoseka);

        // KUNCI 1: Batasi hanya untuk kabupaten yang sedang dibuka agar tidak bocor ke kab lain
        if ($kdKab) {
            $builder->like('s.id_subsls', $kdKab, 'after');
        }

        // KUNCI 2: Amankan filter kegiatan agar SLS yang belum di-assign (NULL) tidak hilang
        $builder->groupStart()
            ->where('w.id_kegiatan', $this->idKegiatanPetugas)
            ->orWhere('w.id_kegiatan IS NULL')
            ->groupEnd();

        $pmlRows = $builder->select('
            IFNULL(w.id_pml, 0) as id_pml, 
            IFNULL(u.name, "Belum Ada PML") as nama_pml,
            SUM(s.total) as total, SUM(s.open) as open, SUM(s.draft) as draft,
            SUM(s.approved_by_pengawas) as approved,
            SUM(s.submitted_by_pencacah + s.submitted_respondent) as submitted')
            ->groupBy('IFNULL(w.id_pml, 0)')->get()->getResultArray();

        return $this->response->setJSON($pmlRows);
    }

    // ==========================================
    // API ENDPOINT 2: LAZY LOAD PPL BY PML
    // ==========================================
    public function getPplByPml()
    {
        $idPml = $this->request->getGet('id_pml');
        $kdKab = $this->request->getGet('kd_kab');

        $maxTanggalRow = $this->db->table('se_progres_subsls')->selectMax('tanggal')->get()->getRow();
        $targetTanggal = $maxTanggalRow->tanggal ?? date('Y-m-d');

        $builder = $this->db->table('se_progres_subsls' . ' s')
            ->join('wilayah_tugas w', 's.id_subsls = w.id_wilayah', 'left')
            ->join('users u', 'w.id_ppl = u.id', 'left')
            ->where('s.tanggal', $targetTanggal)
            // HAPUS ->where('w.id_pml', $idPml) karena bikin mentok saat NULL
            ->where('IFNULL(w.id_pml, 0)', $idPml);

        // KUNCI 1: Batasi wilayah kabupaten
        if ($kdKab) {
            $builder->like('s.id_subsls', $kdKab, 'after');
        }

        // KUNCI 2: Amankan filter kegiatan
        $builder->groupStart()
            ->where('w.id_kegiatan', $this->idKegiatanPetugas)
            ->orWhere('w.id_kegiatan IS NULL')
            ->groupEnd();

        $pplRows = $builder->select('
            IFNULL(w.id_ppl, 0) as id_ppl, 
            IFNULL(u.name, "Belum Ada PPL") as nama_ppl,
            SUM(s.total) as total, SUM(s.open) as open, SUM(s.draft) as draft,
            SUM(s.approved_by_pengawas) as approved,
            SUM(s.submitted_by_pencacah + s.submitted_respondent) as submitted')
            ->groupBy('IFNULL(w.id_ppl, 0)')->get()->getResultArray();

        return $this->response->setJSON($pplRows);
    }
}
