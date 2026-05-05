<?php

namespace App\Controllers;

use CodeIgniter\Model;
use App\Models\BroadcastModel;
use App\Models\UserModel;

class Broadcast extends BaseController
{
    protected $broadcastModel;
    protected $userModel;

    public function __construct()
    {
        $this->broadcastModel = new BroadcastModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Broadcast Information',
        ];

        $data['broadcasts'] = $this->broadcastModel->getBroadcast(session('aktif_kegiatan'), auth()->user()->wilayah_kerja);

        return view('broadcast/index', $data);
    }

    public function simpan()
    {
        $data['id'] = $this->request->getPost('br-id');
        $data['judul'] = $this->request->getPost('br-judul');
        $data['isi'] = $this->request->getPost('br-isi');
        $data['kategori'] = $this->request->getPost('br-kategori');
        $currentUser = auth()->user();
        if ($data['id']) {
            // cek apakah super admin bisa edit semua
            if (!$currentUser->inGroup('superadmin')) {
                // cek apakah admin wilayah bersangkutan punya akses
                $selectedBroadcast = $this->broadcastModel->findById($data['id']);
                if (!$this->userModel->cekKesamaanWilayahTugas($selectedBroadcast->id_user, $currentUser->id)) {
                    return redirect()->back()->with('error', 'User tidak punya akses ubah');
                }
            }
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
