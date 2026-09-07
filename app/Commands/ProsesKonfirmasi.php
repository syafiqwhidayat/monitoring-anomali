<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\AnomaliModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProsesKonfirmasi extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'proses:konfirmasi';
    protected $description = 'Memproses unggahan Excel tanggapan lapangan dengan penyesuaian kolom isLap dan konfirmasi.';
    protected $usage       = 'proses:konfirmasi [namaFile] [logId] [kodeKabOtoritas]';

    protected $logModel;
    protected $anomaliModel;
    protected $db;

    public function run(array $params)
    {
        $fileName = $params[0] ?? null;
        $logId    = $params[1] ?? null;
        $idKab    = $params[2] ?? null; // Kode BPS Kabupaten user, misal: 1311

        if (!$fileName || !$logId || !$idKab) {
            CLI::error("Parameter kurang lengkap! Dibutuhkan: namaFile, logId, dan kodeKabOtoritas.");
            return;
        }

        $this->logModel     = new LogUploadModel();
        $this->anomaliModel = new AnomaliModel();
        $this->db           = \Config\Database::connect();

        $this->logModel->update($logId, ['status' => 'proses']);

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            if (!file_exists($filePath)) {
                throw new \Exception("File tidak ditemukan di direktori uploads.");
            }

            // MEREDAM ERROR DOM DOCUMENT PADA FILE HTML/XML
            if (function_exists('libxml_use_internal_errors')) {
                libxml_use_internal_errors(true);
            }

            $spreadsheet = IOFactory::load($filePath);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();

            if (function_exists('libxml_clear_errors')) {
                libxml_clear_errors();
            }

            $totalBaris   = count($sheetData) - 1;
            $berhasil     = 0;
            $gagal        = 0;
            $errorDetails = [];
            $batchUpdateData = [];

            // ========================================================
            // OPTIMASI 1: MAPPING DATA DATABASE DALAM CHUNK (MEMORI SAFE)
            // ========================================================
            $allIdsInExcel = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                $idAnomali = trim((string)($sheetData[$i][0] ?? ''));
                if (!empty($idAnomali)) {
                    $allIdsInExcel[] = $idAnomali;
                }
            }

            $mappedAnomali = [];
            if (!empty($allIdsInExcel)) {
                // Pecah kueri whereIn menjadi max 500 ID per batch agar database tidak crash/limit
                $chunks = array_chunk(array_unique($allIdsInExcel), 500);
                foreach ($chunks as $chunkIds) {
                    $dbData = $this->db->table('anomali')
                        ->select('id, id_wilayah, konfirmasi')
                        ->whereIn('id', $chunkIds)
                        ->get()
                        ->getResultArray();

                    foreach ($dbData as $rowDb) {
                        $mappedAnomali[$rowDb['id']] = $rowDb;
                    }
                }
            }
            // ========================================================

            $this->db->transStart();

            // Loop utama data Excel
            for ($i = 1; $i < count($sheetData); $i++) {
                $row    = $sheetData[$i];
                $rowNum = $i + 1;

                // PASTIKAN INDEKS KOLOM SESUAI DENGAN FILE EXCEL UPLOAD ANDA
                // Contoh jika struktur Excel: 
                // Col 0: ID Anomali
                // Col 5: Is_Lap (0 atau 1)
                // Col 6: Konfirmasi / Jawaban
                $idAnomali  = trim((string)($row[0] ?? ''));
                $isLapRaw   = trim((string)($row[5] ?? ''));
                $konfirmasi = trim((string)($row[6] ?? ''));

                if (empty($idAnomali)) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: -",
                        'messages' => ["ID Anomali Kosong pada baris ini."]
                    ];
                    $gagal++;
                    continue;
                }

                // GUNAKAN strlen() BUKAN empty() agar angka '0' tidak dianggap kosong!
                if (strlen($isLapRaw) === 0 && strlen($konfirmasi) === 0) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Kolom 'Apakah Kondisi Lapangan' dan 'Konfirmasi' keduanya kosong."]
                    ];
                    $gagal++;
                    continue;
                }

                // VALIDASI VALUE isLap (Harus '1' atau '0')
                if ($isLapRaw !== '0' && $isLapRaw !== '1') {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Gagal! Kolom isLap berkode '" . ($isLapRaw !== '' ? $isLapRaw : 'NULL') . "' tidak valid. Harus bernilai 1 (True) atau 0 (False)."]
                    ];
                    $gagal++;
                    continue;
                }

                $dbIsLap = ($isLapRaw === '1') ? 1 : 0;

                // Cek ketersediaan di DB Map
                if (!isset($mappedAnomali[$idAnomali])) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["ID Anomali tidak ditemukan di database."]
                    ];
                    $gagal++;
                    continue;
                }

                $dataExisting = $mappedAnomali[$idAnomali];

                // Validasi Otoritas Wilayah
                $kabWilayahAnomali = substr($dataExisting['id_wilayah'], 0, 4);
                if ($kabWilayahAnomali !== $idKab) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Gagal! ID berada di luar wilayah otoritas Anda (Wilayah data: " . $kabWilayahAnomali . ")."]
                    ];
                    $gagal++;
                    continue;
                }

                // Validasi jika konfirmasi sudah pernah diisi
                if (!empty($dataExisting['konfirmasi']) && trim($dataExisting['konfirmasi']) !== '-') {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Gagal! Data konfirmasi di database sudah terisi sebelumnya."]
                    ];
                    $gagal++;
                    continue;
                }

                // Masukkan ke antrean batch update
                $batchUpdateData[] = [
                    'id'           => $idAnomali,
                    'konfirmasi'   => $konfirmasi,
                    'is_lap'       => $dbIsLap,
                    'date_konfirmasi' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s'),
                ];
                $berhasil++;
            }

            // EXEKUSI BATCH UPDATE DALAM CHUNK (Mencegah Query Limit)
            if (!empty($batchUpdateData)) {
                $batchChunks = array_chunk($batchUpdateData, 500);
                foreach ($batchChunks as $bChunk) {
                    $this->db->table('anomali')->updateBatch($bChunk, 'id');
                }
            }

            $this->db->transComplete();

            // Simpan Log Selesai
            $logFinalData = [
                'status'        => 'selesai',
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ];
            $this->logModel->update($logId, $logFinalData);

            CLI::write("Proses konfirmasi selesai. Berhasil: $berhasil, Gagal: $gagal", 'green');
        } catch (\Throwable $th) {
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
            }

            CLI::error("Sistem Berhenti: " . $th->getMessage());

            $this->logModel->update($logId, [
                'status'        => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => [$th->getMessage()]]])
            ]);
        }
    }
}
