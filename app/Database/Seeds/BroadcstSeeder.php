<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BroadcstSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_user'    => 1,
                'id_kegiatan'    => 1,
                'judul_broadcast'    => 'Pengumuman Update Fasih Engine',
                'isi_broadcast'    => 'Assalamualaikum bapak/ibu semua, utk pekerjaan manen sawit menggunakan alat manual, itu statusnya tetap pekerja ya bapak ibu. jika majikannya tetap tp lebih dr 1, maka pekerjaa utama adalah di 1 majikan yang paling banyak jam kerja biasanya dalam seminggu, dilanjutkan tambahan utama majikan terbanyak selanjutnya.',
                // 'date_created'    => '2025-10-29 13:04:39'
            ],
            [
                'id_user'    => 1,
                'id_kegiatan'    => 1,
                'judul_broadcast'    => 'Pengumuman Perbaikan Anomali',
                'isi_broadcast'    => 'Assalamualaikum bapak/ibu semua, utk pekerjaan manen sawit menggunakan alat manual, itu statusnya tetap pekerja ya bapak ibu. jika majikannya tetap tp lebih dr 1, maka pekerjaa utama adalah di 1 majikan yang paling banyak jam kerja biasanya dalam seminggu, dilanjutkan tambahan utama majikan terbanyak selanjutnya.',
                // 'date_created'    => '2025-10-30 13:04:39'
            ],
        ];

        // Using Query Builder
        $this->db->table('broadcasts')->insertBatch($data);
    }
}
