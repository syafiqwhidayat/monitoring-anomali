<?php

namespace App\Controllers;

use App\Models\UserModel;

class ManajUser extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return null;
    }

    public function manajOrganik()
    {
        $users = $this->userModel->findAllByGroup('mitra', true);
        $perPage = 10;
        $data['users'] = $users->paginate($perPage, 'default');
        $data['pager'] = $users->pager;
        $data['currentPage'] = $users->pager->getCurrentPage();
        $data['perPage'] = $perPage;

        $data['title'] = 'Manajemen User Organik';

        return view('manajUser/manajOrganik', $data);
    }
    public function manajMitra()
    {
        $users = $this->userModel->findAllByGroup('mitra');
        $perPage = 10;
        $data['users'] = $users->paginate($perPage, 'default');
        $data['pager'] = $users->pager;
        $data['currentPage'] = $users->pager->getCurrentPage();
        $data['perPage'] = $perPage;

        // $data['users'] = [
        //     [
        //         'id'    => 1,
        //         'nama'  => 'Syafiq',
        //         'email' => 'syafiq@bps.go.id',
        //         'role'  => 'admin'
        //     ],
        //     [
        //         'id'    => 2,
        //         'nama'  => 'Rahma',
        //         'email' => 'rahma@bps.go.id',
        //         'role'  => 'operator'
        //     ],
        //     [
        //         'id'    => 3,
        //         'nama'  => 'Lathifah Dzakiyah',
        //         'email' => 'lathifah@bps.go.id',
        //         'role'  => 'operator'
        //     ],
        //     [
        //         'id'    => 4,
        //         'nama'  => 'Admin Dharmasraya',
        //         'email' => 'bps1311@bps.go.id',
        //         'role'  => 'admin'
        //     ]
        // ];
        $data['title'] = 'Manajemen User Organik';

        return view('manajUser/manajOrganik', $data);
    }
    public function tambahOrganik()
    {
        $data['wilayah_sumbar'] = [
            '1300' => '[1300] SUMATERA BARAT',
            '1301' => '[1301] KEPULAUAN MENTAWAI',
            '1302' => '[1302] PESISIR SELATAN',
            '1303' => '[1303] SOLOK',
            '1304' => '[1304] SIJUNJUNG',
            '1305' => '[1305] TANAH DATAR',
            '1306' => '[1306] PADANG PARIAMAN',
            '1307' => '[1307] AGAM',
            '1308' => '[1308] LIMA PULUH KOTA',
            '1309' => '[1309] PASAMAN',
            '1310' => '[1310] PASAMAN BARAT',
            '1311' => '[1311] DHARMASRAYA',
            '1312' => '[1312] SOLOK SELATAN',
            '1371' => '[1371] PADANG',
            '1372' => '[1372] SOLOK (KOTA)',
            '1373' => '[1373] SAWAHLUNTO',
            '1374' => '[1374] PADANG PANJANG',
            '1375' => '[1375] BUKITTINGGI',
            '1376' => '[1376] PAYAKUMBUH',
            '1377' => '[1377] PARIAMAN',
        ];
        $data['title'] = 'Tambah User Organik';
        $data['roles'] = [
            'superadmin' => 'SuperAdmin',
            'admin' => 'Admin',
            'operator' => 'Operator',
            'mitra' => 'Mitra',
        ];

        return view('manajUser/tambahOrganik', $data);
    }

    public function simpanOrganik()
    {
        $userData = $this->request->getPost();
        $userData['username'] = strtok($userData['email'], '@');
        $userData['name'] = ucwords($userData['name']);

        // Rule untuk tambah organik
        $rule = [
            'email'         => 'required|valid_email|is_unique[auth_identities.secret]|regex_match[/^[a-zA-Z0-9._%+-]+@bps\.go\.id$/]',
            'wilayah_kerja' => 'required',
            'name'          => 'required|alpha'
        ];
        $validationMessages = [
            'wilayah_kerja' => [
                'required'     => 'Wilayah kerja harus diisi.',
                'exact_length' => 'Kode wilayah harus 4 digit angka.'
            ],
            'email' => [
                'regex_match' => 'email yg diperbolehkan hanya email bps @bps.go.id',
                'is_unique' => 'email sudah terdaftar di sistem'
            ],
            'name' => [
                'alpha' => 'Nama hanya boleh diisi huruf',
            ]
        ];


        // Logika simpan ke database di sini
        $userEntitas = new \App\Entities\User();
        $userEntitas->fill($userData);

        $isValid = $this->validate($rule, $validationMessages);
        // dd($this->userModel->errors()['email']);
        if ($isValid) {
            $this->userModel->save($userEntitas);
            $userBaru = $this->userModel->findById($this->userModel->getInsertID());
            $userBaru->addGroup($userData['role']); // Menentukan role default
            return redirect()->back()->with('message', 'User berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        };
    }

    public function editOrganik($id)
    {
        $data['wilayah_sumbar'] = [
            '1300' => '[1300] SUMATERA BARAT',
            '1301' => '[1301] KEPULAUAN MENTAWAI',
            '1302' => '[1302] PESISIR SELATAN',
            '1303' => '[1303] SOLOK',
            '1304' => '[1304] SIJUNJUNG',
            '1305' => '[1305] TANAH DATAR',
            '1306' => '[1306] PADANG PARIAMAN',
            '1307' => '[1307] AGAM',
            '1308' => '[1308] LIMA PULUH KOTA',
            '1309' => '[1309] PASAMAN',
            '1310' => '[1310] PASAMAN BARAT',
            '1311' => '[1311] DHARMASRAYA',
            '1312' => '[1312] SOLOK SELATAN',
            '1371' => '[1371] PADANG',
            '1372' => '[1372] SOLOK (KOTA)',
            '1373' => '[1373] SAWAHLUNTO',
            '1374' => '[1374] PADANG PANJANG',
            '1375' => '[1375] BUKITTINGGI',
            '1376' => '[1376] PAYAKUMBUH',
            '1377' => '[1377] PARIAMAN',
        ];
        $data['title'] = 'Tambah User Organik';
        $data['roles'] = [
            'superadmin' => 'SuperAdmin',
            'admin' => 'Admin',
            'operator' => 'Operator',
            'mitra' => 'Mitra',
        ];
        $user = $this->userModel->findById($id);
        if ($user) {
            $data['id'] = $id;
            $data['email'] = $user->getIdentities()[0]->secret;
            $data['name'] = $user->name;
            $data['role'] = $user->getGroups()[0];
            $data['wilayah_kerja'] = $user->wilayah_kerja;
            return view('manajUser/editOrganik', $data);
        } else {
            return redirect()->back()->with('message_errors', 'User tidak ditemukan');
        }
    }

    public function simpanEditOrganik()
    {
        $userData = $this->request->getPost();
        $userData['username'] = strtok($userData['email'], '@');
        $userData['name'] = ucwords($userData['name']);
        $id = $userData['id'];

        // Logika simpan ke database di sini
        $userEntitas = new \App\Entities\User();
        $userEntitas->fill($userData);
        $rule = [
            'email'         => 'required|valid_email|is_unique[auth_identities.secret,user_id,{id}]',
            'wilayah_kerja' => 'required',
            'name'          => 'required',
            'id'          => 'required',
        ];

        // dd($userEntitas->getIdentities());

        $isValid = $this->validate($rule);
        if ($isValid) {
            $user = $this->userModel->findById($id);
            if ($user->getGroups()) {
                $grup = $user->getGroups()[0];
                $user->removeGroup($grup);
            }
            $userEntitas->addGroup($userData['role']);
            $this->userModel->update($id, $userEntitas);
            // $this->userModel->save($userEntitas);
            return redirect()->to('/user/mitra')->with('message', 'User berhasil diedit');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        };
    }

    public function hapusOrganik()
    {
        $id = $this->request->getGet('id');
        if ($this->userModel->delete($id)) {
            return redirect()->to('/user/organik')->with('message', 'User berhasil dihapus');
        } else {
            return redirect()->to('/user/organik')->with('errors', 'User Tidak Bisa Dihapus');
        };
    }
}
