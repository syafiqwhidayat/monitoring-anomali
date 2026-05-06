<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // List semua user
    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('admin/user/index', $data);
    }

    // Form tambah user & proses simpan
    public function store()
    {
        $users = auth()->getProvider();

        $user = new User([
            'username'     => $this->request->getPost('username'),
            'email'        => $this->request->getPost('email'),
            'password'     => $this->request->getPost('password'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'id_wilayah'   => $this->request->getPost('id_wilayah'),
        ]);

        if ($users->save($user)) {
            // Beri role (misal: operator)
            $user = $users->findById($users->getInsertID());
            $user->addGroup($this->request->getPost('role'));

            return redirect()->back()->with('success', 'User berhasil didaftarkan');
        }

        return redirect()->back()->with('errors', $users->errors());
    }

    // fungsi untuk ganti role
    public function gantiRole()
    {
        $groups = auth()->user()->getGroups();
        $currentGrops = session()->get('aktif_role');
        $i = null;
        foreach ($groups as $group) {

            if ($currentGrops != $group) {
                session()->set('aktif_role', $group);
            }
        }

        $target = $this->request->getGet('return') ?? base_url('/');
        return redirect()->to($target)->with('message', 'Kegiatan berhasil diubah ke: ' . session()->get('nama_kegiatan'));
    }
}
