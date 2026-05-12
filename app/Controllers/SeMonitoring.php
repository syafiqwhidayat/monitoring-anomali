<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\SeMonitoringModel;
use App\Models\SeLogModel;
use Config\Services;
use Faker\Provider\Lorem;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SeMonitoring extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;
    protected $seMonitoringModel;
    protected $seLogModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
        $this->seMonitoringModel = new SeMonitoringModel();
        $this->seLogModel = new SeLogModel();
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

        $datalog = $this->seLogModel->select('se_upload_log.*,idn.secret AS email')
            ->join('auth_identities idn', 'id_user = idn.user_id')
            ->where('id_kegiatan', session()->get('aktif_kegiatan'))->orderBy('created_at', 'DESC');

        if (! auth()->user()->inGroup('superadmin')) {
            $datalog->where('id_user', auth()->user()->id);
        }

        $data['logs'] = $datalog->findAll();
        // dd($data['logs']);

        return view('seMonitoring/logUpload', $data);
    }

    public function downloadTemplate()
    {
        return $this->response->download(FCPATH . 'assets\templates\template_monitoring.xlsx', null);
    }

    public function store()
    {
        $file = $this->request->getFile('file_monitoring');

        // cek validitas
        if (!$file->isValid()) return redirect()->back()->with('message_errors', 'File tidak valid');

        // membaca file
        $spreadsheet = IOFactory::load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $logData['id_kegiatan'] = session()->get('aktif_kegiatan');
        $logData['nama_file'] = $file->getName();
        $logData['total_baris'] = count($sheetData) - 1;
        $logData['id_user'] = auth()->user()->id;
        $idLog = $this->seLogModel->insert($logData);


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
}
