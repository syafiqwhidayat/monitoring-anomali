<?php

namespace App\Models;

use App\Controllers\Wilayah;
use CodeIgniter\Model;

class WilayahTugasModel extends Model
{
    protected $table            = 'wilayah_tugas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_wilayah', 'id_kegiatan', 'id_ppl', 'id_pml'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
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

    public function getKegiatanByUser($userId)
    {
        return $this->select('id_kegiatan AS id, k.nama_kegiatan as nama')
            ->join('kegiatan k', 'k.id = id_kegiatan')
            ->where('id_user', $userId)
            ->orderBy('id_kegiatan', 'DESC') // Tetap ambil yang terbaru dari yang dia punya
            ->distinc()
            ->findAll();
    }

    public function getWilayahTugasByKegaitan($idKegiatan, $kdKab = null, $kdKec = null, $kdDes = null)
    {
        $data = $this->select('w.*,ppl.name AS nm_ppl, id_ppl.secret AS em_ppl,pml.name AS nm_pml, id_pml.secret AS em_pml')
            ->join('wilayah w', 'w.id = id_wilayah', 'left')
            ->join('users ppl', 'ppl.id = id_ppl', 'left')
            ->join('auth_identities id_ppl', 'id_ppl.user_id = ppl.id', 'left')
            ->join('users pml', 'pml.id = id_pml')
            ->join('auth_identities id_pml', 'id_pml.user_id = pml.id', 'left')
            ->where('id_kegiatan', $idKegiatan);

        if ($kdKab) {
            $data->where('w.kd_kab', $kdKab);
        }
        if ($kdKec) {
            $data->where('w.kd_kec', $kdKec);
        }
        if ($kdDes) {
            $data->where('w.kd_des', $kdDes);
        }
        return $data->findAll();
    }
}
