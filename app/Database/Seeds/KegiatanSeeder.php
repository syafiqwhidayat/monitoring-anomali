<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'kode_kegiatan'    => 'SAK_NOV25',
                'nama_kegiatan'    => 'Sakernas November 2025',
                'detil_kegiatan'    => 'Survei Ketenagakerjaan Nasional November 2025',
                'priode_awal'    => '2025-11-01 00:00:30',
                'priode_akhir'    => '2025-11-30 23:59:30'
            ],
            [
                'id' => 2,
                'kode_kegiatan'    => 'SAK_AGU25',
                'nama_kegiatan'    => 'Sakernas Agustus 2025',
                'detil_kegiatan'    => 'Survei Ketenagakerjaan Nasional Agustus 2025',
                'priode_awal'    => '2025-08-01 00:00:30',
                'priode_akhir'    => '2025-08-30 23:59:30'
            ],
        ];

        // Using Query Builder
        $this->db->table('kegiatan')->insertBatch($data);
    }
}
