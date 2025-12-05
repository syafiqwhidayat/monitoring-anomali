<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use Config\Services;
use Faker\Provider\Lorem;

class ManajAnom extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
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
        return view('manajAnom/manajemen', $data);
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
