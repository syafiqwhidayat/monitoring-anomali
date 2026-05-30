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
                'label' => json_encode(['Ngibar/Mandiri', 'Submit Petugas', 'Open']),
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
        return $this->response->download(FCPATH . 'assets/templates/template_monitoring.xlsx', null);
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
        $hasil = $this->anomaliModel->jumlahByTanggal($id_kat);
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

    public function monitoringUB()
    {
        $selectedWilayah = $this->request->getGet('wilayah') ?? '1300'; // 1300 = Seluruh Prov Sumbar
        $filterTanggal    = $this->request->getGet('tanggal') ?? '';

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

        // --- 1. DATA PIE CHART (STATUS TERBARU) ---
        $builderPie = $this->db->table('se_list_se26_ub');
        if ($selectedWilayah !== '1300') {
            $builderPie->where('id_wilayah', $selectedWilayah);
        }
        $pieQuery = $builderPie->select('status, COUNT(*) as jml')->groupBy('status')->get()->getResultArray();

        $pieData = ['DRAFT' => 0, 'SUBMITED' => 0, 'REJECTED' => 0, 'OPEN' => 0];
        foreach ($pieQuery as $p) {
            $pieData[strtoupper($p['status'])] = (int)$p['jml'];
        }
        $data['pie_json'] = json_encode(array_values($pieData));

        // --- 2. DATA BAR CHART (WILAYAH ATAU TIM PJ) ---
        $barLabels = [];
        $barSeries = ['DRAFT' => [], 'SUBMITED' => [], 'REJECTED' => [], 'OPEN' => []];

        if ($selectedWilayah === '1300') {
            // Tampilkan Bar Chart per Kabupaten
            foreach ($data['kab_kota'] as $kode => $nama) {
                $barLabels[] = $nama;
                foreach (['DRAFT', 'SUBMITED', 'REJECTED', 'OPEN'] as $st) {
                    $barSeries[$st][] = $this->db->table('se_list_se26_ub')
                        ->where('id_wilayah', $kode)->where('status', $st)->countAllResults();
                }
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
                foreach (['DRAFT', 'SUBMITED', 'REJECTED', 'OPEN'] as $st) {
                    $qCount = $this->db->table('se_list_se26_ub')
                        ->where('id_wilayah', $selectedWilayah)
                        ->where('status', $st);
                    if ($pjRow['pj'] === "Belum Ada PJ") {
                        $qCount->where('tim_pj IS NULL');
                    } else {
                        $qCount->where('tim_pj', $pjRow['pj']);
                    }
                    $barSeries[$st][] = $qCount->countAllResults();
                }
            }
        }
        $data['bar_json'] = json_encode([
            'labels' => $barLabels,
            'datasets' => [
                ['label' => 'REJECTED', 'data' => $barSeries['REJECTED'], 'backgroundColor' => '#EE8911'],
                ['label' => 'SUBMITED', 'data' => $barSeries['SUBMITED'], 'backgroundColor' => '#94C11F'],
                ['label' => 'DRAFT', 'data' => $barSeries['DRAFT'], 'backgroundColor' => '#0369A1'],
                ['label' => 'OPEN', 'data' => $barSeries['OPEN'], 'backgroundColor' => '#B3B3B3'],
            ]
        ]);

        // --- 3. DATA LINE CHART HISTORIS (se_mon_ub) ---
        $builderLine = $this->db->table('se_mon_ub')->select('tanggal')->groupBy('tanggal')->orderBy('tanggal', 'ASC');
        if ($selectedWilayah !== '1300') {
            $builderLine->where('id_wilayah', $selectedWilayah);
        }
        $tglQuery = $builderLine->get()->getResultArray();

        $lineLabels = [];
        $lineSeries = ['DRAFT' => [], 'SUBMITED' => [], 'REJECTED' => [], 'OPEN' => []];

        foreach ($tglQuery as $tRow) {
            $lineLabels[] = date('d M Y', strtotime($tRow['tanggal']));
            foreach (['DRAFT', 'SUBMITED', 'REJECTED', 'OPEN'] as $st) {
                $sumQuery = $this->db->table('se_mon_ub')
                    ->selectSum('jumlah')
                    ->where('tanggal', $tRow['tanggal'])
                    ->where('status', $st);
                if ($selectedWilayah !== '1300') {
                    $sumQuery->where('id_wilayah', $selectedWilayah);
                }
                $res = $sumQuery->get()->getRowArray();
                $lineSeries[$st][] = (int)($res['jumlah'] ?? 0);
            }
        }
        $data['line_json'] = json_encode([
            'labels' => $lineLabels,
            'draft' => $lineSeries['DRAFT'],
            'submited' => $lineSeries['SUBMITED'],
            'rejected' => $lineSeries['REJECTED'],
            'open' => $lineSeries['OPEN']
        ]);

        // --- 4. LIST TABEL DATA DENGAN FILTER ---
        $builderList = $this->db->table('se_list_se26_ub');
        if ($selectedWilayah !== '1300') {
            $builderList->where('id_wilayah', $selectedWilayah);
        }
        if (!empty($filterTanggal)) {
            $builderList->where('DATE(fasih_modified_at)', $filterTanggal);
        }
        $data['assignments'] = $builderList->orderBy('fasih_modified_at', 'DESC')->get()->getResultArray();
        return view('SeMonitoring/se26_ub_view', $data);
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
}
