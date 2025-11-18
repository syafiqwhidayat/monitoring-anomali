<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnomaliSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_kategori_anomali'    => 1,
                'id_user'    => 1,
                'id_wilayah'    => '1311040008000400',
                'id_rtart'    => '131104000800040000101',
                'nm_krt'    => 'Syafiq',
                'nm_art'    => 'Wahyu',
                'konfirmasi'    => ''
            ],
            [
                'id_kategori_anomali'    => 1,
                'id_user'    => 1,
                'id_wilayah'    => '1311040008000400',
                'id_rtart'    => '131104000800040000102',
                'nm_krt'    => 'Syafiq',
                'nm_art'    => 'Hidayat',
                'konfirmasi'    => 'Sesuai Lapangan'
            ],
        ];

        // Using Query Builder
        $this->db->table('anomali')->insertBatch($data);
    }
}
