<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class SeProgresController extends ResourceController
{
    protected $format = 'json';

    public function uploadProgres()
    {
        $file = $this->request->getFile('file_monitoring');
        $idUser = $this->request->getPost('id_user') ?? 1; // Default id_user system/superadmin
        $idKegiatan = $this->request->getPost('id_kegiatan') ?? 2; // Default ID Kegiatan SE2026

        if (!$file || !$file->isValid()) {
            return $this->fail('File tidak valid atau tidak ditemukan.');
        }

        $originalName = $file->getClientName();
        $today = date('Y-m-d'); // Kelompokkan log berdasarkan tanggal hari ini murni

        // 1. Pindahkan file CSV ke folder temporary
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/api_temp', $newName);
        $filePath = WRITEPATH . 'uploads/api_temp/' . $newName;

        $db = Database::connect();

        // 2. Insert Log Awal dengan Status 'proses'
        $db->table('se_upload_log')->insert([
            'id_kegiatan' => $idKegiatan,
            'jenis' => 'api_progres',
            'nama_file' => $originalName,
            'status' => 'proses',
            'total_baris' => 0,
            'berhasil' => 0,
            'gagal' => 0,
            'id_user' => $idUser,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $logId = $db->insertID();

        // 3. Mulai pemrosesan file CSV
        $totalBaris = 0;
        $berhasil = 0;
        $gagal = 0;
        $errorDetails = [];
        $detectedKab = null;

        $db->transStart();

        try {
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                // Ambil baris pertama sebagai header agar tidak masuk hitungan data
                $header = fgetcsv($handle, 0, ";");

                $batchData = [];
                while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
                    // KEKELIRUAN 1: KODE_SUBSLS_16 sekarang berada di indeks 22 (bukan indeks 2 lagi)
                    if (empty($row[22])) continue;

                    $totalBaris++;
                    $idSubsls = $row[22]; // Simpan kode 16 digit sub-sls

                    // Deteksi kode kabupaten dari KODE_SUBSLS_16 (ambil 4 digit pertama)
                    if ($detectedKab === null) {
                        $detectedKab = substr($idSubsls, 0, 4);

                        // Hapus data lama kabupaten ini di tanggal bersangkutan (Replace logic)
                        $db->table('se_progres_subsls')
                            ->where('tanggal', $today)
                            ->like('id_subsls', $detectedKab, 'after')
                            ->delete();
                    }

                    try {
                        // KEKELIRUAN 2: Pemetaan indeks array bergeser mengikuti struktur kolom CSV baru
                        $batchData[] = [
                            'id_subsls'             => $idSubsls,             // Indeks 22 (KODE_SUBSLS_16)
                            'total'                 => (int)$row[23],         // Indeks 23 (total)
                            'open'                  => (int)$row[24],         // Indeks 24 (open)
                            'submitted_by_pencacah' => (int)$row[25],         // Indeks 25 (submitted_by_pencacah)
                            'draft'                 => (int)$row[26],         // Indeks 26 (draft)
                            'approved_by_pengawas'  => (int)$row[27],         // Indeks 27 (approved_by_pengawas)
                            'rejected_by_pengawas'  => (int)$row[28],         // Indeks 28 (rejected_by_pengawas)
                            'submitted_respondent'  => (int)$row[29],         // Indeks 29 (submitted_respondent)
                            'revoked_by_pengawas'   => (int)$row[30],         // Indeks 30 (revoked_by_pengawas)
                            'tanggal'               => $today
                        ];
                        $berhasil++;
                    } catch (\Throwable $e) {
                        $gagal++;
                        $errorDetails[] = "Baris {$totalBaris} (SLS: {$idSubsls}): " . $e->getMessage();
                    }

                    // Flush batch insert per 200 baris demi menghemat memori runtime
                    if (count($batchData) >= 200) {
                        $db->table('se_progres_subsls')->insertBatch($batchData);
                        $batchData = [];
                    }
                }

                // Sisa data yang tidak genap kelipatan 200 di-insert di sini
                if (!empty($batchData)) {
                    $db->table('se_progres_subsls')->insertBatch($batchData);
                }
            }

            unlink($filePath); // Hapus file temporary
            $db->transComplete();

            // 4. Update Log Akhir (Selesai/Gagal)
            $statusAkhir = ($gagal === 0 && $berhasil > 0) ? 'selesai' : (($berhasil > 0) ? 'selesai' : 'gagal');

            $db->table('se_upload_log')->where('id', $logId)->update([
                'status' => $statusAkhir,
                'total_baris' => $totalBaris,
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'error_details' => !empty($errorDetails) ? json_encode($errorDetails) : null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respond([
                'status' => 200,
                'message' => "Progres Kab [{$detectedKab}] berhasil diperbarui untuk tanggal {$today}.",
                'summary' => ['total' => $totalBaris, 'berhasil' => $berhasil, 'gagal' => $gagal]
            ]);
        } catch (\Throwable $th) {
            $db->transRollback();
            if (file_exists($filePath)) unlink($filePath);

            // Tandai log sebagai gagal total
            $db->table('se_upload_log')->where('id', $logId)->update([
                'status' => 'gagal',
                'error_details' => $th->getMessage() . "\n" . $th->getTraceAsString(),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->failServerError('Gagal memproses file progres: ' . $th->getMessage());
        }
    }
}
