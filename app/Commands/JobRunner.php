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
            $db->table('log_upload')->update(['status' => 'proses'], ['id' => $job->id]);

            CLI::write("\n[Job #{$jobProcessedCount}] Memproses Job ID: {$job->id} (Tipe: {$job->jenis})", "cyan");

            try {
                $command = null;
                if ($job->jenis === 'wilayah') {
                    $command = "proses:wilayah $job->nama_file $job->id $job->id_kegiatan $job->wilayah";
                } elseif ($job->jenis === 'anomali') {
                    $command = "proses:anomali $job->nama_file $job->id $job->id_kegiatan $job->wilayah";
                } elseif ($job->jenis === 'anomali_individu') {
                    $command = "proses:anomali_individu $job->nama_file $job->id $job->id_kegiatan $job->wilayah $job->id_user $job->wilayah";
                } elseif ($job->jenis === 'anomali_individu_forced') {
                    $command = "proses:anomali_individu $job->nama_file $job->id $job->id_kegiatan $job->wilayah $job->id_user $job->wilayah 1";
                } else {
                    $command = "proses:konfirmasi $job->nama_file $job->id $job->wilayah";
                }

                // Jalankan command internal
                command($command);

                CLI::write("Job ID {$job->id} Sukses dijalankan.", "green");
            } catch (\Throwable $th) {
                // Update status gagal jika terjadi error catchable
                $db->table('log_upload')->update([
                    'status'        => 'gagal',
                    'error_details' => json_encode([['baris' => '-', 'data' => 'System Runner', 'messages' => [$th->getMessage()]]]),
                ], ['id' => $job->id]);

                CLI::error("Error pada Job ID {$job->id}: " . $th->getMessage());
            }
        }

        if ($jobProcessedCount === 0) {
            CLI::write("Tidak ada antrean pending saat ini.", "yellow");
        } else {
            CLI::write("\n=== Semua antrean selesai diproses. Total Job: {$jobProcessedCount} ===", "green");
        }
    }
}
