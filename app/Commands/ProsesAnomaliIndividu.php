<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\AnomaliModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProsesAnomaliIndividu extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'proses:anomali_individu';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Memproses unggahan Excel anomali individu (1 anomali per baris) dengan update isi_fasih dan join kategori_anomali.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'proses:anomali_individu [namaFile] [logId] [idKegiatan] [kodeKabOtoritas]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    protected $logModel;
    protected $anomaliModel;
    protected $db;

    /**
     * Actually execute a command.
     *
     * @param array $params
     * @return int Exit code (0 untuk sukses, 1 untuk gagal)
     */
    public function run(array $params)
    {
        $fileName   = $params[0] ?? null;
        $logId      = $params[1] ?? null;
        $idKegiatan = $params[2] ?? null;
        $idKab      = $params[3] ?? null; // Kode BPS Kabupaten user, misal: 1311

        if (!$fileName || !$logId || !$idKegiatan || !$idKab) {
            CLI::error("Parameter kurang lengkap! Dibutuhkan: namaFile, logId, idKegiatan, dan kodeKabOtoritas.");
            return 1;
        }

        $this->logModel     = new LogUploadModel();
        $this->anomaliModel = new AnomaliModel();
        $this->db           = \Config\Database::connect();

        // Update status log menjadi 'proses' di awal
        $this->logModel->update($logId, ['status' => 'proses']);

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;

            if (!file_exists($filePath)) {
                throw new \Exception("File template tidak ditemukan di direktori uploads.");
            }

            // Redam error internal libxml jika struktur HTML file rusak (kasus simpanan dari Excel)
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

                // Rekonstruksi id_assigment dari indeks [0] sampai [6]
                $id_assigment = trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')) . '_' . trim($row[5] ?? '') . '_' . trim($row[6] ?? '');
                $kodeAnomali  = trim($row[9] ?? '');

                // Lewati baris kosong
                if (($row[0] ?? '') === null && $kodeAnomali === '') {
                    continue;
                }

                if (!empty($id_assigment)) {
                    $allAssigmentInExcel[] = $id_assigment;

                    // Ekstrak 4 digit pertama sebagai kode kabupaten (Provinsi + Kab)
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

            // Validasi kecocokan wilayah dengan otoritas user yang mengunggah
            if ($detectedKab !== null && $detectedKab !== $idKab) {
                throw new \Exception("Gagal! Kode kabupaten di file ({$detectedKab}) tidak sesuai dengan kewenangan Anda ({$idKab}).");
            }

            // =================================================================
            // 2. CACHE MASTER KATEGORI ANOMALI (Untuk Keperluan INSERT)
            // =================================================================
            $mappedKategori = [];
            if (!empty($allKodeAnomaliInExcel)) {
                $kategoriData = $this->db->table('kategori_anomali')
                    ->select('id, kode_anomali')
                    ->where('id_kegiatan', $idKegiatan)
                    ->whereIn('kode_anomali', array_unique($allKodeAnomaliInExcel))
                    ->get()
                    ->getResultArray();

                foreach ($kategoriData as $kat) {
                    $mappedKategori[$kat['kode_anomali']] = $kat['id'];
                }
            }

            // =================================================================
            // 3. CACHE DATA EXISTING ANOMALI (JOIN Kategori Anomali)
            // =================================================================
            $mappedExisting = [];
            if (!empty($allAssigmentInExcel) && !empty($allKodeAnomaliInExcel)) {
                $dbData = $this->db->table('anomali')
                    ->select('anomali.id, anomali.id_assigment, anomali.isi_fasih, kategori_anomali.kode_anomali')
                    ->join('kategori_anomali', 'kategori_anomali.id = anomali.id_kategori_anomali')
                    ->where('kategori_anomali.id_kegiatan', $idKegiatan)
                    ->whereIn('anomali.id_assigment', array_unique($allAssigmentInExcel))
                    ->whereIn('kategori_anomali.kode_anomali', array_unique($allKodeAnomaliInExcel))
                    ->get()
                    ->getResultArray();

                foreach ($dbData as $rowDb) {
                    $uniqueKey = $rowDb['id_assigment'] . '_' . $rowDb['kode_anomali'];
                    $mappedExisting[$uniqueKey] = $rowDb;
                }
            }

            // =================================================================
            // 4. PROSES ITERASI VALIDASI & PEMBAGIAN BATCH DATA
            // =================================================================
            $batchInsertData = [];
            $batchUpdateData = [];

            $this->db->transStart();

            for ($i = 1; $i < count($sheetData); $i++) {
                $row    = $sheetData[$i];
                $rowNum = $i + 1;

                $id_assigment = trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')) . '_' . trim($row[5] ?? '') . '_' . trim($row[6] ?? '');
                $nmKrt        = trim($row[2] ?? '');
                $nmArt        = trim($row[3] ?? '');
                $idWilayah    = trim($row[4] ?? '');
                $kodeAnomali  = trim($row[9] ?? '');
                $isiFasih     = trim($row[10] ?? '');

                // Lewati baris yang benar-benar kosong
                if (empty($id_assigment) && empty($kodeAnomali)) {
                    continue;
                }

                // Proteksi jika salah satu kolom krusial kosong
                if (empty($id_assigment) || empty($kodeAnomali)) {
                    $errorDetails[] = [
                        'baris'    => $rowNum,
                        'data'     => "Assignment: $id_assigment, Kode Anomali: $kodeAnomali",
                        'messages' => ["Gagal! Kolom Assignment atau Kode Anomali tidak boleh kosong."]
                    ];
                    $gagal++;
                    continue;
                }

                $checkKey = $id_assigment . '_' . $kodeAnomali;

                if (isset($mappedExisting[$checkKey])) {
                    // KONDISI A: DATA SUDAH ADA -> UPDATE ISI_FASIH (is_insert tidak diganggu)
                    $existingData = $mappedExisting[$checkKey];

                    $batchUpdateData[] = [
                        'id'           => $existingData['id'],
                        'isi_fasih'    => $isiFasih,
                        'date_updated' => date('Y-m-d H:i:s')
                    ];
                } else {
                    // KONDISI B: DATA BELUM ADA -> INSERT BARU
                    if (!isset($mappedKategori[$kodeAnomali])) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => "Kode Anomali: $kodeAnomali",
                            'messages' => ["Gagal! Kode Anomali tidak ditemukan di master kategori untuk kegiatan ini."]
                        ];
                        $gagal++;
                        continue;
                    }

                    $idKategoriAnomali = $mappedKategori[$kodeAnomali];

                    $batchInsertData[] = [
                        'id_kategori_anomali' => $idKategoriAnomali,
                        'id_wilayah'          => $idWilayah ?: substr($id_assigment, 0, 10),
                        'id_assigment'        => $id_assigment,
                        'nm_krt'              => $nmKrt ?: null,
                        'nm_art'              => $nmArt ?: null,
                        'konfirmasi'          => '-',
                        'isi_fasih'           => $isiFasih,
                        'is_insert'           => 1,
                        'date_created'        => date('Y-m-d H:i:s'),
                        'date_updated'        => date('Y-m-d H:i:s')
                    ];
                }

                $berhasil++;
            }

            // Eksekusi Massal ke Database
            if (!empty($batchInsertData)) {
                $this->db->table('anomali')->insertBatch($batchInsertData);
            }
            if (!empty($batchUpdateData)) {
                $this->db->table('anomali')->updateBatch($batchUpdateData, 'id');
            }

            $this->db->transComplete();

            // Tulis ringkasan laporan akhir ke tabel log_upload
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

            // Daftarkan error sistem agar status tidak menggantung di 'proses'
            $this->logModel->update($logId, [
                'status'        => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => $fileName ?? 'System', 'messages' => [$th->getMessage()]]])
            ]);

            return 1;
        }
    }
}
