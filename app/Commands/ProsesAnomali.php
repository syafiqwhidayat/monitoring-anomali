<?php

namespace App\Services;

use App\Models\AnomaliModel;
use App\Models\AssigmentModel;
use App\Models\KategoriAnomaliModel;
use App\Models\KegiatanModel;

class ImportService
{
    protected $idKegiatan;
    protected $kegiatanModel;
    protected $assigmentModel;
    protected $anomaliModel;
    protected $kategoriAnomaliModel;
    protected $db;

    protected $mappedKategori = [];
    protected $mappedAssigment = [];

    public function __construct($idKegiatan)
    {
        $this->idKegiatan = $idKegiatan;
        $this->kegiatanModel = new KegiatanModel();
        $this->assigmentModel = new AssigmentModel();
        $this->anomaliModel = new AnomaliModel();
        $this->kategoriAnomaliModel = new KategoriAnomaliModel();
        $this->db = \Config\Database::connect();

        // Pre-load master kategori anomali sekali di awal (In-Memory Lookup)
        $this->loadKategoriToMap();
    }

    private function loadKategoriToMap()
    {
        $kategoriList = $this->kategoriAnomaliModel
            ->where('id_kegiatan', $this->idKegiatan)
            ->findAll();

        foreach ($kategoriList as $kat) {
            $this->mappedKategori[trim($kat['kode_anomali'])] = $kat;
        }
    }

    private function loadAssigmentByKabToMap($kdKab)
    {
        $assigmentList = $this->assigmentModel
            ->where('id_kegiatan', $this->idKegiatan)
            ->like('id_wilayah', $kdKab, 'after')
            ->findAll();

        $this->mappedAssigment = [];
        foreach ($assigmentList as $ass) {
            $this->mappedAssigment[$ass['kd_assigment']] = $ass;
        }
    }

    public function processUpload($dataSheet, $levelKegiatan, $levelWilayah)
    {
        // 1. Grouping Data Berdasarkan Kabupaten (Optimasi per Kabupaten)
        $groupedData = [];
        foreach ($dataSheet as $rowNum => $row) {
            if ($rowNum === 1 || empty(array_filter($row))) {
                continue; // Skip header & baris kosong
            }

            $kdProv = sprintf("%02d", (int)($row[0] ?? 0));
            $kdKab  = sprintf("%02d", (int)($row[1] ?? 0));
            $kdKabFull = $kdProv . $kdKab;

            $groupedData[$kdKabFull][] = [
                'rowNum' => $rowNum,
                'row'    => $row
            ];
        }

        $berhasil = 0;
        $gagal = 0;
        $errorDetails = [];

        // 2. Transaksi Utama per Kabupaten
        foreach ($groupedData as $kdKab => $items) {
            $this->db->transBegin();

            try {
                // A. Reset Flag Status Anomali Existing di Kabupaten ini (Single Query)
                $this->db->table('anomali')
                    ->join('assignment a', 'a.id = anomali.id_assigment')
                    ->where('a.id_kegiatan', $this->idKegiatan)
                    ->where('LEFT(anomali.id_wilayah, 4)', $kdKab)
                    ->update(['is_insert' => 0]);

                // B. Load Master Wilayah Kabupaten ke In-Memory Array (O(1) Lookup)
                $validWilayahList = $this->db->table('wilayah')
                    ->select('id')
                    ->where('LEFT(id, 4)', $kdKab)
                    ->get()
                    ->getResultArray();
                $mapValidWilayah = array_flip(array_column($validWilayahList, 'id'));

                // C. Load Master Assignment Existing Kabupaten ke Memory
                $this->loadAssigmentByKabToMap($kdKab);

                // D. Load Master Anomali Existing Kabupaten ke Memory: [id_assigment][id_kategori] = id_anomali
                $existingAnomali = $this->db->table('anomali')
                    ->select('anomali.id, anomali.id_assigment, anomali.id_kategori_anomali')
                    ->join('assignment a', 'a.id = anomali.id_assigment')
                    ->where('a.id_kegiatan', $this->idKegiatan)
                    ->where('LEFT(anomali.id_wilayah, 4)', $kdKab)
                    ->get()
                    ->getResultArray();

                $mapExistingAnomali = [];
                foreach ($existingAnomali as $ea) {
                    $mapExistingAnomali[$ea['id_assigment']][$ea['id_kategori_anomali']] = $ea['id'];
                }

                $batchNewAssignment = [];
                $validItems = [];

                // E. Validasi Cepat & Parsing Data di Memory
                foreach ($items as $entry) {
                    $rowNum = $entry['rowNum'];
                    $row    = $entry['row'];

                    // Extraction
                    $kdProv = sprintf("%02d", (int)($row[0] ?? 0));
                    $kdKab  = sprintf("%02d", (int)($row[1] ?? 0));
                    $kdKec  = sprintf("%03d", (int)($row[2] ?? 0));
                    $kdDesa = sprintf("%03d", (int)($row[3] ?? 0));
                    $kdSls  = sprintf("%06d", (int)($row[4] ?? 0));

                    if ($levelKegiatan === 'RT') {
                        $nurt   = sprintf("%04d", (int)($row[5] ?? 0));
                        $nuart  = sprintf("%03d", (int)($row[6] ?? 0));
                        $idAss  = $kdProv . $kdKab . $kdKec . $kdDesa . $kdSls . $nurt . $nuart;
                        $idWil  = substr($idAss, 0, $levelWilayah);

                        $data = [
                            'kode_prov'    => $kdProv,
                            'kode_kab'     => $kdKab,
                            'kode_kec'     => $kdKec,
                            'kode_desa'    => $kdDesa,
                            'kode_sls'     => $kdSls,
                            'nurt'         => $nurt,
                            'nuart'        => $nuart,
                            'anomali'      => trim((string)($row[9] ?? '')),
                            'nama_krt'     => trim((string)($row[7] ?? '')),
                            'nama_art'     => trim((string)($row[8] ?? '')),
                            'id_wilayah'   => $idWil,
                            'id_assigment' => $idAss,
                        ];
                    } else { // NRT / SLS Level
                        $kodeNurt = sprintf("%04d", (int)($row[5] ?? 0));
                        $idAss    = $kdProv . $kdKab . $kdKec . $kdDesa . $kdSls . $kodeNurt;
                        $idWil    = substr($idAss, 0, $levelWilayah);

                        $data = [
                            'kode_prov'    => $kdProv,
                            'kode_kab'     => $kdKab,
                            'kode_kec'     => $kdKec,
                            'kode_desa'    => $kdDesa,
                            'kode_sls'     => $kdSls,
                            'kode_nurt'    => $kodeNurt,
                            'anomali'      => trim((string)($row[7] ?? '')),
                            'nama_nrt'     => trim((string)($row[6] ?? '')),
                            'id_wilayah'   => $idWil,
                            'id_assigment' => $idAss,
                        ];
                    }

                    // Native Validation (Hindari CI Validation Overhead)
                    $errors = [];
                    if (empty($data['anomali'])) {
                        $errors[] = 'Kolom anomali wajib diisi';
                    }
                    if (strlen($data['id_wilayah']) !== (int)$levelWilayah) {
                        $errors[] = "Panjang ID Wilayah tidak sesuai ({$levelWilayah} digit)";
                    }
                    if (!isset($mapValidWilayah[$data['id_wilayah']])) {
                        $errors[] = 'ID Wilayah tidak terdaftar di master wilayah';
                    }

                    if (!empty($errors)) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => $data['id_assigment'],
                            'messages' => $errors,
                        ];
                        $gagal++;
                        continue;
                    }

                    // Kumpulkan Assignment Baru jika Belum Ada di DB & Batch Pending
                    if (!isset($this->mappedAssigment[$data['id_assigment']]) && !isset($batchNewAssignment[$data['id_assigment']])) {
                        $batchNewAssignment[$data['id_assigment']] = [
                            'kd_assigment' => $data['id_assigment'],
                            'id_wilayah'   => $data['id_wilayah'],
                            'id_kegiatan'  => $this->idKegiatan,
                            'kd_krt'       => $data['nurt'] ?? null,
                            'kd_art'       => $data['nuart'] ?? null,
                            'nm_krt'       => $data['nama_krt'] ?? null,
                            'nm_art'       => $data['nama_art'] ?? null,
                            'kd_nrt'       => $data['kode_nurt'] ?? null,
                            'nm_nrt'       => $data['nama_nrt'] ?? null,
                        ];
                    }

                    $validItems[] = $data;
                }

                // F. Bulk Insert Assignment Baru
                if (!empty($batchNewAssignment)) {
                    $chunks = array_chunk(array_values($batchNewAssignment), 1000);
                    foreach ($chunks as $chunk) {
                        $this->assigmentModel->insertBatch($chunk);
                    }
                    // Reload map assignment agar ID auto-increment dari database ter-fetch
                    $this->loadAssigmentByKabToMap($kdKab);
                }

                // G. Pemrosesan Anomali (Batch Insert & Update)
                $batchNewAnomali  = [];
                $updateAnomaliIds = [];

                foreach ($validItems as $data) {
                    $idAssigment = $this->mappedAssigment[$data['id_assigment']]['id'] ?? null;
                    if (!$idAssigment) continue;

                    $anomaliCodes = array_filter(array_map('trim', explode(',', rtrim($data['anomali'], ','))));

                    foreach ($anomaliCodes as $kodeAnom) {
                        $idKat = $this->mappedKategori[$kodeAnom]['id'] ?? null;
                        if (!$idKat) continue;

                        // Cek apakah anomali sudah ada
                        if (isset($mapExistingAnomali[$idAssigment][$idKat])) {
                            $updateAnomaliIds[] = $mapExistingAnomali[$idAssigment][$idKat];
                        } else {
                            $batchNewAnomali[] = [
                                'id_kategori_anomali' => $idKat,
                                'id_wilayah'          => $data['id_wilayah'],
                                'id_assigment'        => $idAssigment,
                                'is_insert'           => 1,
                                'konfirmasi'          => '',
                            ];
                        }
                    }

                    $berhasil++;
                }

                // H. Eksekusi Batch Insert Anomali Baru
                if (!empty($batchNewAnomali)) {
                    $chunks = array_chunk($batchNewAnomali, 1000);
                    foreach ($chunks as $chunk) {
                        $this->db->table('anomali')->insertBatch($chunk);
                    }
                }

                // I. Eksekusi Batch Update Anomali Existing (Set is_insert = 1)
                if (!empty($updateAnomaliIds)) {
                    $uniqueUpdateIds = array_unique($updateAnomaliIds);
                    $chunks = array_chunk($uniqueUpdateIds, 1000);
                    foreach ($chunks as $chunk) {
                        $this->db->table('anomali')
                            ->whereIn('id', $chunk)
                            ->update(['is_insert' => 1]);
                    }
                }

                $this->db->transCommit();
            } catch (\Throwable $e) {
                $this->db->transRollback();
                log_message('error', 'Error Upload Excel Kab ' . $kdKab . ': ' . $e->getMessage());
                throw $e;
            }
        }

        return [
            'berhasil'     => $berhasil,
            'gagal'        => $gagal,
            'errorDetails' => $errorDetails
        ];
    }
}
