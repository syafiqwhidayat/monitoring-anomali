<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
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

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
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
        // dd($data['message']);

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
        $data['title'] = 'List Anomali';
        $data['listAnom'] = null;
        $data['id'] = null;
        $data['jenis'] = null;
        // apakah base rumahtangga
        $isRT = (session()->get('is_rt') == 1);

        // mendapatkan nilai filter
        $filterLevel = $this->request->getGet('fil-level');
        $id = $this->request->getGet('id-anomali');
        $filterKategori = $this->request->getGet('fil-kategori');
        $filterFlag = $this->request->getGet('fil-flag');
        $isEdit = $this->request->getGet('is-edit');

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

        if ($isRT) {
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
                    break;
                case 19:
                    $data['jenis'] = 'Art';
                    break;
                case 21:
                    $data['jenis'] = 'Anom';
                    return $this->listAnom($id, $isEdit);
                    break;
            }
        } else {
            if (strlen($id) == session('level_wilayah')) {
                $data['jenis'] = 'Nrt';
            } elseif (strlen($id) > session('level_wilayah')) {
                $data['jenis'] = 'Anom';
                return $this->listAnom($id, $isEdit);
            } else {
                switch (session('level_wilayah')) {
                    case 4:
                        // $data['listAnom'] = $dataDesa;q
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
                        break;
                    case 19:
                        $data['jenis'] = 'Art';
                        break;
                };
            };
        };
        // dd($data['listAnom']);
        return view('anomali/listAnomaliPart', $data);
    }

    public function listAnom($idArt, $isEdit)
    {
        // $data = [
        //     'listAnom' => [
        //         [
        //             'id' => '131104000100010000102',
        //             'kdAnom' => 'AN21',
        //             'detilAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni voluptate sed similique. Architecto non eius ut, beatae iste eveniet laboriosam nihil voluptate magnam aspernatur praesentium cum, veniam corrupti libero asperiores!',
        //         ],
        //         [
        //             'id' => '131104000100010000102',
        //             'kdAnom' => 'AN22',
        //             'detilAnom' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni voluptate sed similique. Architecto non eius ut, beatae iste eveniet laboriosam nihil voluptate magnam aspernatur praesentium cum',
        //         ]
        //     ]
        // ];

        $data['listAnom'] = $this->anomaliModel->getListAnomali($idArt, $isEdit);
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
            'konfirmasi' => 'required|trim|alpha_numeric_punct|min_length[5]'
        ];
        $this->validation->reset();
        $this->validation->setRules($rules);
        if (!$this->validation->run($datum)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $this->validator->getError('konfirmasi') // Kirim pesan error spesifik
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
        $data = [
            'title' => 'Konfirmasi Bulk Anomali',
            'data' => [
                'kode_anomali' => "AN01",
                'is_show' => false,
                'flag' => 1,
                'definisi' => "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi doloremque earum voluptatum rem vitae laboriosam laudantium dignissimos expedita nostrum id nobis sint obcaecati, molestias perferendis ullam ipsam ab deserunt unde voluptas ratione sit quasi consectetur debitis dolores! Reprehenderit, autem ipsum.",
                'detil' => "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi doloremque earum voluptatum rem vitae laboriosam laudantium dignissimos expedita nostrum id nobis sint obcaecati, molestias perferendis ullam ipsam ab deserunt unde voluptas ratione sit quasi consectetur debitis dolores! Reprehenderit, autem ipsum.",
                'id' => 1,
            ],
            'jumlah_anomali' => 30,
        ];

        // filter terpilih
        $data['filterAnomali'] = $this->request->getGet('fil-anomali') ?? null;
        $oldFilter = $this->request->getGet('fil-anomali-old') ?? null;

        // filter wilayah
        $data['sel_prov'] = $this->request->getGet('sel-prov') ?? 13;
        $data['sel_kab'] = $data['sel_prov'] ? $this->request->getGet('sel-kab') ?? null : null;
        // reset filter wilayah ketika ganti filter
        if ($oldFilter !== $data['filterAnomali']) {
            $data['sel_kab'] = null;
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
        $data['list_kab'] = $this->anomaliModel->getWilayah('kab', $data['filterAnomali'], $data['sel_prov']);
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
}
