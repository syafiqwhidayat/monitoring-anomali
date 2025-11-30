<?php

namespace App\Models;

use App\Validation\CustomRules;
use CodeIgniter\Model;
use PhpParser\Node\Stmt\Case_;

class KatAnomaliModel extends Model
{
    protected $table = 'kategori_anomali';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_created';
    protected $updatedField = 'date_updated';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_kegiatan', 'kode_anomali', 'definisi_anomali', 'detil_anomali', 'is_show'];
    protected $validationRules = [
        'id_kegiatan' => 'is_not_unique[kegiatan.id]',
        'kode_anomali' => 'uniqueWith[kategori_anomali.id_kegiatan.kode_anomali]',
    ];
    protected $validationMessages = [
        'id_kegitan' => [
            'is_not_unique' => 'kegiatan statistik tidak ditemukan di database'
        ],
        'kode_anomali' => [
            'uniqueWith' => 'kode anomali sudah ada untuk kegiatan ini'
        ]
    ];
}
