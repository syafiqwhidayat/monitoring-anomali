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

    public function getAnomaliByWilayah($wilayah = false, $isEdit = false)
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
            case '21':
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

        if (!$isEdit) {
            $data = $data->where('konfirmasi', "");
        };

        $data = $data->findAll();

        return $data;
    }

    public function getListAnomali($idArt = false, $isEdit = false)
    {
        $len = strlen($idArt);
        $data = null;
        $query = $this
            ->select('anomali.id AS id, anomali.id_rtart AS id_rtart , k.kode_anomali AS kdAnom, k.detil_anomali AS detilAnom,anomali.konfirmasi')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.is_show', true)
            ->where('anomali.id_rtart', $idArt);
        // ->findAll();
        if (!$isEdit) {
            $query = $query->where('konfirmasi', "");
        };

        $data  = $query->findAll();

        // return $this->select('anomali.*, wilayah.*')
        //     ->join('wilayah', 'wilayah.id = anomali.id_wilayah', 'left')
        //     ->where('wilayah.id', $wilayah)
        //     ->findAll();
        return $data;
    }

    public function setKonfirmasiBulk($KategoriAnomali, $konfirmasi)
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali->where('kategori_anomali.kode_anomali', $KategoriAnomali);
        $joinKatAnomali->set('kategori_anomali.konfirmasi', $konfirmasi);
        // $joinKatAnomali->update();
        $hasil = $joinKatAnomali->findAll();
        // $hasil = $joinKatAnomali->update();
        dd($hasil);

        // update Anomali
        // $joinKatAnomali->update([
        //     'anomali.konfirmasi' => $konfirmasi
        // ]);
    }

    public function jumlahKonfirmasiByAnoamli()
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali->select('kategori_anomali.kode_anomali,COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah_terisi")
            ->groupBy('kategori_anomali.kode_anomali');
        $hasil = $joinKatAnomali->findAll();
        // dd($hasil);
        return ($hasil);
    }

    public function jumlahKonfirmasiByWiayah($idKat = 1)
    {
        // $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');

        $hasil = $this
            ->select("LEFT(id_wilayah,10) as 'id_kec'")
            ->select('COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah_terisi")
            ->where('id_kategori_anomali', $idKat)
            ->groupBy("id_kec");
        $hasil = $hasil->findAll();
        // dd($hasil);
        return ($hasil);
    }
    public function jumlahKonfirmasiByPublik($idKat = null)
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali->select('COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN kategori_anomali.is_show = 1 THEN 1 ELSE 0 END) as jumlah_public")
            ->select("SUM(CASE WHEN kategori_anomali.is_show = 0 THEN 1 ELSE 0 END) as jumlah_non_public");
        if ($idKat) {
            $joinKatAnomali->where('anomali.id_kategori_anomali', $idKat);
        }
        $hasil = $joinKatAnomali->findAll();
        return ($hasil);
    }
    public function jumlahProses($jenis = "all", $idKat = null)
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali->select('COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah_terisi");

        switch ($jenis) {
            case "public":
                $joinKatAnomali->where('kategori_anomali.is_show', 1);
                break;
            case "non_public":
                $joinKatAnomali->where('kategori_anomali.is_show', 0);
                break;
            case "flag1":
                $joinKatAnomali->where('kategori_anomali.flag', 1);
                break;
            case "flag2":
                $joinKatAnomali->where('kategori_anomali.flag', 2);
                break;
            case "flag3":
                $joinKatAnomali->where('kategori_anomali.flag', 3);
                break;
                dafault:
                $joinKatAnomali->where('kategori_anomali.is_show', 1);
                break;
        }
        if ($idKat) {
            $joinKatAnomali->where('anomali.id_kategori_anomali', $idKat);
        }
        $hasil = $joinKatAnomali->findAll();
        // dd($hasil);
        return ($hasil);
    }
    public function jumlahByTanggal($idKat = '')
    {
        $dataUpdate = $this
            ->select("DATE(date_updated) as 'tanggal'")
            // ->select("COUNT(*) as jumlah")
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah")
            ->groupBy("tanggal");
        if ($idKat) {
            $dataUpdate->where('id_kategori_anomali', $idKat);
        };
        $dataUpdate = $dataUpdate->findAll();

        $dataCreated = $this
            ->select("DATE(date_created) as 'tanggal'")
            ->select("COUNT(*) as jumlah")
            ->groupBy("tanggal");
        if ($idKat) {
            $dataCreated->where('id_kategori_anomali', $idKat);
        };
        $dataCreated = $dataCreated->findAll();

        $data = [
            "dataUpdated" => $dataUpdate,
            "dataCreated" => $dataCreated
        ];
        return ($data);
    }

    public function getTop5($id_kat = '')
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali
            ->select('id_wilayah,kategori_anomali.kode_anomali,detil_anomali,konfirmasi')
            ->where("konfirmasi IS NOT NULL AND konfirmasi != ''")
            ->orderBy('anomali.date_updated', 'DESC')
            ->limit(5);
        if ($id_kat) {
            $joinKatAnomali->where('id_kategori_anomali', $id_kat);
        };

        $hasil = $joinKatAnomali->findAll();
        return ($hasil);
    }

    public function wordCloudKonfirmasi($idKat = '', $limit = 30)
    {
        // Ambil semua teks dari kolom konfirmasi
        $data = $this->select('konfirmasi');
        if ($idKat) {
            $data->where('id_kategori_anomali', $idKat);
        };
        $data = $data->findAll();

        // Gabungkan jadi satu string besar
        $text = implode(" ", array_column($data, 'konfirmasi'));
        $text = strtolower($text);

        // Bersihkan karakter aneh
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);

        // Hitung kata
        $words = str_word_count($text, 1);

        // Filter stopwords (kata yang tidak penting)
        $stopWords = ['dan', 'yang', 'untuk', 'di', 'dari', 'ke', 'ini', 'itu', 'dengan', 'ada', 'telah', 'perlu'];
        $filteredWords = array_diff($words, $stopWords);

        $counts = array_count_values($filteredWords);
        arsort($counts); // Urutkan dari yang terbesar

        // Format untuk JavaScript: [["kata", 10], ["kata", 5]]
        $result = [];
        foreach (array_slice($counts, 0, $limit) as $word => $count) {
            $result[] = [$word, (int)$count];
        }

        return $result;
    }
}
