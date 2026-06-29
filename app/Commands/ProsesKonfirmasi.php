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
            $filePath    = WRITEPATH . 'uploads/' . $fileName;
            if (!file_exists($filePath)) {
                throw new \Exception("File tidak ditemukan di direktori uploads.");
            }

            // MEREDAM ERROR DOM DOCUMENT PADA FILE HTML/XML ---
            if (function_exists('libxml_use_internal_errors')) {
                libxml_use_internal_errors(true);
            }

            // Proses load file
            $spreadsheet = IOFactory::load($filePath);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();

            // BERSIHKAN KEMBALI BUFFER ERROR LIBXML ---
            if (function_exists('libxml_clear_errors')) {
                libxml_clear_errors();
            }

            $totalBaris   = count($sheetData) - 1;
            $berhasil     = 0;
            $gagal        = 0;
            $errorDetails = [];

            $batchUpdateData = [];

            // ==========================================
            // OPTIMASI 1: AMBIL SEMUA ID ANOMALI DI AWAL
            // ==========================================
            $allIdsInExcel = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                $idAnomali = trim($sheetData[$i][0] ?? '');
                if (!empty($idAnomali)) {
                    $allIdsInExcel[] = $idAnomali;
                }
            }

            $mappedAnomali = [];
            if (!empty($allIdsInExcel)) {
                $dbData = $this->db->table('anomali')
                    ->select('id, id_wilayah, konfirmasi')
                    ->whereIn('id', $allIdsInExcel)
                    ->get()
                    ->getResultArray();

                foreach ($dbData as $rowDb) {
                    $mappedAnomali[$rowDb['id']] = $rowDb;
                }
            }
            // ==========================================

            // Mulai transaksi data
            $this->db->transStart();

            // Loop utama data Excel
            for ($i = 1; $i < count($sheetData); $i++) {
                $row    = $sheetData[$i];
                $rowNum = $i + 1;

                $idAnomali  = trim($row[0] ?? '');
                $isLapRaw   = trim($row[5] ?? ''); // Kolom Isian Baru (isLap)
                $konfirmasi = trim($row[6] ?? ''); // Kolom Konfirmasi bergeser ke indeks 6

                if (empty($idAnomali)) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["ID Anomali Kosong"]
                    ];
                    $gagal++;
                    continue;
                }

                if (empty($isLapRaw) && empty($konfirmasi)) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Apakah Kondisi Lapangan Kosong atau Konfirmasi Kosong"]
                    ];
                    $gagal++;
                    continue; // Lewati jika isian konfirmasi kosong
                }

                // VALIDASI ATURAN BARU: Cek kolom isLap (Harus bernilai 1 atau 2)
                if ($isLapRaw !== '1' && $isLapRaw !== '2') {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Gagal! Kolom isLap berkode '" . ($isLapRaw ?: 'NULL') . "' tidak valid. Harus bernilai 1 (True) atau 2 (False)."]
                    ];
                    $gagal++;
                    continue;
                }

                // Konversi nilai mentah Excel ke boolean/tinyint database
                $dbIsLap = ($isLapRaw === '1') ? 1 : 0;

                // Cek data existing dari array map di memori
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

                // Validasi Otoritas Wilayah Kabupaten
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

                // Validasi Konflik Isian Data Terisi
                if (!empty($dataExisting['konfirmasi']) && trim($dataExisting['konfirmasi']) !== '-') {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "ID Anomali: " . $idAnomali,
                        'messages' => ["Gagal! Data konfirmasi di database sudah terisi sebelumnya."]
                    ];
                    $gagal++;
                    continue;
                }

                // Jika lolos semua validasi, masukkan ke antrean batch update
                $batchUpdateData[] = [
                    'id'           => $idAnomali,
                    'konfirmasi'   => $konfirmasi,
                    'is_lap'       => $dbIsLap, // Menggunakan hasil konversi (1 / 0)
                    'date_updated' => date('Y-m-d H:i:s')
                ];
                $berhasil++;
            }

            // OPTIMASI: Jalankan batch update massal jika ada data yang lolos
            if (!empty($batchUpdateData)) {
                $this->db->table('anomali')->updateBatch($batchUpdateData, 'id');
            }

            $this->db->transComplete();

            // Update Ringkasan Log Upload
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

            // throw $th;
        }
    }
}
