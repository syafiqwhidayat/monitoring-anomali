<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        // Tambahkan kolom kustom Anda di sini agar diizinkan oleh CI4
        $this->allowedFields = [
            ...$this->allowedFields,
            'nama_lengkap',
            'role',
            'id_wilayah'
        ];
    }
}
