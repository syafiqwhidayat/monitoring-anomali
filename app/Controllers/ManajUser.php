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

    public function list($isMitra = null)
    {
        $data['title'] = 'Manajemen User Organik';

        $data['isMitra'] = $this->request->getGet('fil-mitra') ?? $isMitra ?? null;

        $data['filterKab'] = $this->request->getGet('fil-kab') ?? '';
        $data['filterKeyword'] = $this->request->getGet('fil-word') ?? '';

        // filter
        $data['listKab'] = [
            ['id' => ''],
            ['id' => '1301'],
            ['id' => '1302'],
            ['id' => '1303'],
            ['id' => '1304'],
            ['id' => '1305'],
            ['id' => '1306'],
            ['id' => '1307'],
            ['id' => '1308'],
            ['id' => '1309'],
            ['id' => '1310'],
            ['id' => '1311'],
            ['id' => '1312'],
            ['id' => '1371'],
            ['id' => '1372'],
            ['id' => '1373'],
            ['id' => '1374'],
            ['id' => '1375'],
            ['id' => '1376'],
            ['id' => '1377'],
        ];
        if ($data['isMitra']) {
            $data['title'] = "Manajemen User Mitra";
        }

        $perPage = 10;
        $users = $this->userModel->findAllByGroup(
            'mitra',
            !$data['isMitra'],
            $data['filterKab'],
            $data['filterKeyword']
        );

        $data['users'] = $users->paginate($perPage, 'default');
        $data['pager'] = $users->pager;
        $data['currentPage'] = $users->pager->getCurrentPage();
        $data['perPage'] = $perPage;


        return view('manajUser/manajUser', $data);
    }

    public function tambah()
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
        ];

        return view('manajUser/tambahUser', $data);
    }

    public function simpan()
    {
        $userData = $this->request->getPost();
        $userData['username'] = strtok($userData['email'], '@');
        $userData['name'] = ucwords($userData['name']);
        $isOrganik = str_ends_with($userData['email'], "@bps.go.id");

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
            if ($isOrganik) {
                $userBaru->addGroup('mitra');
            }
            return redirect()->back()->with('message', 'User berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        };
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User Mitra';
        $data['isOrganik'] = false;

        $user = $this->userModel->findById($id);
        $userEmail = $user->getIdentities()[0]->secret;
        if (str_ends_with($userEmail, '@bps.go.id')) {
            $data['isOrganik'] = true;
            $data['title'] = 'Edit User Oganik';
            $data['roles'] = [
                'superadmin' => 'SuperAdmin',
                'admin' => 'Admin',
                'operator' => 'Operator',
            ];
        } else {
            $data['roles'] = [
                'mitra' => 'Mitra',
            ];
        }

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

        if ($user) {
            $data['id'] = $id;
            $data['email'] = $userEmail;
            $data['name'] = $user->name;
            $data['role'] = $user->getGroups()[0];
            $data['wilayah_kerja'] = $user->wilayah_kerja;
            return view('manajUser/editUser', $data);
        } else {
            return redirect()->back()->with('message_errors', 'User tidak ditemukan');
        }
    }

    public function simpanEdit()
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

        $isValid = $this->validate($rule);
        if ($isValid) {
            $user = $this->userModel->findById($id);
            $isOldOrganik = str_ends_with($user->getIdentity('email')->secret, '@bps.go.id');
            $isNewOrganik = str_ends_with($userData['email'], '@bps.go.id');
            if ($isOldOrganik == $isNewOrganik) {
            } else {
                return redirect()->back()->withInput()->with('errors', "tidak boleh ganti domain email");
            }
            if (!$user->inGroup($userData['role'])) {
                foreach ($user->getGroups() as $grup) {
                    if ($grup != 'mitra') {
                        $user->removeGroup($grup);
                    }
                }
                $user->addGroup($userData['role']);
            }
            $this->userModel->update($id, $userEntitas);
            return redirect()->back()->back()->with('message', 'User berhasil diedit');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        };
    }

    public function hapus()
    {
        $id = $this->request->getGet('id');
        if ($this->userModel->delete($id)) {
            return redirect()->to('/user/organik')->with('message', 'User berhasil dihapus');
        } else {
            return redirect()->to('/user/organik')->with('errors', 'User Tidak Bisa Dihapus');
        };
    }
}
