<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class SeListUbController extends ResourceController
{
    protected $format = 'json';

    public function uploadListUb()
    {
        $file = $this->request->getFile('file_list_ub');
        $idUser = $this->request->getPost('id_user') ?? 1;
        $idKegiatan = $this->request->getPost('id_kegiatan') ?? 2;

        if (!$file || !$file->isValid()) {
            return $this->fail('File tidak valid atau tidak ditemukan.');
        }

        $originalName = $file->getClientName();

        // 1. Pindahkan file CSV ke folder temporary
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/api_temp', $newName);
        $filePath = WRITEPATH . 'uploads/api_temp/' . $newName;

        $db = Database::connect();

        // 2. Insert Log Awal
        $db->table('se_upload_log')->insert([
            'id_kegiatan'   => $idKegiatan,
            'jenis'         => 'api_list_se2026ub',
            'nama_file'     => $originalName,
            'status'        => 'proses',
            'total_baris'   => 0,
            'berhasil'      => 0,
            'gagal'         => 0,
            'id_user'       => $idUser,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        $logId = $db->insertID();

        $totalBaris   = 0;
        $berhasil     = 0;
        $gagal        = 0;
        $detectedKab  = null;

        // Menggunakan Strict Manual Transaction
        $db->transBegin();

        try {
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                // Ambil baris pertama sebagai header
                $header = fgetcsv($handle, 0, ";");

                $batchData = [];
                while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
                    if (empty($row[0]) || empty($row[2])) continue;

                    $totalBaris++;
                    $idWilayahRow = trim($row[2]);

                    if ($detectedKab === null) {
                        $detectedKab = $idWilayahRow;
                        // LOGIKA DELETE DI SINI SUDAH DIHAPUS AGAR TIDAK MEREPLACE SEMUA DATA
                    }

                    // Format tanggal fasih_modified_at
                    $dateUpdated = (!empty($row[11])) ? trim($row[11]) : null;
                    if ($dateUpdated && strpos($dateUpdated, '.') !== false) {
                        $dateUpdated = explode('.', $dateUpdated)[0];
                    }

                    $statusFasih = !empty($row[9]) ? strtolower(trim($row[9])) : 'open';

                    $batchData[] = [
                        'sample_id'         => trim($row[0]),
                        'id_sls'            => !empty($row[1]) ? trim($row[1]) : null,
                        'id_wilayah'        => $idWilayahRow,
                        'nama_usaha'        => trim($row[3]),
                        'kode_identity'     => !empty($row[4]) ? trim($row[4]) : null,
                        'alamat_usaha'      => !empty($row[5]) ? trim($row[5]) : null,
                        'nama_kecamatan'    => !empty($row[6]) ? trim($row[6]) : null,
                        'nama_desa'         => !empty($row[7]) ? trim($row[7]) : null,
                        'email_usaha'       => !empty($row[8]) ? trim($row[8]) : null,
                        'pencacah'          => null,
                        'status'            => $statusFasih,
                        // 'mode_pencacahan'   => null,
                        // 'tim_pj'            => !empty($row[10]) ? trim($row[10]) : null,
                        'fasih_modified_at' => $dateUpdated,
                        'uploaded_at'       => date('Y-m-d H:i:s')
                    ];

                    $berhasil++;

                    if (count($batchData) >= 200) {
                        // GANTI: Menggunakan upsertBatch() murni CI4
                        $db->table('se_list_se26_ub')->upsertBatch($batchData);
                        $batchData = [];
                    }
                }

                if (!empty($batchData)) {
                    // GANTI: Menggunakan upsertBatch() murni CI4
                    $db->table('se_list_se26_ub')->upsertBatch($batchData);
                }
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // ==============================================================================
            // PROSES HITUNG (AGREGASI) & SIMPAN KE SE_MON_UB
            // ==============================================================================
            if ($detectedKab !== null) {
                // 1. Ambil jumlah agregasi real-time dari se_list_se26_ub untuk kabupaten ini
                $counts = $db->table('se_list_se26_ub')
                    ->select('status, COUNT(*) as jumlah')
                    ->where('id_wilayah', $detectedKab)
                    ->groupBy('status')
                    ->get()
                    ->getResultArray();

                // Ubah hasil query menjadi bentuk key-value array ['status' => jumlah]
                $realCounts = [];
                foreach ($counts as $c) {
                    $realCounts[$c['status']] = (int)$c['jumlah'];
                }

                // 2. Daftar master status wajib murni dari Fasih (huruf kecil)
                $masterStatus = [
                    'open',
                    'draft',
                    'rejected_by_pengawas',
                    'approved_by_pengawas',
                    'submitted_by_pencacah',
                    'submitted_respondent',
                    'revoked_by_pengawas',
                    'rejected_by_admin_kabupaten'
                ];

                $today = date('Y-m-d');

                // 3. Hapus record monitoring lama untuk KABUPATEN ini di TANGGAL HARI INI jika ada (agar tidak duplikat saat push ulang di hari yang sama)
                $db->table('se_mon_ub')
                    ->where('id_wilayah', $detectedKab)
                    ->where('tanggal', $today)
                    ->delete();

                // 4. Siapkan batch data baru untuk se_mon_ub
                $monBatchData = [];
                foreach ($masterStatus as $statusItem) {
                    // Jika status tidak muncul di hasil hitungan, otomatis di-set 0
                    $jumlahStatus = isset($realCounts[$statusItem]) ? $realCounts[$statusItem] : 0;

                    $monBatchData[] = [
                        'id_wilayah' => $detectedKab,
                        'tanggal'    => $today,
                        'status'     => $statusItem,
                        'jumlah'     => $jumlahStatus
                    ];
                }

                // 5. Insert seluruh baris status ke tabel se_mon_ub
                if (!empty($monBatchData)) {
                    $db->table('se_mon_ub')->insertBatch($monBatchData);
                }
            }

            if ($db->transStatus() === FALSE) {
                throw new \RuntimeException("Transaksi database gagal pada proses upsert batch.");
            }

            $db->transCommit();

            $statusAkhir = ($gagal === 0 && $berhasil > 0) ? 'selesai' : (($berhasil > 0) ? 'selesai' : 'gagal');

            $db->table('se_upload_log')->where('id', $logId)->update([
                'status'        => $statusAkhir,
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => null,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

            return $this->respond([
                'status'  => 200,
                'message' => "List Usaha Baru Kab [{$detectedKab}] berhasil diperbarui (upsert) dengan Log ID #{$logId}.",
                'summary' => ['total' => $totalBaris, 'berhasil' => $berhasil, 'gagal' => $gagal]
            ]);
        } catch (\Throwable $th) {
            $dbError = $db->error();
            $sqlError = (!empty($dbError['message'])) ? " [SQL ERROR: " . $dbError['message'] . "]" : "";

            $db->transRollback();

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $db->table('se_upload_log')->where('id', $logId)->update([
                'status'        => 'gagal',
                'error_details' => $th->getMessage() . $sqlError . "\n" . $th->getTraceAsString(),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

            return $this->failServerError('Gagal memproses up-insert file list UB: ' . $th->getMessage() . $sqlError);
        }
    }
}
