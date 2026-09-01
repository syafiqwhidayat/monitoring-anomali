<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JobRunner extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'proses:semua';
    protected $description = 'Menjalankan antrean proses dengan pengaman anti-overlap.';
    protected $usage       = 'proses:semua';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        set_time_limit(0);

        // =================================================================
        // PENGAMAN 1: CEK APAKAH CRON SEBELUMNYA MASIH BERJALAN
        // =================================================================
        $runningJob = $db->table('log_upload')
            ->where('status', 'proses')
            ->get()
            ->getRow();

        if ($runningJob) {
            // Jika ada yang statusnya 'proses', hentikan cron saat ini agar tidak overlap
            CLI::write("ℹ️ Cron sebelumnya masih berjalan memproses Job ID: {$runningJob->id}. Lewati sesi ini.", "yellow");
            return;
        }
        // =================================================================

        CLI::write("=== Memulai Pengecekan Antrean Job ===", "green");
        $jobProcessedCount = 0;

        while (true) {
            // Ambil 1 antrean tertua yang berstatus 'pending'
            $job = $db->table('log_upload')
                ->where('status', 'pending')
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getRow();

            // Jika sudah tidak ada antrean pending, keluar dari loop
            if (!$job) {
                break;
            }

            $jobProcessedCount++;

            // Kunci status menjadi 'proses'
            $db->table('log_upload')
                ->where('id', $job->id)
                ->update(['status' => 'proses']);

            CLI::write("\n[Job #{$jobProcessedCount}] Memproses Job ID: {$job->id} (Tipe: {$job->jenis})", "cyan");

            try {
                // Sanitasi & penanganan parameter NULL/kosong
                $namaFile   = !empty($job->nama_file) ? $job->nama_file : 'NULL';
                $idJob      = !empty($job->id) ? $job->id : 'NULL';
                $idKegiatan = !empty($job->id_kegiatan) ? $job->id_kegiatan : 'NULL';
                $wilayah    = !empty($job->wilayah) ? $job->wilayah : 'NULL';
                $idUser     = !empty($job->id_user) ? $job->id_user : 'NULL';

                $command = null;

                if ($job->jenis === 'wilayah') {
                    $command = "proses:wilayah {$namaFile} {$idJob} {$idKegiatan} {$wilayah}";
                } elseif ($job->jenis === 'anomali') {
                    $command = "proses:anomali {$namaFile} {$idJob} {$idKegiatan} {$wilayah}";
                } elseif ($job->jenis === 'anomali_individu') {
                    $command = "proses:anomali_individu {$namaFile} {$idJob} {$idKegiatan} {$wilayah} {$idUser}";
                } elseif ($job->jenis === 'anomali_individu_forced') {
                    $command = "proses:anomali_individu {$namaFile} {$idJob} {$idKegiatan} {$wilayah} {$idUser} 1";
                } else {
                    $command = "proses:konfirmasi {$namaFile} {$idJob} {$wilayah}";
                }

                // Jalankan command internal
                // Mulai menangkap output buffer untuk menangkap pesan error konsol non-exception
                ob_start();
                command($command);
                $output = ob_get_clean();

                // Cek apakah sub-command mencetak pesan error parameter
                if (stristr($output, 'Parameter tidak lengkap') !== false || stristr($output, 'wajib diisi') !== false) {
                    throw new \Exception(trim(strip_tags($output)));
                }

                // Cetak ulang output normal jika tidak ada error
                CLI::write($output);

                CLI::write("Job ID {$job->id} Sukses dijalankan.", "green");
            } catch (\Throwable $th) {
                // Pastikan buffer dibersihkan jika error terjadi di dalam try
                if (ob_get_level() > 0) {
                    ob_end_clean();
                }

                $errorMessage = $th->getMessage();

                // Update status gagal jika terjadi error catchable
                $db->table('log_upload')
                    ->where('id', $job->id)
                    ->update([
                        'status'        => 'gagal',
                        'error_details' => json_encode([
                            [
                                'baris'    => '-',
                                'data'     => 'System Runner',
                                'messages' => [$errorMessage]
                            ]
                        ]),
                    ]);

                CLI::error("❌ Error pada Job ID {$job->id}: {$errorMessage}");
            }
        }

        if ($jobProcessedCount === 0) {
            CLI::write("Tidak ada antrean pending saat ini.", "yellow");
        } else {
            CLI::write("\n=== Semua antrean selesai diproses. Total Job: {$jobProcessedCount} ===", "green");
        }
    }
}
