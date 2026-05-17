<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RunQueue extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'queue:run';
    protected $description = 'Menjalankan antrean proses wilayah dan anomali secara otomatis.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // Ambil 1 antrean tertua yang statusnya masih 'antri'
        $job = $db->table('log_upload')
            ->where('status', 'pending')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getRow();

        if (!$job) {
            CLI::write("Tidak ada antrean saat ini.", "yellow");
            return;
        }

        // Update status agar tidak diambil oleh proses cron berikutnya (Race Condition)
        $db->table('queue_proses')->update(['status' => 'proses'], ['id' => $job->id]);

        CLI::write("Memproses Job ID: {$job->id} (Tipe: {$job->jenis})", "cyan");

        try {
            // Set timeout PHP secara manual untuk job ini (misal 10 menit)
            set_time_limit(600);

            // Tentukan nama command yang akan dipanggil
            $command = null;
            if ($job->tipe_proses === 'wilayah') {
                $command = "proses:wilayah $job->nama_file $job->id $job->id_kegiatan $job->wilayah";
            } else {
                $command = "proses:anomali $job->nama_file $job->id $job->id_kegiatan $job->wilayah";
            }

            // Panggil command proses yang sudah Anda buat sebelumnya secara internal
            // Format: command($commandName, [$params])
            command($command);

            // Update status selesai
            $db->table('queue_proses')->update(['status' => 'selesai'], ['id' => $job->id]);
            CLI::write("Job ID {$job->id} Berhasil.", "green");
        } catch (\Throwable $th) {
            // Update status gagal jika terjadi error sistem
            $db->table('queue_proses')->update([
                'status' => 'gagal',
                'error_details' => json_encode($th->getMessage()),
            ], ['id' => $job->id]);

            CLI::error("Error pada Job ID {$job->id}: " . $th->getMessage());
        }
    }
}
