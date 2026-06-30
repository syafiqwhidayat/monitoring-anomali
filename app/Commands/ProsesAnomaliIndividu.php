<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\AnomaliModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProsesAnomaliIndividu extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'proses:anomali_individu';
    protected $description = 'Memproses unggahan Excel anomali individu dengan auto-create kategori_anomali dan assigment jika belum ada.';
    protected $usage       = 'proses:anomali_individu [namaFile] [logId] [idKegiatan] [kodeKabOtoritas]';

    protected $logModel;
    protected $anomaliModel;
    protected $db;

    public function run(array $params)
    {
        $fileName              = $params[0] ?? null;
        $logId                 = $params[1] ?? null;
        $idKegiatan            = $params[2] ?? null;
        $idKab                 = $params[3] ?? null;
        $idUser                = $params[4] ?? null;
        $forcedKonfirmasi      = $params[5] ?? 0;

        if (!$fileName || !$logId || !$idKegiatan || !$idKab) {
            CLI::error("Parameter kurang lengkap! Dibutuhkan: namaFile, logId, idKegiatan, dan kodeKabOtoritas.");
            return 1;
        }

        $this->logModel     = new LogUploadModel();
        $this->anomaliModel = new AnomaliModel();
        $this->db           = \Config\Database::connect();

        $this->logModel->update($logId, ['status' => 'proses']);

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;

            if (!file_exists($filePath)) {
                throw new \Exception("File template tidak ditemukan di direktori uploads.");
            }

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

            // =================================================================
            // 1. KUMPULKAN DATA UNIQUE DARI EXCEL & VALIDASI KABUPATEN TUNGGAL
            // =================================================================
            $allAssigmentInExcel   = [];
            $allKodeAnomaliInExcel = [];
            $detectedKab           = null;

            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];

                $id_assigment = trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')) . '_' . trim($row[5] ?? '') . '_' . trim($row[6] ?? '');
                $kodeAnomali  = trim($row[9] ?? '');

                if (empty($id_assigment) && empty($kodeAnomali)) {
                    continue;
                }

                if (!empty($id_assigment)) {
                    $allAssigmentInExcel[] = $id_assigment;

                    $kabRow = substr($id_assigment, 0, 4);
                    if ($detectedKab === null) {
                        $detectedKab = $kabRow;
                    } elseif ($detectedKab !== $kabRow) {
                        throw new \Exception("hanya boleh 1 jenis kabupaten");
                    }
                }

                if (!empty($kodeAnomali)) {
                    $allKodeAnomaliInExcel[] = $kodeAnomali;
                }
            }

            if ($detectedKab !== null && !in_array($idKab, ['0000', '1300']) && $detectedKab !== $idKab) {
                throw new \Exception("Gagal! Kode kabupaten di file ({$detectedKab}) tidak sesuai dengan kewenangan Anda ({$idKab}).");
            }

            $uniqueAssigments = array_unique($allAssigmentInExcel);
            $uniqueKodes      = array_unique($allKodeAnomaliInExcel);

            // =================================================================
            // 2. CACHE MASTER KATEGORI ANOMALI (Ke Memori RAM)
            // =================================================================
            $mappedKategori = [];
            if (!empty($uniqueKodes)) {
                $kategoriData = $this->db->table('kategori_anomali')
                    ->select('id, kode_anomali')
                    ->where('id_kegiatan', $idKegiatan)
                    ->whereIn('kode_anomali', $uniqueKodes)
                    ->get()
                    ->getResultArray();

                foreach ($kategoriData as $kat) {
                    $mappedKategori[$kat['kode_anomali']] = $kat['id'];
                }
            }

            // =================================================================
            // 3. CACHE DATA MASTER ASSIGMENT (Ke Memori RAM)
            // =================================================================
            $mappedAssigment = [];
            $uniqueAssigmentIds = [];
            if (!empty($uniqueAssigments)) {
                $assigmentData = $this->db->table('assigment')
                    ->select('id, kd_assigment')
                    ->where('id_kegiatan', $idKegiatan)
                    ->whereIn('kd_assigment', $uniqueAssigments)
                    ->get()
                    ->getResultArray();

                foreach ($assigmentData as $asg) {
                    $mappedAssigment[$asg['kd_assigment']] = $asg['id'];
                    $uniqueAssigmentIds[] = $asg['id'];
                }
            }

            // Mulai Transaksi Database
            $this->db->transStart();

            // =================================================================
            // 4. RESET SEMUA IS_INSERT JADI 0 DI AWAL (Hanya yang terdampak di Excel)
            // =================================================================
            if (!empty($uniqueAssigments) && !empty($uniqueKodes)) {
                $involvedKategoriIds = !empty($mappedKategori) ? array_values($mappedKategori) : [0];

                $this->db->table('anomali')
                    // ->whereIn('id_assigment', $uniqueAssigments)
                    ->whereIn('id_kategori_anomali', $involvedKategoriIds)
                    ->update(['is_insert' => 0]);
            }

            // =================================================================
            // 5. CACHE DATA EXISTING ANOMALI (Ke Memori RAM)
            // =================================================================
            $mappedExisting = [];
            if (!empty($uniqueAssigments) && !empty($uniqueKodes)) {
                $dbData = $this->db->table('anomali')
                    ->select('anomali.id, anomali.id_assigment, anomali.isi_fasih, kategori_anomali.kode_anomali,anomali.konfirmasi,anomali.is_sistem')
                    ->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali')
                    ->where('kategori_anomali.id_kegiatan', $idKegiatan)
                    ->whereIn('anomali.id_assigment', $uniqueAssigmentIds)
                    ->whereIn('kategori_anomali.kode_anomali', $uniqueKodes)
                    ->get()
                    ->getResultArray();

                foreach ($dbData as $rowDb) {
                    $uniqueKey = $rowDb['id_assigment'] . '_' . $rowDb['kode_anomali'];
                    $mappedExisting[$uniqueKey] = $rowDb;
                }
            }

            // =================================================================
            // 6. PROSES ITERASI UTAMA DATA EXCEL
            // =================================================================
            $batchInsertAnomali = [];
            $batchUpdateAnomali = [];

            for ($i = 1; $i < count($sheetData); $i++) {
                $row    = $sheetData[$i];
                $rowNum = $i + 1;

                $id_assigment = trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')) . '_' . trim($row[5] ?? '') . '_' . trim($row[6] ?? '');
                $kdKrt        = trim($row[5] ?? '');
                $kdArt        = trim($row[6] ?? '');
                $nmKrt        = trim($row[7] ?? '');
                $nmArt        = trim($row[8] ?? '');
                $idWilayah    = trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? ''));
                $kodeAnomali  = trim($row[9] ?? '');
                $isiFasih     = trim($row[10] ?? '');
                $konfirmasi     = trim($row[11] ?? '');

                if (empty($id_assigment) && empty($kodeAnomali)) {
                    continue;
                }

                if (empty($id_assigment) || empty($kodeAnomali)) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "Assignment: $id_assigment, Kode Anomali: $kodeAnomali",
                        'messages' => ["Gagal! Kolom Assignment atau Kode Anomali tidak boleh kosong."]
                    ];
                    $gagal++;
                    continue;
                }
                if (empty($kdKrt) || empty($nmKrt)) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "Kd KRT: $kdKrt, Nama KRT: $nmKrt",
                        'messages' => ["Gagal! KD KRT dan Nama KRT tidak boleh kosong"]
                    ];
                    $gagal++;
                    continue;
                }

                // -------------------------------------------------------------
                // PERIKSA / BUAT KATEGORI ANOMALI JIKA BELUM ADA
                // -------------------------------------------------------------
                if (!isset($mappedKategori[$kodeAnomali])) {
                    $this->db->table('kategori_anomali')->insert([
                        'id_kegiatan'   => $idKegiatan,
                        'level_anomali' => $idKab, // Default diisi kode kabupaten otoritas
                        'kode_anomali'  => $kodeAnomali,
                        'flag'          => '3',
                        'is_show'       => 0,
                        'date_created'  => date('Y-m-d H:i:s')
                    ]);
                    $newKategoriId = $this->db->insertID();

                    // Masukkan ke cache memori agar baris berikutnya tidak buat duplikat lagi
                    $mappedKategori[$kodeAnomali] = $newKategoriId;
                }
                $idKategoriAnomali = $mappedKategori[$kodeAnomali];

                // -------------------------------------------------------------
                // PERIKSA / BUAT ASSIGMENT JIKA BELUM ADA
                // -------------------------------------------------------------
                if (!isset($mappedAssigment[$id_assigment])) {
                    $this->db->table('assigment')->insert([
                        'id_wilayah'   => $idWilayah ?: substr($id_assigment, 0, 10),
                        'id_kegiatan'  => $idKegiatan,
                        'kd_assigment' => $id_assigment,
                        'kd_krt'       => $kdKrt ?: null,
                        'kd_art'       => $kdArt ?: null,
                        'nm_krt'       => $nmKrt ?: null,
                        'nm_art'       => $nmArt ?: null,
                    ]);
                    $newAssigmentId = $this->db->insertID();

                    // Masukkan ke cache memori
                    $mappedAssigment[$id_assigment] = $newAssigmentId;
                }

                // -------------------------------------------------------------
                // KLASIFIKASI UPSERT TABEL ANOMALI
                // -------------------------------------------------------------
                $checkKey = $mappedAssigment[$id_assigment] . '_' . $kodeAnomali;
                if (isset($mappedExisting[$checkKey])) {
                    // KONDISI A: DATA SUDAH ADA -> UPDATE
                    $existingData = $mappedExisting[$checkKey];

                    $konfirmasiDb = isset($existingData['konfirmasi']) ? trim($existingData['konfirmasi']) : null;
                    $isSistemDb   = isset($existingData['is_sistem']) ? (int)$existingData['is_sistem'] : 0;
                    if ($isSistemDb === 1) {
                        $konfirmasiDb = null;
                        $isDbKosong   = true;
                    } else {
                        $isDbKosong   = ($konfirmasiDb === null || $konfirmasiDb === '' || strtolower($konfirmasiDb) === 'none' || $konfirmasiDb === '-');
                    }

                    // Inisialisasi nilai konfirmasi akhir dengan data database saat ini
                    $finalKonfirmasi = $isDbKosong ? null : $konfirmasiDb;

                    if ($forcedKonfirmasi == 1) {
                        // Jika forced = 1, ganti dengan excel KECUALI jika excel-nya kosong/strip
                        if ($konfirmasi !== '' && $konfirmasi !== '-') {
                            $finalKonfirmasi = $konfirmasi;
                        }
                    } else {
                        // Jika forced = 0, pakai excel HANYA JIKA di database masih kosong/strip
                        if ($isDbKosong) {
                            $finalKonfirmasi = $konfirmasi;
                        }
                    }

                    $batchUpdateAnomali[] = [
                        'id'           => $existingData['id'],
                        'id_user'    => $idUser,
                        'isi_fasih'    => $isiFasih,
                        'is_insert'    => 1,
                        'is_sistem'    => 0,
                        'konfirmasi'    => $finalKonfirmasi,
                        'date_updated' => date('Y-m-d H:i:s')
                    ];
                } else {
                    // KONDISI B: DATA BELUM ADA -> INSERT
                    $batchInsertAnomali[] = [
                        'id_kategori_anomali' => $idKategoriAnomali,
                        'id_wilayah'          => $idWilayah ?: substr($id_assigment, 0, 10),
                        'id_assigment'        => $mappedAssigment[$id_assigment],
                        'isi_fasih'           => $isiFasih,
                        'konfirmasi'           => $konfirmasi,
                        'is_insert'           => 1,
                        'id_user'           => $idUser,
                        'date_created'        => date('Y-m-d H:i:s'),
                        'date_updated'        => date('Y-m-d H:i:s')
                    ];

                    // Daftarkan ke cache temporary agar jika ada baris duplikat di Excel, ia masuk antrean Update berikutnya
                    $mappedExisting[$checkKey] = ['id' => null, 'id_assigment' => $id_assigment, 'isi_fasih' => $isiFasih, 'kode_anomali' => $kodeAnomali, 'konfirmasi' => $konfirmasi];
                }

                $berhasil++;
            }

            // Eksekusi data anomali secara massal di akhir transaksi
            if (!empty($batchInsertAnomali)) {
                $this->db->table('anomali')->insertBatch($batchInsertAnomali);
            }
            if (!empty($batchUpdateAnomali)) {
                $this->db->table('anomali')->updateBatch($batchUpdateAnomali, 'id');
            }
            // membuat yg tidak muncul sebagai konfirmasi by sistem
            $this->db->table('anomali')
                // ->whereIn('id_assigment', $uniqueAssigments)
                ->whereIn('id_kategori_anomali', $involvedKategoriIds)
                ->where('is_insert', 0)
                ->update(['is_sistem' => 1, 'konfirmasi' => 'System: Sudah diperbaiki di fasih']);

            $this->db->transComplete();

            // Selesai dan update Log Upload
            $this->logModel->update($logId, [
                'status'        => 'selesai',
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ]);

            CLI::write("Proses anomali individu selesai. Berhasil: $berhasil, Gagal: $gagal", 'green');
            return 0;
        } catch (\Throwable $th) {
            if (isset($this->db) && $this->db->transStatus() === false) {
                $this->db->transRollback();
            }

            CLI::error("Sistem Berhenti: " . $th->getMessage());

            $this->logModel->update($logId, [
                'status'        => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => $fileName ?? 'System', 'messages' => [$th->getMessage()]]])
            ]);

            return 1;
        }
    }
}
