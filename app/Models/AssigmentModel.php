<?php

namespace App\Models;

use App\Validation\CustomRules;
use CodeIgniter\Model;
use PhpParser\Node\Stmt\Case_;

class AssigmentModel extends Model
{
    protected $table = 'assigment';
    protected $useTimestamps = false;
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'id_wilayah', 'id_kegiatan', 'kd_krt', 'kd_art', 'nm_krt', 'nm_art', 'id_bs'];
    protected $validationRules = [
        'id_wilayah' => 'required|is_not_unique[wilayah.id]',

    ];
    protected $validationMessages = [
        'id_kegiatan' => [
            'is_not_unique' => 'Wialyah tidak ditemukan pada database'
        ],
    ];

    public function getOrInsert($data)
    {
        // 1. Cek apakah id_rtart sudah ada
        $existing = $this->where('id', $data['id_assigment'])->first();
        if ($existing) {
            // Jika ada, kembalikan primary key (id)-nya
            return $existing['id'];
        }
        // 2. Jika belum ada, insert data baru
        // dd($data);
        $dataBaru = [
            'id' => $data['id_assigment'],
            'id_wilayah' => substr($data['id_assigment'], 0, 16),
            'id_kegiatan' => 1,
            'kd_krt' => $data['nurt'],
            'kd_art' => $data['nuart'],
            'nm_krt' => $data['nama_krt'],
            'nm_art' => $data['nama_art'],
        ];
        $this->insert($dataBaru);

        // Kembalikan ID yang baru saja dibuat
        return $this->insertID();
    }
}
