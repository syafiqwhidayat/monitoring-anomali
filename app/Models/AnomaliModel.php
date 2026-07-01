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
    protected $allowedFields = [
        'id_kategori_anomali',
        'id_user',
        'id_wilayah',
        'id_assigment',
        'konfirmasi',
        'is_lap',
        'is_insert',
        'is_sistem',
        'isi_fasih',
    ];

    public function getAnomaliByWilayah($wilayah = null, $isEdit = null, $kode_anomali = null, $flag = null, $levelAnomali = null, $isRT = true)
    {
        $id_kegiatan = session()->get('aktif_kegiatan') ?? null;
        $level_wilayah = session()->get('level_wilayah') ?? null;
        $idUser = auth()->user()->id;
        $isRoleMitra = session('aktif_role') == 'mitra';

        // Jika id_kegiatan tidak ada, langsung hentikan proses di awal (Early Return)
        if (!$id_kegiatan) {
            return null;
        }

        $data = $this
            ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
            ->join('wilayah', 'wilayah.id = art.id_wilayah', 'left')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->join('wilayah_tugas wt', 'wt.id_wilayah = anomali.id_wilayah AND wt.id_kegiatan = k.id_kegiatan', 'left');

        // Hitung panjang wilayah (hanya jika tidak mengandung '_')
        $hasUnderscore = (strpos($wilayah, '_') !== false);
        $len = $hasUnderscore ? $level_wilayah : strlen($wilayah);

        // 1. Template konfigurasi query dinamis BPS
        $levelConfig = [
            '0' => [
                'select'  => "LEFT(art.kd_assigment, 4) AS id, wilayah.kd_kab AS kd, wilayah.nm_kab AS nm, COUNT(DISTINCT anomali.id) AS jmlAnom",
                'where'   => ['SUBSTRING(anomali.id_wilayah, 1, 2)', '13'],
                'groupBy' => 'id'
            ],
            '4' => [
                'select'  => "SUBSTRING(art.kd_assigment, 1, 7) AS id, wilayah.kd_kec AS kd, wilayah.nm_kec AS nm, COUNT(DISTINCT anomali.id) AS jmlAnom",
                'where'   => ['SUBSTRING(anomali.id_wilayah, 1, 4)', $wilayah],
                'groupBy' => 'id'
            ],
            '7' => [
                'select'  => "SUBSTRING(art.kd_assigment, 1, 10) AS id, wilayah.kd_des AS kd, wilayah.nm_des AS nm, COUNT(DISTINCT anomali.id) AS jmlAnom",
                'where'   => ['SUBSTRING(anomali.id_wilayah, 1, 7)', $wilayah],
                'groupBy' => 'id'
            ],
            '10' => [
                'select'  => "SUBSTRING(art.kd_assigment, 1, 16) AS id, CONCAT(wilayah.kd_sls, wilayah.kd_subsls) AS kd, wilayah.nm_sls AS nm, COUNT(DISTINCT anomali.id) AS jmlAnom",
                'where'   => ['SUBSTRING(art.id_wilayah, 1, 10)', $wilayah],
                'groupBy' => 'id'
            ],
            '16' => [
                // isRT = 0 -> Format: [kdWilayah]_[kdAssigment]
                'select'  => "SUBSTRING_INDEX(art.kd_assigment, '_', 2) AS id, art.kd_krt AS kd, art.nm_krt AS nm, COUNT(DISTINCT anomali.id) AS jmlAnom",
                'where'   => ["SUBSTRING(anomali.id_wilayah, 1, $level_wilayah)", $wilayah],
                'groupBy' => 'id'
            ],
            'RT' => [
                // isRT = 1 -> Format: [kdwilayah]_[kdAssigment]_[kdRoster]
                'select'  => "SUBSTRING_INDEX(art.kd_assigment, '_', 3) AS id, art.kd_art AS kd, art.nm_art AS nm, COUNT(DISTINCT anomali.id) AS jmlAnom",
                'where'   => ["SUBSTRING_INDEX(art.kd_assigment, '_', 2)", $wilayah],
                'groupBy' => 'id'
            ]
        ];

        // 2. LOGIKA BARU PENENTUAN LEVEL (Sangat Terarah)
        if ($hasUnderscore || $len > $level_wilayah) {
            // Jika parameter $wilayah sudah membawa '_', berarti kita mutlak berada di level rincian jeroan Ruta
            $currentLevel = ($isRT == 1) ? 'RT' : (string)$level_wilayah;
        } elseif ($len == $level_wilayah) {
            // Jika baru mencapai batas maksimal wilayah, tentukan tujuannya berdasarkan tipe kegiatan (isRT)
            $currentLevel = '16';
        } else {
            // Jika belum mencapai batas level_wilayah, teruskan drill-down standard (0, 4, 7, 10)
            $currentLevel = (string)$len;
        }

        // 3. Eksekusi Blok Query Utama
        if (isset($levelConfig[$currentLevel])) {
            $config = $levelConfig[$currentLevel];

            $data->select($config['select'])
                ->where($config['where'][0], $config['where'][1])
                ->where('k.is_show', true)
                ->groupBy($config['groupBy']);
        } else {
            return null;
        }
        // dd($data->findAll());

        // --- Sisa Filter Modul Tetap Dipertahankan ---
        if ($isEdit) {
            $data = $data->where('LENGTH(konfirmasi) > 0');
        } else {
            $data = $data->where('LENGTH(konfirmasi) = 0');
        }

        if ($kode_anomali) {
            $data = $data->where('id_kategori_anomali', $kode_anomali);
        }

        if ($flag) {
            $data = $data->where('k.flag', $flag);
        }

        if ($levelAnomali) {
            $data = $data->where('k.level_anomali', $levelAnomali);
        }

        $data = $data->where('k.id_kegiatan', $id_kegiatan);

        if ($isRoleMitra) {
            $data->groupStart()
                ->where('wt.id_ppl', $idUser)
                ->orWhere('wt.id_pml', $idUser)
                ->groupEnd();
        }

        return $data->findAll();
    }

    // mendapatkan daftar anomali.
    public function getListAnomali($idArt = false, $isEdit = false)
    {
        $query = $this->builder();
        $idKegiatan = session()->get('aktif_kegiatan') ?? null;


        $query->select('anomali.id AS id, art.kd_assigment AS id_assigment, k.kode_anomali AS kdAnom, k.detil_anomali AS detilAnom, anomali.konfirmasi, anomali.is_lap AS is_lap')
            ->join('assigment art', 'art.id = anomali.id_assigment', 'left')
            ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali', 'left')
            ->join('wilayah_tugas wt', 'wt.id_wilayah = anomali.id_wilayah AND wt.id_kegiatan = k.id_kegiatan', 'left')
            ->where('k.is_show', true)
            ->where('art.kd_assigment', $idArt); // Mencocokkan string gabungan dengan underscore secara presisi

        // Filter status konfirmasi (Sudah diedit / Belum)
        if ($isEdit) {
            $query->where('LENGTH(konfirmasi) > 0');
        } else {
            $query->where('LENGTH(konfirmasi) = 0');
        };

        // Filter berdasarkan sub-kegiatan sensus ekonomi yang aktif
        if ($idKegiatan) {
            $query = $query->where('k.id_kegiatan', $idKegiatan);
        };

        $data  = $query->get()->getResultArray();

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
        $idKegiatan = session()->get('aktif_kegiatan') ?? null;

        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');

        $joinKatAnomali->select('COUNT(*) as jumlah_total')
            ->select("SUM(CASE WHEN kategori_anomali.is_show = 1 THEN 1 ELSE 0 END) as jumlah_public")
            ->select("SUM(CASE WHEN kategori_anomali.is_show = 0 THEN 1 ELSE 0 END) as jumlah_non_public");
        if ($idKat) {
            $joinKatAnomali->where('anomali.id_kategori_anomali', $idKat);
        }

        $joinKatAnomali->where('kategori_anomali.id_kegiatan', $idKegiatan);
        $hasil = $joinKatAnomali->findAll();
        return ($hasil);
    }
    public function jumlahProses($jenis = "all", $idKat = null, $idWilayah = null)
    {
        $joinKatAnomali = $this->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali');
        $idKegiatan = session('aktif_kegiatan');

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
            $joinKatAnomali->whereIn('kategori_anomali.level_anomali', ['0000', '1300', $idWilayah]);
        }
        if ($idKegiatan) {
            $joinKatAnomali->where('kategori_anomali.id_kegiatan', $idKegiatan);
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

    // mendapatkan anomlai berdasarkan assigment
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
            $data->whereIn('k.level_anomali', ['0000', '1300', $wilayah_kerja]);
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
            $data->whereIn('k.level_anomali', ['0000', '1300', $wilayah_kerja]);
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
            $data->whereIn('k.level_anomali', ['0000', '1300', $wilayah_kerja]);
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
