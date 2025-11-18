<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RtArtSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'    => '1311040008000400001011',
                'id_wilayah'    => '1311040008000400',
                'id_kegiatan'    => '1',
                'kd_rt'    => '001',
                'kd_art'    => '01',
                'nm_krt'    => 'Syafiq',
                'nm_art'    => 'Syafiq',
                'idbs'    => '',
            ],
            [
                'id'    => '1311040008000400001011',
                'id_wilayah'    => '1311040008000400',
                'id_kegiatan'    => '1',
                'kd_rt'    => '001',
                'kd_art'    => '02',
                'nm_krt'    => 'Syafiq',
                'nm_art'    => 'Wahyu',
                'idbs'    => '',
            ],
        ];

        // Using Query Builder
        $this->db->table('rt_art')->insertBatch($data);
    }
}
