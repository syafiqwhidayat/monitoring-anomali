<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use Config\Services;
use Faker\Provider\Lorem;

class Anom extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
    }

    public function list()
    {
        $data = [
            'title' => 'List Anomali',
            'listAnom' => [
                [
                    'nmKec' => 'Sungai Rumbai',
                    'id' => '1311010',
                    'kd' => '010',
                    'jmlAnom' => 63
                ],
                [
                    'nmKec' => 'Koto Besar',
                    'id' => '1311011',
                    'kd' => '011',
                    'jmlAnom' => 12
                ],
                [
                    'nmKec' => 'Asam Jujuhan',
                    'id' => '1311012',
                    'kd' => '012',
                    'jmlAnom' => 7
                ],
                [
                    'nmKec' => 'Koto Baru',
                    'id' => '1311020',
                    'kd' => '020',
                    'jmlAnom' => 6
                ],
                [
                    'nmKec' => 'Koto Salak',
                    'id' => '1311021',
                    'kd' => '021',
                    'jmlAnom' => 18
                ],
                [
                    'nmKec' => 'Tiumang',
                    'id' => '1311022',
                    'kd' => '022',
                    'jmlAnom' => 47
                ],
                [
                    'nmKec' => 'Padang Laweh',
                    'id' => '1311023',
                    'kd' => '023',
                    'jmlAnom' => 35
                ],
                [
                    'nmKec' => 'Sitiung',
                    'id' => '1311030',
                    'kd' => '030',
                    'jmlAnom' => 14
                ],
                [
                    'nmKec' => 'Timpeh',
                    'id' => '1311031',
                    'kd' => '031',
                    'jmlAnom' => 32
                ],
                [
                    'nmKec' => 'Pulau Punjung',
                    'id' => '1311040',
                    'kd' => '040',
                    'jmlAnom' => 74
                ],
                [
                    'nmKec' => 'IX Koto',
                    'id' => '1311041',
                    'kd' => '041',
                    'jmlAnom' => 45
                ],
            ]
        ];

        $dat = $this->anomaliModel->getAnomaliByWilayah('1311');
        $data['listAnom'] = $dat;

        return view('anomali/listAnomali', $data);
    }

    public function listWilAnom($idKec)
    {
        $id = substr($idKec, 8);
        $data = [
            'title' => 'List Anomali',
            'listAnom' => null,
            'id' => null,
            'jenis' => null

        ];

        $dataDesa = [
            [
                'nm' => 'Desa Sungai Rumbai',
                'id' => '1311010001',
                'kd' => '001',
                'jmlAnom' => 12
            ],
            [
                'nm' => 'Desa Koto Besar',
                'id' => '1311011002',
                'kd' => '002',
                'jmlAnom' => 45
            ],
            [
                'nm' => 'Desa Asam Jujuhan',
                'id' => '1311012003',
                'kd' => '003',
                'jmlAnom' => 13
            ],
            [
                'nm' => 'Desa Koto Baru',
                'id' => '1311020004',
                'kd' => '004',
                'jmlAnom' => 22
            ],
            [
                'nm' => 'Desa Koto Salak',
                'id' => '1311021005',
                'kd' => '005',
                'jmlAnom' => 21
            ],
            [
                'nm' => 'Desa Tiumang',
                'id' => '1311022006',
                'kd' => '006',
                'jmlAnom' => 14
            ],
            [
                'nm' => 'Desa Padang Laweh',
                'id' => '1311023007',
                'kd' => '007',
                'jmlAnom' => 23
            ],
            [
                'nm' => 'Desa Sitiung',
                'id' => '1311030008',
                'kd' => '008',
                'jmlAnom' => 27
            ],
            [
                'nm' => 'Desa Timpeh',
                'id' => '1311031009',
                'kd' => '009',
                'jmlAnom' => 22
            ],
            [
                'nm' => 'Desa Pulau Punjung',
                'id' => '13110400010',
                'kd' => '010',
                'jmlAnom' => 17
            ],
            [
                'nm' => 'Desa IX Koto',
                'id' => '13110410011',
                'kd' => '011',
                'jmlAnom' => 34
            ],
        ];
        $dataSLS = [
            [
                'nm' => 'Jorong Sungai Rumbai',
                'id' => '1311010001000100',
                'kd' => '000100',
                'jmlAnom' => 12
            ],
            [
                'nm' => 'Jorong Koto Besar',
                'id' => '1311011001000200',
                'kd' => '000200',
                'jmlAnom' => 14
            ],
            [
                'nm' => 'Jorong Asam Jujuhan',
                'id' => '1311012001000300',
                'kd' => '000300',
                'jmlAnom' => 20
            ],
            [
                'nm' => 'Jorong Koto Baru',
                'id' => '1311020001000400',
                'kd' => '000400',
                'jmlAnom' => 24
            ],
            [
                'nm' => 'Jorong Koto Salak',
                'id' => '1311021001000500',
                'kd' => '000500',
                'jmlAnom' => 33
            ],
            [
                'nm' => 'Jorong Tiumang',
                'id' => '1311022001000600',
                'kd' => '000600',
                'jmlAnom' => 2
            ],
            [
                'nm' => 'Jorong Padang Laweh',
                'id' => '1311023001000700',
                'kd' => '000700',
                'jmlAnom' => 14
            ],
            [
                'nm' => 'Jorong Sitiung',
                'id' => '1311030001000800',
                'kd' => '000800',
                'jmlAnom' => 22
            ],
            [
                'nm' => 'Jorong Timpeh',
                'id' => '1311031001000900',
                'kd' => '00900',
                'jmlAnom' => 11
            ],
            [
                'nm' => 'Jorong Pulau Punjung',
                'id' => '1311040001001000',
                'kd' => '001000',
                'jmlAnom' => 32
            ],
            [
                'nm' => 'Jorong IX Koto',
                'id' => '1311041001001100',
                'kd' => '001100',
                'jmlAnom' => 35
            ],
        ];
        $dataRuta = [
            [
                'nm' => 'Syafiq',
                'id' => '1311010001000100001',
                'kd' => '001',
                'jmlAnom' => 2
            ],
            [
                'nm' => 'Taufiq',
                'id' => '1311010001000100004',
                'kd' => '004',
                'jmlAnom' => 1
            ],

        ];
        $dataArt = [
            [
                'nm' => 'Rahma',
                'id' => '131101000100010000102',
                'kd' => '02',
                'jmlAnom' => 1
            ],
            [
                'nm' => 'Tifa',
                'id' => '131101000100010000402',
                'kd' => '02',
                'jmlAnom' => 3
            ],

        ];

        $ids = strlen($id);
        $data['id'] = $ids;
        switch ($ids) {
            case 4:
                // $data['listAnom'] = $dataDesa;
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah($id);
                $data['jenis'] = 'Kec';
                break;
            case 7:
                // $data['listAnom'] = $dataDesa;
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah($id);
                $data['jenis'] = 'Des';
                break;
            case 10:
                // $data['listAnom'] = $dataSLS;
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah($id);
                $data['jenis'] = 'SLS';
                break;
            case 16:
                // $data['listAnom'] = $dataRuta;
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah($id);
                $data['jenis'] = 'Ruta';
                break;
            case 19:
                // $data['listAnom'] = $dataArt;
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah($id);
                $data['jenis'] = 'Art';
                break;
            case 21:
                // $data['listAnom'] = $dataArt;
                $data['listAnom'] = $this->anomaliModel->getAnomaliByWilayah($id);
                $data['jenis'] = 'Anom';
                // return view('anomali/listAnomaliDetil', $data);
                return $this->listAnom($id);
                break;
        }
        // dd($data);

        return view('anomali/listAnomaliPart', $data);
    }

    public function listAnom($idArt)
    {
        $data = [
            'listAnom' => [
                [
                    'id' => '131104000100010000102',
                    'kdAnom' => 'AN21',
                    'detilAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni voluptate sed similique. Architecto non eius ut, beatae iste eveniet laboriosam nihil voluptate magnam aspernatur praesentium cum, veniam corrupti libero asperiores!',
                ],
                [
                    'id' => '131104000100010000102',
                    'kdAnom' => 'AN22',
                    'detilAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni voluptate sed similique. Architecto non eius ut, beatae iste eveniet laboriosam nihil voluptate magnam aspernatur praesentium cum',
                ]
            ]
        ];

        $data['listAnom'] = $this->anomaliModel->getListAnomali($idArt);
        // dd($data['listAnom']);

        return view('anomali/listAnomaliDetil', $data);
    }

    public function manajemenList()
    {
        $perPage = 10;

        $data = [
            "title" => "Manajemen Anomali",
            "listAnom" => [
                [
                    'id' => 1,
                    'kdAnom' => 'AN21',
                    'desAnom' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Id blanditiis, modi maiores ducimus nulla harum veritatis, facere pariatur ipsa magnam ipsum deleniti, vel odio eligendi veniam iusto accusantium quo fugiat?',
                    'detAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum architecto magnam hic quod assumenda, cumque veritatis iure nobis at ut.',
                    'isSee' => true,
                ],
                [
                    'id' => 2,
                    'kdAnom' => 'AN22',
                    'desAnom' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Id blanditiis, modi maiores ducimus nulla harum veritatis, facere pariatur ipsa magnam ipsum deleniti, vel odio eligendi veniam iusto accusantium quo fugiat?',
                    'detAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum architecto magnam hic quod assumenda, cumque veritatis iure nobis at ut.',
                    'isSee' => false,
                ],
                [
                    'id' => 3,
                    'kdAnom' => 'AN24',
                    'desAnom' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Id blanditiis, modi maiores ducimus nulla harum veritatis, facere pariatur ipsa magnam ipsum deleniti, vel odio eligendi veniam iusto accusantium quo fugiat?',
                    'detAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum architecto magnam hic quod assumenda, cumque veritatis iure nobis at ut.',
                    'isSee' => true,
                ],
            ],
        ];
        $data['listAnom'] = $this->katAnomaliModel->where('id_kegiatan', 1)->paginate($perPage, 'default');
        $data['pager'] = $this->katAnomaliModel->where('id_kegiatan', 1)->pager;
        $data['currentPage'] = $this->katAnomaliModel->where('id_kegiatan', 1)->pager->getCurrentPage();

        // dd($data);
        // dd($data);
        return view('anomali/manajemen', $data);
    }

    public function manajemenSee()
    {
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        if ($action === "toggle") {
            return redirect()->to('/anomali/manajemen')->with('message', 'Status Lihat diubah');
        } elseif ($action === "delete") {
            return redirect()->to('/anomali/manajemen')->with('message', 'anomali berhasil dihapus');
        } else {
            return redirect()->to('/anomali/manajemen')->with('error', 'aksi tidak valid');
        }
        return view('anomali/manajemen');
    }

    public function upload()
    {
        $data = [
            "title" => "Upload Anomali"
        ];
        return view('anomali/upload', $data);
    }

    public function template()
    {
        $template = "ini adalah template";
        dd($template);
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
        return view('anomali/edit', $data);
    }

    public function updateKonfirmasi()
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        $id = $this->request->getPost('id');
        $konfirmasi = $this->request->getVar('konfirmasi');
        $rules = [
            'id'           => 'required|is_natural_no_zero',
            'konfirmasi'   => 'required',
        ];
        if (! $this->validate($rules)) {
            // Jika validasi GAGAL, kembalikan JSON error
            dd("gagal validasi");
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $this->validator->getError('konfirmasi') // Kirim pesan error spesifik
            ]);
        }

        // Lakukan proses update
        $dataUpdate = [
            'konfirmasi' => trim($konfirmasi),
            // ...
        ];

        $this->anomaliModel->update($id, $dataUpdate);

        // Jika BERHASIL, kembalikan JSON sukses

        // dd("berhasil validasi");
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Konfirmasi berhasil disimpan.',
            'id_updated' => $id
        ]);

        // dd($konfirmasi);
    }
}
