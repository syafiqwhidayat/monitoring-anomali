<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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
            ->select('id, kd_subsls')
            ->where('kd_kab', $kdKab)
            ->where('kd_kec', $kdKec)
            ->where('kd_des', $kdDes)
            ->where('kd_sls', $kdSls)
            ->where('kd_subsls !=', '')
            ->orderBy('kd_subsls', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    // =========================================================================
    // 3. FETCH MAP DATA (GEOJSON POLYGON + POINT BANGUNAN BY SUB SLS)
    // =========================================================================
    public function getDataMap($idSubSls)
    {
        // Data Titik dari se_bangunan
        $bangunan = $this->db->table('se_bangunan')
            ->where('idsubsls', $idSubSls)
            ->get()->getResultArray();

        // Data Poligon dari wilayah_geojson
        $rowGeo = $this->db->table('wilayah_geojson')
            ->where('idsubsls', $idSubSls)
            ->get()->getRowArray();

        $geojsonRaw = $rowGeo ? json_decode($rowGeo['geojson']) : null;

        return $this->response->setJSON([
            'status'   => 'success',
            'geojson'  => $geojsonRaw,
            'bangunan' => $bangunan
        ]);
    }
}
