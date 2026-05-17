<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Node\Stmt\Case_;

class AnomaliKegiatanWilayahTugasModel extends Model
{
    protected $table = 'v_anomali_kegiatan_wilayah_tugas';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_created';
    protected $updatedField = 'date_updated';
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    public function getAnomaliByWilayah($wilayah = null, $isEdit = null, $kode_anomali = null, $flag = null, $levelAnomali = null, $isRT = true)
    {
        $id_kegiatan = session()->get('aktif_kegiatan') ?? null;
        $level_wilayah = session()->get('level_wilayah') ?? null;
        $idUser = auth()->user()->id;
        $isOrganik = session('isOrganik') ?? true;

        // mengambil panjang id wilayah
        $len = strlen($wilayah);
        $data = null;
        if ($len == $level_wilayah && !$isRT) {
            $data = $this
                ->select("kd_assigment AS id, kd_nrt as kd, nm_nrt AS nm, COUNT(*) AS jmlAnom")
                ->where('id_wilayah', $wilayah)
                ->where('is_show', true)
                ->groupBy('id_assigment');

            // $data = $this
            //     ->select("art.kd_assigment AS id , art.kd_nrt AS kd, art.nm_nrt AS nm, COUNT(*) AS jmlAnom")
            //     ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
            //     ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            //     ->where('art.id_wilayah', $wilayah)
            //     ->where('k.is_show', true)
            //     ->groupBy('art.kd_assigment');
        } else {
            // memasukkan filter by wilayah
            switch ($len) {
                case '0':
                    // $data = $this->select("LEFT(art.kd_assigment,4) AS id, wilayah.kd_kab AS kd,wilayah.nm_kab AS nm,COUNT(DISTINCT anomali.id) AS jmlAnom")
                    //     ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                    //     ->join('wilayah', 'wilayah.id = art.id_wilayah', 'left')
                    //     ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    //     ->where('SUBSTRING(anomali.id_wilayah, 1, 2)', '13')
                    //     ->where('k.is_show', true)
                    //     ->groupBy('wilayah.kd_kab');
                    $data = $this->select("LEFT(kd_assigment,4) AS id, kd_kab AS kd,nm_kab AS nm, COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(id_wilayah, 1, 2)', '13')
                        ->where('is_show', true)
                        ->groupBy('kd_kab');
                    break;
                case '4':
                    // $data = $this
                    //     ->select("SUBSTRING(art.kd_assigment, 1, 7) AS id, wilayah.kd_kec AS kd,wilayah.nm_kec AS nm,COUNT(DISTINCT anomali.id) AS jmlAnom")
                    //     ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                    //     ->join('wilayah', 'wilayah.id = art.id_wilayah', 'left')
                    //     ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    //     ->where('SUBSTRING(anomali.id_wilayah, 1, 4)', $wilayah)
                    //     ->where('k.is_show', true)
                    //     ->groupBy('wilayah.kd_kec');
                    $data = $this
                        ->select("SUBSTRING(kd_assigment, 1, 7) AS id, kd_kec AS kd,nm_kec AS nm, COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(id_wilayah, 1, 4)', $wilayah)
                        ->where('is_show', true)
                        ->groupBy('kd_kec');
                    break;
                case '7':
                    // $data = $this
                    //     ->select("SUBSTRING(art.kd_assigment, 1, 10) AS id, w.kd_des AS kd, w.nm_des AS nm,COUNT(*) AS jmlAnom")
                    //     ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                    //     ->join('wilayah w', 'LEFT(w.id,LENGTH(art.id_wilayah)) = art.id_wilayah', 'left')
                    //     ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    //     ->where('SUBSTRING(anomali.id_wilayah, 1, 7)', $wilayah)
                    //     ->where('k.is_show', true)
                    //     ->groupBy('w.kd_des');
                    $data = $this
                        ->select("SUBSTRING(kd_assigment, 1, 10) AS id, kd_des AS kd, nm_des AS nm,COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(id_wilayah, 1, 7)', $wilayah)
                        ->where('is_show', true)
                        ->groupBy('kd_des');
                    break;
                case '10':
                    // $data = $this
                    //     ->select("SUBSTRING(art.kd_assigment, 1, 16) AS id,wilayah.nm_sls AS nm, CONCAT(wilayah.kd_sls,wilayah.kd_subsls) AS kd ,COUNT(*) AS jmlAnom")
                    //     ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                    //     ->join('wilayah', 'LEFT(wilayah.id,LENGTH(art.id_wilayah)) = art.id_wilayah', 'left')
                    //     ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    //     ->where('SUBSTRING(art.id_wilayah, 1, 10)', $wilayah)
                    //     ->where('k.is_show', true)
                    //     ->groupBy('CONCAT(wilayah.kd_sls,wilayah.kd_subsls)');
                    $data = $this
                        ->select("SUBSTRING(kd_assigment, 1, 16) AS id,nm_sls AS nm, CONCAT(wilayah.kd_sls,wilayah.kd_subsls) AS kd ,COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(id_wilayah, 1, 10)', $wilayah)
                        ->where('k.is_show', true)
                        ->groupBy('CONCAT(wilayah.kd_sls,wilayah.kd_subsls)');
                    break;
                case '16':
                    // $data = $this
                    //     ->select("SUBSTRING(art.kd_assigment, 1, 19) AS id , art.kd_krt AS kd, art.nm_krt AS nm, COUNT(*) AS jmlAnom")
                    //     ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
                    //     ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
                    //     ->where('SUBSTRING(anomali.id_wilayah, 1, 16)', $wilayah)
                    //     ->where('k.is_show', true)
                    //     ->groupBy('art.kd_krt');
                    $data = $this
                        ->select("SUBSTRING(kd_assigment, 1, 19) AS id , kd_krt AS kd, nm_krt AS nm, COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(id_wilayah, 1, 16)', $wilayah)
                        ->where('is_show', true)
                        ->groupBy("SUBSTRING(kd_assigment, 1, 19)");
                    break;
                case '19':
                    $data = $this
                        ->select("SUBSTRING(kd_assigment, 1, 21) AS id , kd_art AS kd, nm_art AS nm, COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(kd_assigment, 1, 19)', $wilayah)
                        ->where('is_show', true)
                        ->groupBy('SUBSTRING(kd_assigment, 1, 21)');
                    break;
                case '21':
                    $data = $this
                        ->select("kd_assigment AS id , kd_art AS kd, nm_art AS nm, COUNT(DISTINCT id) AS jmlAnom")
                        ->where('SUBSTRING(kd_assigment, 1, 21)', $wilayah)
                        ->where('is_show', true)
                        ->groupBy('kd_assigment');
                    break;
                default:
                    # code...
                    break;
            }
        }

        // filter is Edit
        if ($isEdit) {
            $data = $data->where('LENGTH(konfirmasi) > 0');
        } else {
            $data = $data->where('LENGTH(konfirmasi) = 0');
        };


        // filter berdasarkan kode anomali
        if ($kode_anomali) {
            $data = $data->where('id_kategori_anomali', $kode_anomali);
        };

        // filter berdasarkan flag
        if ($flag) {
            $data = $data->where('k.flag', $flag);
        };

        // filter berdasarkan level anomali
        if ($levelAnomali) {
            $data = $data->where('k.level_anomali', $levelAnomali);
        };


        // filter id kegiatan
        if ($id_kegiatan) {
            $data = $data->where('k.id_kegiatan', $id_kegiatan);
        } else {
            return null;
        };

        // filter untuk wilayah tugas mitra
        if (!$isOrganik) {
            $data->Where('id_ppl', $idUser)
                ->orWhere('id_pml', $idUser);
        }


        $data = $data->findAll();


        return $data;
    }

    public function getListAnomali($idArt = false, $isEdit = false)
    {

        $len = strlen($idArt);
        $data = null;

        $idKegiatan = session()->get('aktif_kegiatan') ?? null;

        $query = $this
            ->select('anomali.id AS id, art.kd_assigment AS id_assigment , k.kode_anomali AS kdAnom, k.detil_anomali AS detilAnom,anomali.konfirmasi,anomali.is_lap as is_lap')
            ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.is_show', true)
            ->where('art.kd_assigment', $idArt);
        // ->findAll();
        if ($isEdit) {
            $query->where('LENGTH(konfirmasi) > 0');
        } else {
            $query->where('LENGTH(konfirmasi) = 0');
        };

        if ($idKegiatan) {
            $query = $query->where('k.id_kegiatan', $idKegiatan);
        };

        $data  = $query->findAll();

        return $data;
    }

    public function setKonfirmasiBulk($KategoriAnomali, $konfirmasi)
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali->where('kategori_anomali.kode_anomali', $KategoriAnomali);
        $joinKatAnomali->where('konfirmasi', '');
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

    public function jumlahKonfirmasiByAnoamli($id_kegiatan)
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali->select('kategori_anomali.kode_anomali,COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah_terisi")
            ->where('id_kegiatan', $id_kegiatan)
            ->groupBy('kategori_anomali.kode_anomali');
        $hasil = $joinKatAnomali->findAll();
        // dd($hasil);
        return ($hasil);
    }

    public function jumlahKonfirmasiByWiayah($idKat = 1, $idkab = null, $level = null, $levelOuput = 4)
    {
        $id_kegiatan = session()->get('aktif_kegiatan');
        $level_wilayah = session()->get('aktif_kegiatan');

        $hasil = $this
            ->select("LEFT(id_wilayah,$levelOuput) as 'id_wil'")
            ->select('COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah_terisi")
            ->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali')
            ->where('id_kategori_anomali', $idKat)
            ->groupBy("id_wil");
        if ($idkab) {
            $hasil->where('LEFT(anomali.id_wilayah,4)', $idkab);
        }
        if ($level) {
            $hasil->where('kategori_anomali', $level);
        }

        $hasil = $hasil->findAll();
        return ($hasil);
    }
    public function jumlahKonfirmasiByPublik($idKat = null)
    {
        $idKegiatan = session()->get('aktif_kegiatan');

        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');

        $joinKatAnomali->select('COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN kategori_anomali.is_show = 1 THEN 1 ELSE 0 END) as jumlah_public")
            ->select("SUM(CASE WHEN kategori_anomali.is_show = 0 THEN 1 ELSE 0 END) as jumlah_non_public");
        if ($idKat) {
            $joinKatAnomali->where('anomali.id_kategori_anomali', $idKat);
        }

        $joinKatAnomali->where('id_kegiatan', $idKegiatan);
        $hasil = $joinKatAnomali->findAll();
        return ($hasil);
    }
    public function jumlahProses($jenis = "all", $idKat = null, $idWilayah = null)
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
            default:
                $joinKatAnomali->where('kategori_anomali.is_show', 1);
                break;
        }
        if ($idKat) {
            $joinKatAnomali->where('anomali.id_kategori_anomali', $idKat);
        }
        if ($idWilayah) {
            $joinKatAnomali->whereIn('kategori_anomali.level_anomali', ['1300', $idWilayah]);
        }
        $hasil = $joinKatAnomali->findAll();

        return ($hasil);
    }
    public function jumlahByTanggal($idKat = '')
    {
        $idKegiatan = session()->get('aktif_kegiatan');
        $dataUpdate = $this
            ->select("DATE(anomali.date_updated) as 'tanggal'")
            ->select("SUM(CASE WHEN konfirmasi IS NOT NULL AND konfirmasi != '' THEN 1 ELSE 0 END) as jumlah")
            ->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali')
            ->groupBy("tanggal");
        if ($idKat) {
            $dataUpdate->where('id_kategori_anomali', $idKat);
        };
        if ($idKegiatan) {
            $dataUpdate->where('id_kegiatan', $idKegiatan);
        }
        $dataUpdate = $dataUpdate->findAll();

        $dataCreated = $this
            ->select("DATE(anomali.date_created) as 'tanggal'")
            ->select("COUNT(*) as jumlah")
            ->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali')
            ->groupBy("tanggal");
        if ($idKat) {
            $dataCreated->where('id_kategori_anomali', $idKat);
        };
        if ($idKegiatan) {
            $dataCreated->where('id_kegiatan', $idKegiatan);
        }
        $dataCreated = $dataCreated->findAll();

        $data = [
            "dataUpdated" => $dataUpdate,
            "dataCreated" => $dataCreated
        ];
        return ($data);
    }

    public function getTop5($id_kat = '')
    {
        $idKegiatan = session()->get('aktif_kegiatan');
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $joinKatAnomali
            ->select('id_wilayah,kategori_anomali.kode_anomali,detil_anomali,konfirmasi')
            ->where("konfirmasi IS NOT NULL AND konfirmasi != ''")
            ->orderBy('anomali.date_updated', 'DESC')
            ->limit(5);
        if ($id_kat) {
            $joinKatAnomali->where('id_kategori_anomali', $id_kat);
        };
        if ($idKegiatan) {
            $joinKatAnomali->where('id_kegiatan', $idKegiatan);
        }

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
        $stopWords = ['bulk', 'sistem', 'dan', 'yang', 'untuk', 'di', 'dari', 'ke', 'ini', 'itu', 'dengan', 'ada', 'telah', 'perlu', 'lagi', 'apakah', 'sudah', 'adalah', 'tidak', 'bukan', 'juga', 'saya', 'kami', 'dia', 'mereka', 'oleh', 'pada', 'serta', 'sebagai'];
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

    public function getAnomaliByAssigment($kd_assigment)
    {
        $query = $this
            ->select('anomali.id AS id, ass.kd_assigment AS id_assigment , k.kode_anomali AS kdAnom, k.detil_anomali AS detilAnom,anomali.konfirmasi, anomali.id_kategori_anomali AS id_kategori_anomali')
            ->join('assigment ass', 'ass.id = anomali.id_assigment', 'left')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('anomali.id_assigment', $kd_assigment);
        $data  = $query->findAll();
        return ($data);
    }

    public function getKdAnomaliByUser()
    {
        $id_kegiatan = session()->get('aktif_kegiatan');
        $wilayah_kerja = auth()->user()->wilayah_kerja;

        $data = $this->select('k.kode_anomali AS nama,k.level_anomali AS level, id_kategori_anomali as id')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.id_kegiatan', $id_kegiatan);

        if ($wilayah_kerja != '1300') {
            $data->whereIn('k.level_anomali', ['1300', $wilayah_kerja]);
        }
        $data->orderBy('level_anomali', 'ASC')
            ->orderBy('kode_anomali', 'ASC')
            ->distinct();

        return ($data->findAll());
    }

    public function getFlagByUser()
    {
        $id_kegiatan = session()->get('aktif_kegiatan');
        $wilayah_kerja = auth()->user()->wilayah_kerja;

        $data = $this->select('k.flag AS value')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.id_kegiatan', $id_kegiatan);

        if ($wilayah_kerja != '1300') {
            $data->whereIn('k.level_anomali', ['1300', $wilayah_kerja]);
        }

        $data->orderBy('level_anomali', 'ASC')
            ->orderBy('kode_anomali', 'ASC')
            ->distinct();
        return ($data->findAll());
    }

    public function getLevelAnomByUser()
    {
        $id_kegiatan = session()->get('aktif_kegiatan');
        $wilayah_kerja = auth()->user()->wilayah_kerja;

        $data = $this->select('k.level_anomali AS id')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.id_kegiatan', $id_kegiatan);

        if ($wilayah_kerja != '1300') {
            $data->whereIn('k.level_anomali', ['1300', $wilayah_kerja]);
        }

        $data->orderBy('level_anomali', 'ASC')
            ->orderBy('kode_anomali', 'ASC')
            ->distinct();

        return ($data->findAll());
    }
    public function getWilayahAnomByUser()
    {
        $id_kegiatan = session()->get('aktif_kegiatan');
        $wilayah_kerja = auth()->user()->wilayah_kerja;

        $data = $this->select('LEFT(anomali.id_wilayah,4) AS id')
            // ->join('assigment a', 'a.id = anomali.id_assigment', 'left')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->where('k.id_kegiatan', $id_kegiatan);

        if ($wilayah_kerja != '1300') {
            $data->where('LEFT(anomali.id_wilayah,4)', $wilayah_kerja);
        }

        $data->orderBy('id', 'ASC'); //id disini adalah id Wilayah
        $data->distinct();

        return ($data->findAll());
    }

    public function getWilayah($level = 'kab', $idKat = null, $idProv = null, $idKab = null, $idKec = null, $idDes = null)
    {
        if ($level == 'kab' && !$idProv) return null;
        if ($level == 'kec' && !$idKab) return null;
        if ($level == 'des' && !$idKec) return null;
        if ($level == 'sls' && !$idDes) return null;

        $idKegiatan = session('aktif_kegiatan');
        $data = $this
            ->join('wilayah w', 'w.id = id_wilayah')
            ->join('kategori_anomali k', 'k.id = id_kategori_anomali')
            ->where('id_kegiatan', $idKegiatan);

        switch ($level) {
            case 'kab':
                $data->select('kd_kab AS id, nm_kab AS nama');
                break;
            case 'kec':
                $data->select('kd_kec AS id, nm_kec AS nama');
                break;
            case 'des':
                $data->select('kd_des AS id, nm_des AS nama');
                break;
            case 'sls':
                $data->select('kd_sls AS id, nm_sls AS nama');
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

        return ($data->distinct()->findAll());
    }
}
