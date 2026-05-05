<?php

namespace App\Models;

use App\Controllers\Broadcast;
use CodeIgniter\Model;

class BroadcastModel extends Model
{
    protected $table            = 'broadcasts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'id_kegiatan',
        'judul',
        'isi',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'judul' => 'required',
        'isi' => 'required',
        'kategori' => 'required',
        'id_kegiatan' => 'required',
        'id_user' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getBroadcast($id_kegiatan = null, $wilayah_kerja = null)
    {
        if (!$id_kegiatan || !$wilayah_kerja) {
            return null;
        }

        $data = $this->select('broadcasts.*,us.wilayah_kerja')
            ->where('id_kegiatan', $id_kegiatan)
            ->whereIn('us.wilayah_kerja', ['1300', $wilayah_kerja])
            ->join('users us', 'us.id =id_user')
            ->orderBy('created_at', 'DESC');
        $data = $data->findAll();
        foreach ($data as $key => $dat) {
            $warna = null;
            switch ($dat['kategori']) {
                case 'sop':
                    $warna = 'blue';
                    break;
                case 'kbli':
                    $warna = 'green';
                    break;
                case 'kondef':
                    $warna = 'orange';
                    break;

                default:
                    $warna = 'blue';
                    break;
            }

            $data[$key]['warna'] = $warna;
            if (substr($dat['wilayah_kerja'], -2) === "00") {
                $data[$key]['level'] = 'provinsi';
            } else {
                $data[$key]['level'] = 'kabupaten';
            }
        }

        return ($data);
    }
}
