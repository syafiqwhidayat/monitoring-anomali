<?php

namespace App\Models;

use CodeIgniter\Model;

class SeMonitoringModel extends Model
{
    protected $table            = 'se_monitoring';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['kd_wilayah', 'id_log', 'jml_open', 'jml_submit', 'tbh_opeh', 'tbh_submit', 'jml_ed'];

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


    public function jumlahKonfirmasiByWiayah()
    {
        $subQuery = $this->builder()
            ->select('kd_wilayah, MAX(created_at) as terbaru')
            ->groupBy('kd_wilayah')
            ->getCompiledSelect();

        $hasil = $this->select('se_monitoring.kd_wilayah')
            ->select("(jml_open + jml_submit + tbh_open +tbh_submit) AS 'jumlah_total'")
            ->select("(jml_submit + tbh_submit) as jumlah_submit")
            ->select("(jml_ed) as jumlah_ED")
            ->join("($subQuery) t", 'se_monitoring.kd_wilayah = t.kd_wilayah AND se_monitoring.created_at = t.terbaru')
            ->orderBy('se_monitoring.kd_wilayah', 'ASC');
        // dd($hasil->findAll());
        return ($hasil->findAll());
    }

    public function getDataTerbaru()
    {
        $subQuery = $this->builder()
            ->select('kd_wilayah, MAX(created_at) as terbaru')
            ->groupBy('kd_wilayah')
            ->getCompiledSelect();

        $hasil = $this->select('se_monitoring.*')
            // ->select("(jml_open + jml_submit + tbh_open +tbh_submit) AS 'jumlah_total'")
            // ->select("(jml_submit + tbh_submit) as jumlah_submit")
            // ->select("(jml_ed) as jumlah_ED")
            ->join("($subQuery) t", 'se_monitoring.kd_wilayah = t.kd_wilayah AND se_monitoring.created_at = t.terbaru')
            ->orderBy('se_monitoring.kd_wilayah', 'ASC');
        // dd($hasil->findAll());
        return ($hasil->findAll());
    }

    public function jumlahSeluruhWilayah($idKat = null)
    {
        $subQuery = $this->builder()
            ->select('kd_wilayah, MAX(created_at) as terbaru')
            ->groupBy('kd_wilayah')
            ->getCompiledSelect();

        $hasil = $this
            ->select("SUM(jml_submit) AS 'jml_submit'")
            ->select("SUM(jml_open) as jml_open")
            ->select("SUM(tbh_submit) as tbh_submit")
            ->select("SUM(tbh_open) as tbh_open")
            ->select("SUM(jml_ed) as jml_ed")
            ->join("($subQuery) t", 'se_monitoring.kd_wilayah = t.kd_wilayah AND se_monitoring.created_at = t.terbaru')
            ->where('se_monitoring.kd_wilayah LIKE', '13%');

        // dd($hasil->findAll());


        $hasil = $hasil->findAll();
        return ($hasil);
    }

    public function jumlahByTanggal($idKat = '')
    {
        $hasil = $this
            ->select("DATE(created_at) as 'tanggal'")
            ->select("SUM(jml_ed) as jumlah_ed")
            ->select("SUM(jml_submit + tbh_submit) as jumlah_submit")
            ->select("SUM(jml_open + tbh_open + jml_submit + tbh_submit) as jumlah_total")
            ->groupBy("tanggal");
        $hasil = $hasil->findAll();
        return ($hasil);
    }
}
