<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Geo\GeoLocator;

class PetaController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = "Peta Cakupan SE";
        return view('peta/index', $data);
    }

    public function uploadView()
    {
        // Pastikan view dipanggil dengan kata kunci 'return'
        $data['title'] = "Upload Peta Cakupan";
        return view('peta/upload', $data);
    }

    // =========================================================================
    // 1. UPLOAD & TRUNCATE/REPLACE DATA BANGUNAN (CSV)
    // =========================================================================
    public function uploadCsv()
    {
        $file = $this->request->getFile('file_csv');

        if (!$file || !$file->isValid() || $file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'File harus berformat CSV!');
        }

        $handle = fopen($file->getTempName(), 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membaca file CSV.');
        }

        // 1. Baca baris pertama sebagai Header
        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            fclose($handle);
            return redirect()->back()->with('error', 'File CSV kosong atau format salah.');
        }

        // Map nama header ke index kolom (case-insensitive & trim whitespace)
        $headerMap = [];
        foreach ($header as $index => $colName) {
            $cleanName = strtolower(trim($colName));
            $headerMap[$cleanName] = $index;
        }

        // Pemetaan nama header ke kolom database
        // Key: Variabel di CSV | Value: Nama Kolom di Database
        $mapConfig = [
            'id_subsls'          => ['idsubsls', 'id_subsls'],
            'no_bang'            => ['no_bangunan', 'no_bang'],
            'nama_principal'     => ['nama_principal'],
            'geotag_latitude'    => ['latitude', 'geotag_latitude', 'lat'],
            'geotag_longitude'   => ['longitude', 'geotag_longitude', 'lng', 'long'],
            'jns_bangunan_value' => ['jns_bangunan_value', 'jns_bangunan', 'jenis_bangunan']
        ];

        // Cari indeks masing-masing kolom berdasarkan header CSV
        $colIdx = [];
        foreach ($mapConfig as $dbKey => $possibleNames) {
            $found = null;
            foreach ($possibleNames as $name) {
                if (isset($headerMap[$name])) {
                    $found = $headerMap[$name];
                    break;
                }
            }
            $colIdx[$dbKey] = $found;
        }

        $insertBatch = [];
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Ambil idsubsls; lewati jika kosong
            $idsubsls = isset($colIdx['id_subsls']) && isset($row[$colIdx['id_subsls']])
                ? trim($row[$colIdx['id_subsls']])
                : null;

            if (empty($idsubsls)) {
                continue;
            }

            $insertBatch[] = [
                'idsubsls'           => $idsubsls,
                'no_bangunan'        => isset($colIdx['no_bang']) && isset($row[$colIdx['no_bang']]) ? trim($row[$colIdx['no_bang']]) : '',
                'nama_principal'     => isset($colIdx['nama_principal']) && isset($row[$colIdx['nama_principal']]) ? trim($row[$colIdx['nama_principal']]) : '',
                'latitude'           => isset($colIdx['geotag_latitude']) && isset($row[$colIdx['geotag_latitude']]) ? (float) trim($row[$colIdx['geotag_latitude']]) : 0,
                'longitude'          => isset($colIdx['geotag_longitude']) && isset($row[$colIdx['geotag_longitude']]) ? (float) trim($row[$colIdx['geotag_longitude']]) : 0,
                'jns_bangunan_value' => isset($colIdx['jns_bangunan_value']) && isset($row[$colIdx['jns_bangunan_value']]) && $row[$colIdx['jns_bangunan_value']] !== '' ? trim($row[$colIdx['jns_bangunan_value']]) : null,
            ];
        }

        fclose($handle);

        if (empty($insertBatch)) {
            return redirect()->back()->with('error', 'Tidak ada baris data valid yang diimpor.');
        }

        // Kosongkan tabel dan simpan data baru
        $this->db->table('se_bangunan')->truncate();
        $this->db->table('se_bangunan')->insertBatch($insertBatch);

        return redirect()->back()->with('success', 'Berhasil mengunggah ' . count($insertBatch) . ' titik bangunan.');
    }

    public function uploadGeojson()
    {
        $file = $this->request->getFile('file_geojson');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File GeoJSON tidak valid.');
        }

        $content = file_get_contents($file->getTempName());
        $json = json_decode($content, true);

        if (!$json || !isset($json['features'])) {
            return redirect()->back()->with('error', 'Format GeoJSON salah atau tidak memiliki daftar "features".');
        }

        $inserted = 0;
        $this->db->transStart();

        foreach ($json['features'] as $feature) {
            // Ambil idsubsls dari properties (Sesuaikan key jika di file Anda menggunakan ID_SUBSLS / id_subsls / id)
            $props = $feature['properties'] ?? [];
            $idSubSls = $props['idsubsls'] ?? $props['ID_SUBSLS'] ?? $props['id'] ?? null;

            if ($idSubSls) {
                // Bungkus per feature sebagai objek GeoJSON mandiri
                $singleGeoJson = json_encode([
                    'type'       => 'FeatureCollection',
                    'features'   => [$feature]
                ]);

                // Simpan atau Timpa (UPSERT)
                $this->db->table('wilayah_geojson')->replace([
                    'idsubsls'   => trim($idSubSls),
                    'geojson'    => $singleGeoJson,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $inserted++;
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal menyimpan GeoJSON ke database.');
        }

        return redirect()->back()->with('success', "Berhasil memproses $inserted poligon Sub SLS ke database!");
    }

    // =========================================================================
    // 2. DROPDOWN WILAYAH (CASCADING)
    // =========================================================================
    public function getKabupaten()
    {
        // Filter langsung di level query database
        $builder = $this->db->table('wilayah'); // Sesuaikan nama tabel Anda
        $builder->select('kd_kab, nm_kab');
        $builder->where('kd_kab !=', '');
        $builder->where('kd_kab !=', '00');
        $builder->where('nm_kab !=', '-');
        $builder->where('nm_kab !=', '');
        $builder->groupBy('kd_kab, nm_kab');
        $builder->orderBy('kd_kab', 'ASC');

        $kabupaten = $builder->get()->getResultArray();

        return $this->response->setJSON($kabupaten);
    }

    public function getKecamatan()
    {
        $kdKab = $this->request->getGet('kd_kab');
        $data = $this->db->table('wilayah')
            ->select('kd_kec, nm_kec')
            ->where('kd_kab', $kdKab)
            ->where('kd_kec !=', '')
            ->where('kd_kec !=', '000')
            ->groupBy('kd_kec, nm_kec')
            ->orderBy('kd_kec', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    public function getDesa()
    {
        $kdKab = $this->request->getGet('kd_kab');
        $kdKec = $this->request->getGet('kd_kec');
        $data = $this->db->table('wilayah')
            ->select('kd_des, nm_des')
            ->where('kd_kab', $kdKab)
            ->where('kd_kec', $kdKec)
            ->where('kd_des !=', '')
            ->where('kd_des !=', '000')
            ->groupBy('kd_des, nm_des')
            ->orderBy('kd_des', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    public function getSls()
    {
        $kdKab = $this->request->getGet('kd_kab');
        $kdKec = $this->request->getGet('kd_kec');
        $kdDes = $this->request->getGet('kd_des');
        $data = $this->db->table('wilayah')
            ->select('kd_sls, nm_sls')
            ->where('kd_kab', $kdKab)
            ->where('kd_kec', $kdKec)
            ->where('kd_des', $kdDes)
            ->where('kd_sls !=', '')
            ->where('nm_sls !=', '')
            ->where('kd_sls !=', '0000')
            ->groupBy('kd_sls, nm_sls')
            ->orderBy('kd_sls', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    public function getSubSls()
    {
        $kdKab = $this->request->getGet('kd_kab');
        $kdKec = $this->request->getGet('kd_kec');
        $kdDes = $this->request->getGet('kd_des');
        $kdSls = $this->request->getGet('kd_sls');

        // Perbaikan: Ambil kolom 'idsubsls' agar nilainya valid saat dikirim ke client
        $data = $this->db->table('wilayah')
            ->select('id, kd_subsls, nm_sls')
            ->where('kd_kab', $kdKab)
            ->where('kd_kec', $kdKec)
            ->where('kd_des', $kdDes)
            ->where('kd_sls', $kdSls)
            ->where('kd_subsls !=', '')
            // Buang baris agregat "seluruh SLS" (kd_subsls = 00): bukan Sub SLS nyata.
            ->where('kd_subsls !=', '00')
            ->orderBy('kd_subsls', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    // =========================================================================
    // 3. FETCH MAP DATA (GEOJSON POLYGON + POINT BANGUNAN BY SUB SLS)
    // =========================================================================
    public function getDataMap($idSubSls)
    {
        $idSubSls = (string)$idSubSls;

        // Bangunan & poligon diambil terpisah dan masing-masing dibungkus try/catch.
        // Kalau salah satu query melempar, bagian yang lain tetap bisa ditampilkan.
        $bangunan = [];
        $errBangunan = null;
        try {
            $bangunan = $this->db->table('se_bangunan')
                ->where('idsubsls', $idSubSls)
                // Buang baris tanpa geotag: kolom lat/long 0 akan membuat marker
                // tergambar di titik (0, 0) Samudra Atlantik.
                ->where('latitude !=', 0)
                ->where('longitude !=', 0)
                ->get()->getResultArray();
        } catch (\Throwable $e) {
            $errBangunan = 'Data titik bangunan tidak dapat dimuat.';
        }

        $geojsonRaw = null;
        $errGeo = null;
        try {
            $rowGeo = $this->db->table('wilayah_geojson')
                ->where('idsubsls', $idSubSls)
                ->get()->getRowArray();
            $geojsonRaw = $rowGeo ? json_decode($rowGeo['geojson']) : null;
        } catch (\Throwable $e) {
            $errGeo = 'Data batas wilayah tidak dapat dimuat.';
        }

        // Metadata wilayah untuk panel hasil & popup
        $info = null;
        try {
            $info = $this->db->table('wilayah')
                ->select('id, kd_kab, nm_kab, kd_kec, nm_kec, kd_des, nm_des, kd_sls, nm_sls, kd_subsls')
                ->where('id', $idSubSls)
                ->get()->getRowArray();
        } catch (\Throwable $e) {
            $info = null;
        }

        // Pesan untuk kondisi kosong / gagal, diisi di server agar UI tidak
        // pernah menampilkan peta kosong tanpa penjelasan.
        $pesan = null;
        if ($errGeo !== null) {
            $pesan = $errGeo;
        } elseif ($geojsonRaw === null) {
            $pesan = 'Batas wilayah untuk Sub SLS ini belum tersedia.';
        } elseif ($errBangunan !== null) {
            $pesan = $errBangunan;
        } elseif (count($bangunan) === 0) {
            $pesan = 'Belum ada titik bangunan untuk Sub SLS ini.';
        }

        return $this->response->setJSON([
            'status'          => 'success',
            'idsubsls'        => $idSubSls,
            'geojson'         => $geojsonRaw,
            'bangunan'        => $bangunan,
            'jumlah_bangunan' => count($bangunan),
            'info'            => $info,
            'pesan'           => $pesan,
        ]);
    }

    // =========================================================================
    // 4. IDENTIFIKASI SUB SLS DARI TITIK / KLIK PETA
    // =========================================================================

    /**
     * Cari Sub SLS yang memuat titik tertentu (klik peta atau lokasi GPS).
     * GET peta/locate-subsls?lat=-0.73342560&lng=100.26935420
     */
    public function locateSubSls()
    {
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'found'  => false,
                    'pesan'  => 'Koordinat tidak valid.',
                ]);
        }

        $lat = (float)$lat;
        $lng = (float)$lng;

        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'found'  => false,
                    'pesan'  => 'Koordinat berada di luar rentang dunia.',
                ]);
        }

        try {
            $rows = $this->db->table('wilayah_geojson')
                ->select('idsubsls, geojson')
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'found'  => false,
                    'pesan'  => 'Data batas wilayah tidak dapat dimuat.',
                ]);
        }

        // Tahap 1: index bbox + luas. Tahap 2: uji containment pada kandidat bbox.
        $index = GeoLocator::buildIndex($rows);
        $hasil = GeoLocator::findContaining($index, $rows, $lng, $lat);

        if ($hasil['found'] === false) {
            return $this->response->setJSON([
                'status'          => 'success',
                'found'           => false,
                'idsubsls'        => null,
                'geojson'         => null,
                'bangunan'        => [],
                'jumlah_bangunan' => 0,
                'jumlah_kandidat' => $hasil['jumlah_kandidat'],
                'lat'             => $lat,
                'lng'             => $lng,
                'pesan'           => 'Titik tersebut berada di luar area yang memiliki data batas wilayah.',
            ]);
        }

        $idsubsls = (string)$hasil['idsubsls'];

        $info = null;
        try {
            $info = $this->db->table('wilayah')
                ->select('id, kd_kab, nm_kab, kd_kec, nm_kec, kd_des, nm_des, kd_sls, nm_sls, kd_subsls')
                ->where('id', $idsubsls)
                ->get()
                ->getRowArray();
        } catch (\Throwable $e) {
            $info = null;
        }

        $bangunan = [];
        try {
            $bangunan = $this->db->table('se_bangunan')
                ->where('idsubsls', $idsubsls)
                ->where('latitude !=', 0)
                ->where('longitude !=', 0)
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            $bangunan = [];
        }

        return $this->response->setJSON([
            'status'          => 'success',
            'found'           => true,
            'idsubsls'        => $idsubsls,
            'geojson'         => $hasil['geojson'],
            'bangunan'        => $bangunan,
            'jumlah_bangunan' => count($bangunan),
            'jumlah_kandidat' => $hasil['jumlah_kandidat'],
            'info'            => $info,
            'lat'             => $lat,
            'lng'             => $lng,
            'pesan'           => count($bangunan) === 0
                ? 'Belum ada titik bangunan untuk Sub SLS ini.'
                : null,
        ]);
    }

    // =========================================================================
    // 5. BATAS WILAYAH DI LEVEL SLS / DESA / KECAMATAN
    // =========================================================================

    /**
     * Ambil outline gabungan dari Sub SLS anak sebuah SLS/Desa/Kecamatan.
     * GET peta/get-batas/{level}/{kode}?kd_kab=..&kd_kec=..&kd_des=..
     *
     * Bila outline gabungan tidak valid (data.poligon saling tumpang tindih),
     * dikirimkan poligon-poligon anak apa adanya - client tidak perlu tahu
     * mode mana yang dipakai, keduanya GeoJSON biasa.
     */
    public function getBatasWilayah($level = 'desa', $kode = null)
    {
        $level = (string)$level;
        $kode = (string)$kode;

        $kdKab = (string)$this->request->getGet('kd_kab');
        $kdKec = (string)$this->request->getGet('kd_kec');
        $kdDes = (string)$this->request->getGet('kd_des');

        $builder = $this->db->table('wilayah')
            ->select('id')
            ->where('kd_subsls !=', '')
            ->where('kd_subsls !=', '00');

        switch ($level) {
            case 'kecamatan':
                $builder->where('kd_kab', $kdKab)->where('kd_kec', $kode);
                break;
            case 'desa':
                $builder->where('kd_kab', $kdKab)->where('kd_kec', $kdKec)->where('kd_des', $kode);
                break;
            case 'sls':
                $builder->where('kd_kab', $kdKab)->where('kd_kec', $kdKec)->where('kd_des', $kdDes)->where('kd_sls', $kode);
                break;
            default:
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => 'error',
                        'pesan'  => 'Level batas wilayah tidak dikenal.',
                    ]);
        }

        if ($kdKab === '' || ($level === 'desa' && $kdKec === '') || ($level === 'sls' && ($kdKec === '' || $kdDes === ''))) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'pesan'  => 'Kode kabupaten/kecamatan/desa diperlukan untuk menyusun batas wilayah.',
                ]);
        }

        $ids = array_column($builder->get()->getResultArray(), 'id');

        if ($ids === []) {
            return $this->response->setJSON([
                'status'         => 'success',
                'level'          => $level,
                'kode'           => $kode,
                'mode'           => 'group',
                'jumlah_poligon' => 0,
                'geojson'        => ['type' => 'FeatureCollection', 'features' => []],
                'pesan'          => 'Batas wilayah untuk level ini belum tersedia.',
            ]);
        }

        try {
            $rows = $this->db->table('wilayah_geojson')
                ->select('idsubsls, geojson')
                ->whereIn('idsubsls', $ids)
                ->get()
                ->getResultArray();
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'pesan'  => 'Data batas wilayah tidak dapat dimuat.',
                ]);
        }

        $union = GeoLocator::unionOutline($rows);

        if ($union !== null) {
            $geojson = [
                'type'     => 'FeatureCollection',
                'features' => [[
                    'type'       => 'Feature',
                    'properties' => ['level' => $level, 'kode' => $kode],
                    'geometry'  => ['type' => 'Polygon', 'coordinates' => $union],
                ]],
            ];
            $mode = 'union';
        } else {
            // Fallback: poligon anak apa adanya.
            $features = [];
            foreach ($rows as $row) {
                $geo = json_decode((string)$row['geojson'], true);
                if (!is_array($geo)) {
                    continue;
                }
                foreach (($geo['features'] ?? []) as $feature) {
                    $features[] = $feature;
                }
            }
            $geojson = ['type' => 'FeatureCollection', 'features' => $features];
            $mode = 'group';
        }

        return $this->response->setJSON([
            'status'         => 'success',
            'level'          => $level,
            'kode'           => $kode,
            'mode'           => $mode,
            'jumlah_poligon' => count($geojson['features']),
            'geojson'        => $geojson,
            'pesan'          => null,
        ]);
    }
}
