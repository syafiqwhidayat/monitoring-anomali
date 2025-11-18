<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'    => '1311040008000400',
                'id_prov'    => '13',
                'id_kab'    => '11',
                'id_kec'    => '040',
                'id_des'    => '008',
                'id_sls'    => '0004',
                'id_subsls'    => '00',
                'nm_prov'    => 'Sumatera Barat',
                'nm_kab'    => 'Dharmasraya',
                'nm_kec'    => 'Pulau Punjung',
                'nm_des'    => 'Sikabau',
                'nm_sls'    => 'Jorong Sikabau'
            ],
            [
                'id'    => '1311040008000500',
                'id_prov'    => '13',
                'id_kab'    => '11',
                'id_kec'    => '040',
                'id_des'    => '008',
                'id_sls'    => '0005',
                'id_subsls'    => '00',
                'nm_prov'    => 'Sumatera Barat',
                'nm_kab'    => 'Dharmasraya',
                'nm_kec'    => 'Pulau Punjung',
                'nm_des'    => 'Sikabau',
                'nm_sls'    => 'Jorong Parik Tarajak'
            ],
        ];

        // Using Query Builder
        $this->db->table('wilayah')->insertBatch($data);
    }
}
