<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Node\Stmt\Case_;

class AnomaliModel extends Model
{
    protected $table = 'anomali';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_created';
    protected $updatedField = 'date_updated';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_kategori_anomali', 'id_user', 'id_wilayah', 'id_rtart', 'konfirmasi'];

    public function getAnomaliByWilayah($wilayah = false, $isNull = false)
    {
        $len = strlen($wilayah);
        $data = null;
        switch ($len) {
            case '4':
                $data = $this
                    ->select('SUBSTRING(anomali.id_wilayah, 1, 7) AS id, wilayah.kd_kec AS kd,wilayah.nm_kec AS nmKec,COUNT(*) AS jmlAnom')
                    ->join('wilayah', 'wilayah.id = anomali.id_wilayah', 'left')
                    ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    ->where('k.is_show', true)
                    ->groupBy('wilayah.kd_kec');
                break;
            case '7':
                $data = $this
                    ->select('SUBSTRING(anomali.id_wilayah, 1, 10) AS id, w.kd_des AS kd, w.nm_des AS nm,COUNT(*) AS jmlAnom')
                    ->join('wilayah w', 'w.id = anomali.id_wilayah', 'left')
                    ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    ->where('SUBSTRING(anomali.id_rtart, 1, 7)', $wilayah)
                    ->where('k.is_show', true)
                    ->groupBy('w.kd_des');
                break;
            case '10':
                $data = $this
                    ->select('anomali.id_wilayah AS id,wilayah.nm_sls AS nm, CONCAT(wilayah.kd_sls,wilayah.kd_subsls) AS kd ,COUNT(*) AS jmlAnom')
                    ->join('wilayah', 'wilayah.id = anomali.id_wilayah', 'left')
                    ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    ->where('SUBSTRING(anomali.id_rtart, 1, 10)', $wilayah)
                    ->where('k.is_show', true)
                    ->groupBy('CONCAT(wilayah.kd_sls,wilayah.kd_subsls)');
                break;
            case '16':
                $data = $this
                    ->select('SUBSTRING(anomali.id_rtart, 1, 19) AS id , art.kd_krt AS kd, art.nm_krt AS nm, COUNT(*) AS jmlAnom')
                    ->join('rt_art art', 'art.id = anomali.id_rtart', 'left')
                    ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    ->where('SUBSTRING(anomali.id_rtart, 1, 16)', $wilayah)
                    ->where('k.is_show', true)
                    ->groupBy('art.kd_krt');
                break;
            case '19':
                $data = $this
                    ->select('anomali.id_rtart AS id , art.kd_art AS kd, art.nm_art AS nm, COUNT(*) AS jmlAnom')
                    ->join('rt_art art', 'art.id = anomali.id_rtart', 'left')
                    ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    ->where('SUBSTRING(anomali.id_rtart, 1, 19)', $wilayah)
                    ->where('k.is_show', true)
                    ->groupBy('art.kd_krt');
                break;
            default:
                # code...
                break;
        }


        if ($wilayah == false) {
            return $this->select('anomali.*, wilayah.*')
                ->join('wilayah', 'wilayah.id = anomali.id_wilayah', 'left')
                ->findAll();
        };

        if (!$isNull) {
            $data = $data->where('konfirmasi', NULL);
        };

        $data = $data->findAll();

        return $data;
    }

    public function getListAnomali($idArt = false)
    {
        $len = strlen($idArt);
        $data = null;
        $data = $this
            ->select('anomali.id AS id, anomali.id_rtart AS id_rtart , k.kode_anomali AS kdAnom, k.detil_anomali AS detilAnom,anomali.konfirmasi')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.is_show', true)
            ->where('anomali.id_rtart', $idArt)
            ->findAll();

        // return $this->select('anomali.*, wilayah.*')
        //     ->join('wilayah', 'wilayah.id = anomali.id_wilayah', 'left')
        //     ->where('wilayah.id', $wilayah)
        //     ->findAll();
        return $data;
    }
}
