<?php

namespace App\Controllers;

use CodeIgniter\Model;
use App\Models\UserModel;
use App\Models\KegiatanModel;
use App\Models\AnomaliModel;

class Home extends BaseController
{
    public function index(): string
    {
        $data['title'] = 'Sidik Anomali';
        $users = new UserModel();
        $kegiatan = new KegiatanModel();
        $anomali = new AnomaliModel();

        $data['total_petugas'] = $users->countAllResults();
        $data['total_kegiatan'] = $kegiatan->countAllResults();
        $data['total_anomali'] = $anomali->countAllResults(); //125, // Ganti dengan query asli Anda

        return view('pages/home', $data);
    }
}
