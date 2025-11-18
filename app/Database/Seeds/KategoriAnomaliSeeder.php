<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriAnomaliSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'id_kegiatan' => 1,
                'kode_anomali'    => 'AN1',
                'definisi_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
                'detil_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
                'is_show'    => TRUE,
                'date_created'    => '2025-11-05 13:04:39'
            ],
            [
                'id' => 2,
                'id_kegiatan' => 1,
                'kode_anomali'    => 'AN2',
                'definisi_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
                'detil_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
                'is_show'    => TRUE,
                'date_created'    => '2025-11-05 13:04:39'
            ],
        ];

        // Using Query Builder
        $this->db->table('kategori_anomali')->insertBatch($data);
    }
}
