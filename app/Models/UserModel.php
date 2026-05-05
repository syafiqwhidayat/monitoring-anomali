<?php

namespace App\Models;


use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use App\Entities\User; // Import entity buatan Anda
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class UserModel extends ShieldUserModel
{
    protected $returnType = User::class; // Beritahu model untuk menggunakan Entity ini

    protected function initialize(): void
    {
        parent::initialize();

        // Tambahkan kolom kustom Anda di sini agar diizinkan oleh CI4
        $this->allowedFields[] = 'wilayah_kerja';
        $this->allowedFields[] = 'name';
    }

    // protected $validationRules = [
    //     'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
    //     'email' => 'required|valid_email|is_unique[auth_identities.secret,id,{id}]',
    //     'wilayah_kerja' => 'required|numeric|exact_length[4]', // Contoh: harus 4 digit kode BPS
    // ];

    // Pesan error kustom (Opsional)
    // protected $validationMessages = [
    //     'wilayah_kerja' => [
    //         'required'     => 'Wilayah kerja harus diisi.',
    //         'exact_length' => 'Kode wilayah harus 4 digit angka.'
    //     ],
    //     'email' => [
    //         'regex_match' => 'email yg diperbolehkan hanya email bps @bps.go.id'
    //     ],
    // ];

    // fungsi untuk mengembalikan semua user berdasarkan Grup nya
    public function findAllByGroup($groupName = null, $isNotIn = False)
    {
        $users = $this->select('users.id, users.name as nama, auth_identities.secret as email,users.wilayah_kerja , auth_groups_users.group AS role')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_identities', 'auth_identities.user_id = users.id')
            ->where('auth_identities.type', 'email_password')
            ->orderBy('users.created_at', "ASCD");
        if (!$groupName) {
            return ($users->asArray());
        }
        if ($isNotIn) {
            $users->where('auth_groups_users.group !=', $groupName);
        } else {
            $users->where('auth_groups_users.group', $groupName);
        }
        return ($users->asArray());
    }

    public function cekKesamaanWilayahTugas($id1 = null, $id2 = null): bool
    {
        if (!$id1 || !$id2) {
            return false;
        }
        $user1 = $this->findById($id1);
        $user2 = $this->findById($id2);

        if ($user1->wilayah_kerja == $user2->wilayah_kerja) {
            return true;
        } else {
            return false;
        }
    }
}
