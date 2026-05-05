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
            'id_wilayah' => $data['id_wilayah'],
            'id_kegiatan' => 1,
            'kd_krt' => $data['nurt'] ?? null,
            'kd_art' => $data['nuart'] ?? null,
            'nm_krt' => $data['nama_krt'] ?? null,
            'nm_art' => $data['nama_art'] ?? null,
            'kd_nrt' => $data['kode_nrt'] ?? null,
            'nm_nrt' => $data['nama_nrt'] ?? null,
        ];
        $this->insert($dataBaru);

        // Kembalikan ID yang baru saja dibuat
        return $this->insertID();
    }
}
