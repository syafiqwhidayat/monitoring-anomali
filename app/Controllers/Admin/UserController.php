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

        $wilayahTugasModel = new \App\Models\WilayahTugasModel();
        $kegiatanModel = new \App\Models\KegiatanModel();

        // mendapatkan daftar kegiatan aktif
        if (session()->get('aktif_role') == 'mitra') {
            $daftar_kegiatan = $wilayahTugasModel->getKegiatanByUser(auth()->id());
        } else {
            $daftar_kegiatan = $kegiatanModel->getKegiatanDesc();
        }

        if (!$daftar_kegiatan && session()->get('aktif_kegiatan')) {
            $daftar_kegiatan = [['id' => null, 'nama' => 'tidak ada kegiatan terdaftar']];
            session()->set('aktif_kegiatan', null);
            session()->set('nama_kegiatan', 'Tidak ada kegiatan terdaftar');
        }

        if (!session()->get('aktif_kegiatan') && $daftar_kegiatan) {
            session()->set('aktif_kegiatan', $daftar_kegiatan[0]['id']);
            session()->set('nama_kegiatan', $daftar_kegiatan[0]['nama']);
        }

        $target = $this->request->getGet('return') ?? base_url('/');
        return redirect()->to($target)->with('message', 'Berhasil Ganti Role: ' . session()->get('aktif_role'));
    }

    public function profile()
    {
        $user = auth()->user();
        // Ambil maksimal 2 role
        $roles = array_slice($user->getGroups(), 0, 2);

        return view('auth/profile_view', [
            'title' => "User Profile",
            'user'  => $user,
            'roles' => $roles
        ]);
    }

    public function updateProfile()
    {
        $user = auth()->user();
        $userModel = model(UserModel::class);

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        // Validasi password jika diisi
        if ($this->request->getPost('password')) {
            $rules['password']     = 'required|strong_password';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update data nama
        $user->name = ucwords($this->request->getPost('nama'));

        // Update password jika ada input
        if ($this->request->getPost('password')) {
            $user->password = $this->request->getPost('password');
        }

        $userModel->save($user);

        return redirect()->to('profile')->with('message', 'Profil berhasil diperbarui!');
    }
}
