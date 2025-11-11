<?php

namespace App\Controllers;

use \App\Models\ComicsModel;
use Config\Services;

class Comics extends BaseController
{
    protected $comicsModel;

    public function __construct()
    {
        $this->comicsModel = new ComicsModel();
    }

    public function index()
    {
        // $comics = $this->comicsModel->findAll();

        $data = [
            'title' => 'Daftar Komik',
            'comics' => $this->comicsModel->getComic()
        ];


        return view('comics/index', $data);
    }

    public function detail($slug)
    {
        $comic = $this->comicsModel->getComic($slug);
        $data = [
            'title' => 'Detail Komik',
            'comic' => $comic
        ];

        //jika komik tidak ada di tabel
        if (empty($data['comic'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Komik ' . $slug . ' tidak ditemukan');
        }

        return view('comics/detail', $data);
    }

    public function create()
    {
        session();
        // $val = \Config\Services::validation();
        // $val = service('validation');
        $val = session()->getFlashdata('validation');
        ($val) ? $val = $val : $val = service('validation');

        // d($val);
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => $val
        ];

        return view('comics/create', $data);
    }

    public function save()
    {
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $validator = service('validation');
        $dataToValidate = [
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            // 'sampul' => $this->request->getVar('sampul')
        ];

        $rule = [
            // 'judul' => [
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} komik harus diisi'
            //     ]
            // ],
            'judul' => 'required|is_unique[comics.judul]',
            'slug' => 'required|is_unique[comics.slug]',
            'penulis' => 'required',
            'penerbit' => 'required',
            'sampul' => 'uploaded[sampul]|max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg, image/jpeg,image/png]'
        ];


        // validasi input
        if (!$this->validate($rule)) {
            // if (!$validator->setRules($rule)->run($dataToValidate)) {
            // $validation = \Config\Services::validation();
            $validation = service('validation');
            // $validation = $validator;
            // dd($validation);
            // dd($this->validator->getErrors());
            return redirect()->to('/comics/create')
                ->withInput()
                ->with('validation', $validation);
        }


        // $this->comicsModel->save($dataToValidate);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');

        return redirect()->to('/comics');
    }

    public function delete($id = null)
    {
        // $cah = $id;
        // $this->comicsModel->delete($id);
        $this->comicsModel->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/comics');
    }

    public function edit($slug)
    {
        session();
        $val = session()->getFlashdata('validation');
        ($val) ? $val = $val : $val = service('validation');

        d($val);
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => $val,
            'comic' => $this->comicsModel->getComic($slug)
        ];

        return view('comics/edit', $data);
    }

    public function update($id)
    {
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $validator = service('validation');
        $dataToValidate = [
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            // 'sampul' => $this->request->getVar('sampul')
        ];

        $rule = [
            'judul' => 'required|is_unique[comics.judul,id,' . $id . ']',
            'slug' => 'required|is_unique[comics.slug,id,' . $id . ']',
            'penulis' => 'required',
            'penerbit' => 'required',
        ];

        if (!$validator->setRules($rule)->run($dataToValidate)) {
            $validation = $validator;
            return redirect()->to('/comics/edit/' . $this->request->getVar('slug'))
                ->withInput()
                ->with('validation', $validation);
        }


        $this->comicsModel->save($dataToValidate);

        session()->setFlashdata('pesan', 'Data berhasil diubah.');

        return redirect()->to('/comics');
    }
}
