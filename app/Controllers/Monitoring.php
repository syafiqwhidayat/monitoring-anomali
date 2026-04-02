<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use Config\Services;
use Faker\Provider\Lorem;

class Monitoring extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
    }

    public function index()
    {
        $dataHead = $this->anomaliModel->jumlahKonfirmasiByPublik();
        $data = [
            "title" => "Monitoring Semua Anomali",
            "dataCharJmlAnom" => $this->dataChartJmlAnom(),
            "dataHead" => [
                'total seluruh' => $dataHead[0]['jumlah_total'],
                'total public' => $dataHead[0]['jumlah_public'],
                'total non public' => $dataHead[0]['jumlah_non_public']
            ],
            "dataProses" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses()),
            ],
            "dataProsesPublic" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses('public')),
            ],
            "dataProsesNonPublic" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses('non_public')),
            ],
            "dataProsesFlag1" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses('flag1')),
            ],
            "dataProsesFlag2" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses('flag2')),
            ],
            "dataProsesFlag3" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses('flag3')),
            ],
            "dataTimeline" => $this->dataTimeline(),
            "dataTop5" => $this->anomaliModel->getTop5()
        ];
        return view('monitoring/monitoringAll', $data);
    }

    public function view($id_kat)
    {
        $general = $this->katAnomaliModel->getDataUmum($id_kat)[0];
        $dataHead = $this->anomaliModel->jumlahKonfirmasiByPublik($id_kat);
        $data = [
            "title" => "Monitoring Anomali " . $general['kode_anomali'],
            "kode_anomali" => $general['kode_anomali'],
            "is_public" => $general['is_show'],
            "flag" => $general['flag'],
            "dataCharJmlAnom" => $this->dataChartJmlAnom($id_kat),
            "dataHead" => [
                'total seluruh' => $dataHead[0]['jumlah_total'],
            ],
            "dataProses" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode($this->dataChartProses('proses', $id_kat)),
            ],
            "dataTimeline" => $this->dataTimeline($id_kat),
            "dataWordCloud" => json_encode($this->dataWordCloud($id_kat)),
            "dataTop5" => $this->anomaliModel->getTop5($id_kat)
        ];
        return view('monitoring/monitoringSelc', $data);
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



        // Jika BERHASIL, kembalikan JSON sukses

        // dd("berhasil validasi");
        // return $this->response->setJSON([
        //     'status' => 'success',
        //     'message' => 'Konfirmasi berhasil disimpan.',
        //     'id_updated' => $id
        // ]);

        // dd($konfirmasi);
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
        if ($id_kat) {
            $dataModel = $this->anomaliModel->jumlahKonfirmasiByWiayah($id_kat);
            $judulBaris = array_column($dataModel, 'id_kec');
        } else {
            $dataModel = $this->anomaliModel->jumlahKonfirmasiByAnoamli();
            $judulBaris = array_column($dataModel, 'kode_anomali');
        };
        $jumlah_terisi = array_column($dataModel, 'jumlah_terisi');
        $jumlah_total = array_column($dataModel, 'jumlah_total');
        $selisih = array_map(fn($a, $b) => $a - $b, $jumlah_total, $jumlah_terisi);
        $data =  [
            'labels' => json_encode($judulBaris),
            'datesets' => [
                [
                    'label' => json_encode(['Sudah Konfirmasi']),
                    'nilai' => json_encode($jumlah_terisi),
                ],
                [
                    'label' => json_encode(['Belum Konfirmasi']),
                    'nilai' => json_encode($selisih),
                ]
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
        $dataCreated = array_column($hasil['dataCreated'], 'jumlah', 'tanggal');
        $dataUpdated = array_column($hasil['dataUpdated'], 'jumlah', 'tanggal');
        $tanggalCreated = array_column($hasil['dataCreated'], 'tanggal');
        $tanggalUpdated = array_column($hasil['dataUpdated'], 'tanggal');
        $tanggalGabung = array_unique(array_merge($tanggalCreated, $tanggalUpdated));
        sort($tanggalGabung);

        $runningTotalCreated = 0;
        $runningTotalUpdated = 0;
        $dataChartCreated = array_map(function ($tgl) use ($dataCreated, &$runningTotalCreated) {
            // Ambil jumlah hari ini (jika tidak ada, anggap 0)
            $jumlahHariIni = (int) ($dataCreated[$tgl] ?? 0);
            // Tambahkan ke total akumulasi
            $runningTotalCreated += $jumlahHariIni;

            return $runningTotalCreated;
            // return (int) ($dataCreated[$tgl] ?? 0);
        }, $tanggalGabung);
        $dataChartUpdated = array_map(function ($tgl) use ($dataUpdated, &$runningTotalUpdated) {
            // Ambil jumlah hari ini (jika tidak ada, anggap 0)
            $jumlahHariIni = (int) ($dataUpdated[$tgl] ?? 0);

            // Tambahkan ke total akumulasi
            $runningTotalUpdated += $jumlahHariIni;

            return $runningTotalUpdated;
            // return (int) ($dataUpdated[$tgl] ?? 0);
        }, $tanggalGabung);

        // ubah nilai null jadi yg terkecil
        $tglTerkecil = min(array_filter($tanggalGabung), 0);
        $tglBaru = date('Y-m-d', strtotime($tglTerkecil . " -1 day"));
        $tanggalGabung = array_map(function ($val) use ($tglBaru) {
            return $val ?? $tglBaru;
        }, $tanggalGabung);



        $data = [
            'labels' => json_encode($tanggalGabung),
            'datasets' => [
                [
                    'label' => json_encode(['Total Anomali']),
                    'nilai' => json_encode($dataChartCreated)
                ],
                [
                    'label' => json_encode(['Progres Anomali']),
                    'nilai' => json_encode($dataChartUpdated)
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
