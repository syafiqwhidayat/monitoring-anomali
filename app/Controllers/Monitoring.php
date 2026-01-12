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
        $data = [
            "title" => "Monitoring Semua Anomali",
            "dataCharJmlAnom" => [
                'labels' => json_encode(['AN01', 'AN02', 'AN03', 'AN04']),
                'datesets' => [
                    [
                        'label' => json_encode(['Sudah Konfirmasi']),
                        'nilai' => json_encode([15, 84, 15, 34]),
                    ],
                    [
                        'label' => json_encode(['Belum Konfirmasi']),
                        'nilai' => json_encode([10, 45, 30, 25]),
                    ]
                ],
            ],
            "dataHead" => [
                'total seluruh' => 245,
                'total public' => 135,
                'total non public' => 97
            ],
            "dataProses" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([89, 31]),
            ],
            "dataProsesPublic" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([74, 21]),
            ],
            "dataProsesNonPublic" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([84, 12]),
            ],
            "dataProsesFlag1" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([78, 35]),
            ],
            "dataProsesFlag2" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([23, 43]),
            ],
            "dataProsesFlag3" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([38, 48]),
            ],
            "dataTimeline" => [
                'labels' => json_encode(['1 Jan 2025', '2 Jan 2025', '3 Jan 2025', '4 Jan 2025']),
                'datasets' => [
                    [
                        'label' => json_encode(['Total Anomali']),
                        'nilai' => json_encode([45, 45, 85, 85])
                    ],
                    [
                        'label' => json_encode(['Progres Anomali']),
                        'nilai' => json_encode([12, 25, 45, 65])
                    ],
                ]
            ]
        ];
        return view('monitoring/monitoringAll', $data);
    }

    public function view()
    {
        $data = [
            "title" => "Monitoring Semua Anomali",
            "kode_anomali" => "AN01",
            "is_public" => true,
            "dataCharJmlAnom" => [
                'labels' => json_encode(['AN01', 'AN02', 'AN03', 'AN04']),
                'datesets' => [
                    [
                        'label' => json_encode(['Sudah Konfirmasi']),
                        'nilai' => json_encode([15, 84, 15, 34]),
                    ],
                    [
                        'label' => json_encode(['Belum Konfirmasi']),
                        'nilai' => json_encode([10, 45, 30, 25]),
                    ]
                ],
            ],
            "dataHead" => [
                'total seluruh' => 245,
                'total public' => 135,
                'total non public' => 97
            ],
            "dataProses" => [
                'label' => json_encode(['Selesai', 'Belum']),
                'nilai' => json_encode([89, 31]),
            ],
            "dataTimeline" => [
                'labels' => json_encode(['1 Jan 2025', '2 Jan 2025', '3 Jan 2025', '4 Jan 2025']),
                'datasets' => [
                    [
                        'label' => json_encode(['Total Anomali']),
                        'nilai' => json_encode([45, 45, 85, 85])
                    ],
                    [
                        'label' => json_encode(['Progres Anomali']),
                        'nilai' => json_encode([12, 25, 45, 65])
                    ],
                ]
            ],
            "dataWordCloud" => json_encode([
                ["error", 40],
                ["anomali", 30],
                ["wilayah", 30],
                ["konfirmasi", 20],
                ["data", 20],
                ["jawa", 20],
                ["barat", 20],
                ["petugas", 20],
                ["validasi", 20],
                ["duplikasi", 20]
            ])
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
}
