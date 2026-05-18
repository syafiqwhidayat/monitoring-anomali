<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class JobRunner extends BaseCommand
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
    protected $name = 'proses:semua';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Menjalankan antrean proses wilayah dan anomali secara otomatis.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'proses:semua';

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

    /**
     * Actually execute a command.
     *
     * @param array $params
     */

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        // CLI::write("Tidak ada antrean saat ini.", "yellow");
        // return;

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
        $db->table('log_upload')->update(['status' => 'proses'], ['id' => $job->id]);

        CLI::write("Memproses Job ID: {$job->id} (Tipe: {$job->jenis})", "cyan");

        try {
            // Set timeout PHP secara manual untuk job ini (misal 10 menit)
            set_time_limit(600);

            // Tentukan nama command yang akan dipanggil
            $command = null;
            if ($job->jenis === 'wilayah') {
                $command = "proses:wilayah $job->nama_file $job->id $job->id_kegiatan $job->wilayah";
            } else {
                $command = "proses:anomali $job->nama_file $job->id $job->id_kegiatan $job->wilayah";
            }

            // Panggil command proses yang sudah Anda buat sebelumnya secara internal
            // Format: command($commandName, [$params])
            command($command);

            // Update status selesai
            // $db->table('log_upload')->update(['status' => 'selesai'], ['id' => $job->id]);
            CLI::write("Job ID {$job->id} Berhasil.", "green");
        } catch (\Throwable $th) {
            // Update status gagal jika terjadi error sistem
            $db->table('log_upload')->update([
                'status' => 'gagal',
                'error_details' => json_encode($th->getMessage()),
            ], ['id' => $job->id]);

            CLI::error("Error pada Job ID {$job->id}: " . $th->getMessage());
        }
    }
}
