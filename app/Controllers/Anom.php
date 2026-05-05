<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use Config\Services;
use Faker\Provider\Lorem;

use function PHPUnit\Framework\returnArgument;

class Anom extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
    }

    public function list($isEdit = false)
    {
        // cek is edit jika ada inisial value
        $data['isEdit'] = $isEdit ?: ($this->request->getGet('is-edit') ?? false);
        $data['filterWilayah'] = null;
        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        $data['filterKategori'] = $this->request->getGet('fil-kategori') ?? '';
        $data['filterFlag'] = $this->request->getGet('fil-flag') ?? '';
        $isRT = (session()->get('is_rt') == 1);

        // cek filter wilayah inisial value
        $userWilayah = auth()->user()->wilayah_kerja;
        $data['filterWilayah'] = ($userWilayah != '1300')
            ? $userWilayah
            : ($this->request->getGet('fil-wilayah') ?? '1300');

        // Persiapan data dropdown

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

        return view('anomali/listAnomali', $data);
    }

    public function listDetil()
    {
        $data['title'] = 'List Anomali';
        $data['listAnom'] = null;
        $data['id'] = null;
        $data['jenis'] = null;
        $isRT = (session()->get('is_rt') == 1);
        // dd(session()->get('is_rt') == 1);

        $filterLevel = $this->request->getGet('fil-level');
        $id = $this->request->getGet('id-anomali');
        $filterKategori = $this->request->getGet('fil-kategori');
        $filterFlag = $this->request->getGet('fil-flag');
        $isEdit = $this->request->getGet('is-edit');
        // dd($isRT);
        try {
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
            // dd($e->getMessage());
        }
        // dd($data['listAnom']);

        if ($isRT) {
            switch (strlen($id)) {
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
                case 21:
                    $data['jenis'] = 'Anom';
                    return $this->listAnom($id, $isEdit);
                    break;
            }
        } else {
            if (strlen($id) == session('level_wilayah')) {
                $data['jenis'] = 'Nrt';
                // return $this->listAnom($id, $isEdit);
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

        return view('anomali/listAnomaliPart', $data);
    }

    public function listAnom($idArt, $isEdit)
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

        $data['listAnom'] = $this->anomaliModel->getListAnomali($idArt, $isEdit);


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

        $id = $this->request->getPost('id');
        $konfirmasi = $this->request->getVar('konfirmasi');
        $rules = [
            'id'           => 'required|is_natural_no_zero',
            // 'konfirmasi'   => 'required',
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

    public function updateKonfirmasiBulk()
    {
        $konfirmasi = $this->request->getVar('konfirmasi');
        $id_kode_anomali = $this->request->getVar('kode_anomali');
        $konfirmasi = trim($konfirmasi);
        // dd($id_kode_anomali);

        // Lakukan proses update
        $dataUpdate = [
            'konfirmasi' => trim($konfirmasi),
            // ...
        ];
        $jumlah = 0;

        // if ($this->anomaliModel->setKonfirmasiBulk($id_kode_anomali, $konfirmasi)) {
        //     $jumlah = $this->anomaliModel->db->affectedRows();
        //     // echo "Berhasil memperbarui $jumlah data.";
        // } else {
        //     dd("gagal");
        // }
        $hasil = $this->anomaliModel
            ->where('id_kategori_anomali', $id_kode_anomali)
            ->set(['konfirmasi' => $konfirmasi])
            ->update();
        // ->findAll();
        // dd($hasil);
        if ($hasil) {
            $jumlah = $this->anomaliModel->db->affectedRows();
            // echo "Berhasil memperbarui $jumlah data.";
        } else {
            dd("gagal");
        }

        // Jika BERHASIL, kembalikan JSON sukses

        // dd("berhasil validasi");
        // return $this->response->setJSON([
        //     'status' => 'success',
        //     'message' => 'Konfirmasi berhasil disimpan. Berhasil mempengaruhi ' . $jumlah . ' anomali'
        // ]);

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
            'listKodeAnom' => null,
            'data' => [
                'is_show' => true,
            ],
        ];

        $data['listKodeAnom'] = $this->anomaliModel->getKdAnomaliByUser() ?? [];
        // dd($data['listKodeAnom']);

        // dd($data['listKodeAnom']);

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
