<?php

namespace App\Models;

use App\Controllers\Wilayah;
use App\Models\UserModel;
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
        $data = $this->select('id_kegiatan AS id, k.nama_kegiatan as nama')
            ->join('kegiatan k', 'k.id = id_kegiatan')
            ->where('id_ppl', $userId)
            ->orWhere('id_pml', $userId)
            ->orderBy('id_kegiatan', 'DESC') // Tetap ambil yang terbaru dari yang dia punya
            ->distinct();

        return $data->findAll();
    }

    public function getWilayahTugasByKegaitan($idKegiatan, $kdKab = null, $kdKec = null, $kdDes = null, $keyword = null)
    {
        $this->builder()->resetQuery();

        $builder = $this->builder();
        $builder->select('wilayah_tugas.id AS id_wt,w.*,ppl.name AS nm_ppl, id_ppl.secret AS em_ppl,pml.name AS nm_pml, id_pml.secret AS em_pml')
            ->join('wilayah w', 'w.id = id_wilayah', 'left')
            ->join('users ppl', 'ppl.id = id_ppl', 'left')
            ->join('auth_identities id_ppl', 'id_ppl.user_id = ppl.id', 'left')
            ->join('users pml', 'pml.id = id_pml', 'left')
            ->join('auth_identities id_pml', 'id_pml.user_id = pml.id', 'left')
            ->groupBy('id_wilayah')
            ->where('id_kegiatan', $idKegiatan);

        if ($kdKab) $builder->where('w.kd_kab', $kdKab);
        if ($kdKec) $builder->where('w.kd_kec', $kdKec);
        if ($kdDes) $builder->where('w.kd_des', $kdDes);

        if ($keyword) {
            $builder->groupStart()
                ->like('ppl.name', $keyword)
                ->orLike('pml.name', $keyword)
                ->orLike('id_ppl.secret', $keyword)
                ->orLike('id_pml.secret', $keyword)
                ->groupEnd();
        }
        return $builder->get()->getResultArray();
    }

    public function getUserByKegiatan($wilayah_kerja = null)
    {
        $userModel = new UserModel();
        $db = \Config\Database::connect();
        $wilayahKerja = auth()->user()->wilayah_kerja; //wilayah kerja user yang request

        $subquery = $db->table('wilayah_tugas')
            ->select('id_pml AS id_user, 1 as prioritas')
            // ->where('id_kegiatan', $idKegiatan) // Filter untuk PML
            ->union(
                $db->table('wilayah_tugas')->select('id_ppl AS id_user,2 as prioritas')
                // ->where('id_kegiatan', $idKegiatan) // Filter untuk PML
            )
            ->getCompiledSelect();

        $data = $userModel->select('users.id AS id,users.name AS nama,ide.secret AS email')
            ->join('auth_identities ide', 'ide.user_id = users.id', 'left')
            ->join("($subquery) AS tabel_gabung", 'tabel_gabung.id_user = users.id', 'inner')
            ->orderBy('tabel_gabung.prioritas', 'ASC');

        if ($wilayahKerja != '1300') {
            $data->where('users.wilayah_kerja', $wilayahKerja);
        }

        return ($data->asArray()->findAll());
    }

    public function getWilayah($level = 'kab', $idProv = null, $idKab = null, $idKec = null, $idDes = null)
    {
        $idKegiatan = session('aktif_kegiatan');
        $data = $this->distinct()
            ->join('wilayah w', 'w.id = id_wilayah')
            ->where('id_kegiatan', $idKegiatan);

        switch ($level) {
            case 'kab':
                $data->select('kd_kab AS id, nm_kab AS nama');
                if (!$idProv) {
                    return null;
                }
                break;
            case 'kec':
                $data->select('kd_kec AS id, nm_kec AS nama');
                if (!$idKab) {
                    return null;
                }
                break;
            case 'des':
                $data->select('kd_des AS id, nm_des AS nama');
                if (!$idKec) {
                    return null;
                }
                break;
            case 'sls':
                $data->select('kd_sls AS id, nm_sls AS nama');
                if (!$idDes) {
                    return null;
                }
                break;
        }

        if ($idProv) {
            $data->where('kd_prov', $idProv);
            $data->where('kd_kab !=', "");
        }
        if ($idKab) {
            $data->where('kd_kab', $idKab);
            $data->where('kd_kec !=', "");
        }
        if ($idKec) {
            $data->where('kd_kec', $idKec);
            $data->where('kd_des !=', "");
        }
        if ($idDes) {
            $data->where('kd_des', $idDes);
            $data->where('kd_sls !=', "");
        }

        return ($data->findAll());
    }
}
