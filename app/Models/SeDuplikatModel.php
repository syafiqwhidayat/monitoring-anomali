<?php

namespace App\Models;

use CodeIgniter\Model;

class SeDuplikatModel extends Model
{
    protected $table = 'se_cekduplikat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_mirip', 'kode_identitas', 'nama_usaha', 'id_wilayah'];
    protected $returnType = 'array';

    /**
     * Mengambil data duplikat kelompok berdasarkan wilayah
     */
    public function getDuplikatByWilayah($idWilayah)
    {
        return $this->where('id_wilayah', $idWilayah)
            ->orderBy('id_mirip', 'ASC')
            ->findAll();
    }

    public function getAvailabelWilayah()
    {
        return $this->select('id_wilayah')
            ->distinct()
            ->findAll();
    }
}
