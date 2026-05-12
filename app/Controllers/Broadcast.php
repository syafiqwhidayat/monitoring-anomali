<?php

namespace App\Controllers;

use CodeIgniter\Model;
use App\Models\BroadcastModel;
use App\Models\UserModel;
use CodeIgniter\Validation\Validation;
use PhpOffice\PhpSpreadsheet\RichText\Run;

class Broadcast extends BaseController
{
    protected $broadcastModel;
    protected $userModel;
    protected $validator;

    public function __construct()
    {
        $this->broadcastModel = new BroadcastModel();
        $this->userModel = new UserModel();
        $this->validator = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title'      => 'Broadcast Information',
        ];
        $currentWilayah = auth()->user()->wilayah_kerja;

        // variabel filter
        $data['filterWilayah'] = $this->request->getGet('fil-wilayah') ?? $currentWilayah;

        // pilihan filter
        $data['listWilayah'] = [];
        $listWilayah = $this->broadcastModel->select('wilayah')
            ->where('id_kegiatan', session('aktif_kegiatan'))
            ->distinct()
            // ->orderBy('wilayah', 'ASC')
            ->findAll();

        foreach ($listWilayah as ['wilayah' => $kode]) {
            $data['listWilayah'][] = [
                'id'   => $kode,
                'nama' => "Broadcast $kode",
            ];
        }

        $data['listWilayah'] = array_merge($data['listWilayah'], $listSelKdAnom ?? []);


        $hasil = $this->broadcastModel->getBroadcast($data['filterWilayah']);
        $data['broadcasts'] = !empty($hasil) ? $hasil : [];

        return view('broadcast/index', $data);
    }

    public function simpan()
    {
        $currentUser = auth()->user();

        $data['id'] = $this->request->getPost('br-id') ?? null;
        $data['judul'] = strip_tags($this->request->getPost('br-judul'));
        $data['isi'] = strip_tags($this->request->getPost('br-isi'));
        $data['kategori'] = $this->request->getPost('br-kategori');
        $data['wilayah'] = $currentUser->wilayah_kerja;
        $rule = [
            'judul' => "max_length[40]|trim",
            'isi' => "max_length[300]|trim"
        ];
        $this->validator->reset();
        if (!$this->validator->setRules($rule)->run($data)) {
            $error = $this->validator->getErrors();
            $firstError = !empty($error) ? array_values($error)[0] : null;
            // memunculkan appaun pesan error yang pertama
            return redirect()->back()->with('error', "Kesalahan Input : $firstError");
        }


        if ($data['id']) {
            // cek apakah super admin bisa edit semua
            $selectedBroadcast = $this->broadcastModel->find($data['id']);
            if ($currentUser->inGroup('superadmin')) {
            } elseif ($currentUser->inGroup('admin')) {
                // cek apakah admin wilayah bersangkutan punya akses
                if (!$this->userModel->cekKesamaanWilayahTugas($selectedBroadcast['id_user'], $currentUser->id)) {
                    return redirect()->back()->with('error', 'User tidak punya akses ubah');
                }
            } else {
                return redirect()->back()->with('error', 'anda tidak punya akses untuk edit');
            }
            // jika aman semuanya.
            unset($data['id']);
            if (!$this->broadcastModel->update($selectedBroadcast['id'], $data)) {
                return redirect()->back()->with('error', 'gagal edit broadcast');
            } else {
                return redirect()->back()->with('message', 'berhasil edit broadcast');
            };
        } else {
            $data['id_kegiatan'] = session()->get('aktif_kegiatan');
            $data['id_user'] = $currentUser->id;
        }

        if ($this->broadcastModel->save($data)) {
            return redirect()->back()->with('message', 'berhasil menambahkan broadcast');
        } else {
            return redirect()->back()->with('error', 'gagal menambahkan broadcast');
        }
    }

    public function hapus()
    {
        $id = $this->request->getPost('id');
        $currentUser = auth()->user();
        // cek apakah super admin bisa edit semua
        if (!$currentUser->inGroup('superadmin')) {
            // cek apakah admin wilayah bersangkutan punya akses
            $selectedBroadcast = $this->broadcastModel->findById($id);
            if (!$this->userModel->cekKesamaanWilayahTugas($selectedBroadcast->id_user, $currentUser->id)) {
                return redirect()->back()->with('error', 'User tidak punya akses hapus');
            }
        }
        if ($this->broadcastModel->delete($id)) {
            return redirect()->back()->with('message', 'berhasil hapus broadcast');
        } else {
            return redirect()->back()->with('error', 'Gagal Hapus Broadcast');
        }
    }
}
