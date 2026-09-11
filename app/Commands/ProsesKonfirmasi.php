<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\AnomaliModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;
    private $chunkSize = 0;

    public function setRows(int $startRow, int $chunkSize): void
    {
        $this->startRow  = $startRow;
        $this->chunkSize = $chunkSize;
    }

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
    {
        // Pembatasan Kolom: HANYA baca kolom A sampai G
        $allowedColumns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        if (!in_array($columnAddress, $allowedColumns, true)) {
            return false;
        }

        // Pembatasan Baris: Hanya baca baris chunk aktif
        if ($row >= $this->startRow && $row < ($this->startRow + $this->chunkSize)) {
            return true;
        }

        return false;
    }
}

class ProsesKonfirmasi extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'proses:konfirmasi';
    protected $description = 'Memproses unggahan Excel tanggapan lapangan dengan Proteksi Baris/Kolom Hantu.';
    protected $usage       = 'proses:konfirmasi [namaFile] [logId] [kodeKabOtoritas]';

    protected $logModel;
    protected $anomaliModel;
    protected $db;

    public function run(array $params)
    {
        ini_set('memory_limit', '1024M');

        $fileName = $params[0] ?? null;
        $logId    = $params[1] ?? null;
        $idKab    = $params[2] ?? null;

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

            if (function_exists('libxml_use_internal_errors')) {
                libxml_use_internal_errors(true);
            }

            $inputFileType = IOFactory::identify($filePath);

            /** @var \PhpOffice\PhpSpreadsheet\Reader\BaseReader $reader */
            $reader = IOFactory::createReader($inputFileType);

            // Abaikan styling, rumus, dan format sel
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);

            $info      = $reader->listWorksheetInfo($filePath);
            $totalRows = $info[0]['totalRows'] ?? 0;

            // Batasi maksimal pindaian jika XML mengindikasikan 1 juta baris hantu
            // Ini mencegah perulangan tak terbatas
            $maxRowsToScan = min($totalRows, 100000);

            $chunkSize   = 1000;
            $chunkFilter = new ChunkReadFilter();
            $reader->setReadFilter($chunkFilter);

            $berhasil         = 0;
            $gagal            = 0;
            $errorDetails     = [];
            $totalBarisData   = 0;
            $consecutiveEmptyChunks = 0; // Penghitung chunk kosong berturut-turut

            // Iterasi mulai dari baris 2 (melewati header baris 1)
            for ($startRow = 2; $startRow <= $maxRowsToScan; $startRow += $chunkSize) {

                $chunkFilter->setRows($startRow, $chunkSize);

                // Load sheet
                $spreadsheet = $reader->load($filePath);
                $worksheet   = $spreadsheet->getActiveSheet();

                $chunkIds = [];
                $rowsData = [];
                $endRow   = $startRow + $chunkSize - 1;

                for ($rowNum = $startRow; $rowNum <= $endRow; $rowNum++) {
                    // Ambil nilai sel dengan aman
                    $cellA = $worksheet->getCell("A{$rowNum}");
                    $cellF = $worksheet->getCell("F{$rowNum}");
                    $cellG = $worksheet->getCell("G{$rowNum}");

                    $idAnomali  = $cellA ? trim((string)$cellA->getValue()) : '';
                    $isLapRaw   = $cellF ? trim((string)$cellF->getValue()) : '';
                    $konfirmasi = $cellG ? trim((string)$cellG->getValue()) : '';

                    // Jika Kolom A, F, dan G semuanya kosong, lewati
                    if ($idAnomali === '' && $isLapRaw === '' && $konfirmasi === '') {
                        continue;
                    }

                    $rowsData[$rowNum] = [
                        'id_anomali' => $idAnomali,
                        'is_lap_raw' => $isLapRaw,
                        'konfirmasi' => $konfirmasi,
                    ];

                    if (!empty($idAnomali)) {
                        $chunkIds[] = $idAnomali;
                    }

                    $totalBarisData++;
                }

                // Cek jika chunk ini kosong
                if (empty($rowsData)) {
                    $consecutiveEmptyChunks++;

                    // Bersihkan memori chunk ini
                    $spreadsheet->disconnectWorksheets();
                    unset($spreadsheet, $worksheet, $rowsData, $chunkIds);
                    gc_collect_cycles();

                    // Jika 2 chunk berturut-turut (2.000 baris) kosong total, dipastikan ini area baris hantu
                    if ($consecutiveEmptyChunks >= 2) {
                        break;
                    }
                    continue;
                }

                // Reset penghitung chunk kosong jika menemukan data
                $consecutiveEmptyChunks = 0;

                // Query DB hanya untuk ID aktif di chunk ini
                $mappedAnomali = [];
                if (!empty($chunkIds)) {
                    $dbData = $this->db->table('anomali')
                        ->select('id, id_wilayah, konfirmasi')
                        ->whereIn('id', array_unique($chunkIds))
                        ->get()
                        ->getResultArray();

                    foreach ($dbData as $rowDb) {
                        $mappedAnomali[$rowDb['id']] = $rowDb;
                    }
                }

                $batchUpdateData = [];

                // Validasi data
                foreach ($rowsData as $rowNum => $row) {
                    $idAnomali  = $row['id_anomali'];
                    $isLapRaw   = $row['is_lap_raw'];
                    $konfirmasi = $row['konfirmasi'];

                    if (empty($idAnomali)) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "ID Anomali: -",
                            'messages' => ["ID Anomali Kosong pada baris ini."]
                        ];
                        $gagal++;
                        continue;
                    }

                    if (strlen($isLapRaw) === 0 && strlen($konfirmasi) === 0) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "ID Anomali: " . $idAnomali,
                            'messages' => ["Kolom 'Apakah Kondisi Lapangan' dan 'Konfirmasi' keduanya kosong."]
                        ];
                        $gagal++;
                        continue;
                    }

                    if ($isLapRaw !== '0' && $isLapRaw !== '1') {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "ID Anomali: " . $idAnomali,
                            'messages' => ["Gagal! Kolom isLap berkode '" . ($isLapRaw !== '' ? $isLapRaw : 'NULL') . "' tidak valid."]
                        ];
                        $gagal++;
                        continue;
                    }

                    if (!isset($mappedAnomali[$idAnomali])) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "ID Anomali: " . $idAnomali,
                            'messages' => ["ID Anomali tidak ditemukan di database."]
                        ];
                        $gagal++;
                        continue;
                    }

                    $dataExisting      = $mappedAnomali[$idAnomali];
                    $kabWilayahAnomali = substr($dataExisting['id_wilayah'], 0, 4);

                    if ($kabWilayahAnomali !== $idKab) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "ID Anomali: " . $idAnomali,
                            'messages' => ["Gagal! ID berada di luar wilayah otoritas Anda."]
                        ];
                        $gagal++;
                        continue;
                    }

                    if (!empty($dataExisting['konfirmasi']) && trim($dataExisting['konfirmasi']) !== '-') {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "ID Anomali: " . $idAnomali,
                            'messages' => ["Gagal! Data konfirmasi di database sudah terisi."]
                        ];
                        $gagal++;
                        continue;
                    }

                    $batchUpdateData[] = [
                        'id'              => $idAnomali,
                        'konfirmasi'      => $konfirmasi,
                        'is_lap'          => ($isLapRaw === '1') ? 1 : 0,
                        'date_konfirmasi' => date('Y-m-d H:i:s'),
                        'date_updated'    => date('Y-m-d H:i:s'),
                    ];
                    $berhasil++;
                }

                if (!empty($batchUpdateData)) {
                    $this->db->table('anomali')->updateBatch($batchUpdateData, 'id');
                }

                // Bersihkan memori secara ketat di setiap akhir chunk
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet, $worksheet, $rowsData, $chunkIds, $batchUpdateData, $mappedAnomali);
                gc_collect_cycles();
            }

            if (function_exists('libxml_clear_errors')) {
                libxml_clear_errors();
            }

            $logFinalData = [
                'status'        => 'selesai',
                'total_baris'   => $totalBarisData,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ];
            $this->logModel->update($logId, $logFinalData);

            CLI::write("Proses konfirmasi selesai. Berhasil: $berhasil, Gagal: $gagal", 'green');
        } catch (\Throwable $th) {
            CLI::error("Sistem Berhenti: " . $th->getMessage());

            $this->logModel->update($logId, [
                'status'        => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => [$th->getMessage()]]])
            ]);
        }
    }
}
