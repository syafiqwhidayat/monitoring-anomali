<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Kegiatan extends BaseController
{
    public function index()
    {
        //
    }

    public function set($id)
    {
        $kegiatanModel = new \App\Models\KegiatanModel();
        $wilayahTugasModel = new \App\Models\WilayahTugasModel();
        $userId = auth()->id();

        $hasAccess = false;

        if (auth()->user()->getGroups()[0] == 'mitra') {
            $daftar_kegiatan = $wilayahTugasModel->getKegiatanByUser($userId);
            $semua_id = array_column($daftar_kegiatan, 'id');
            if (in_array($id, $semua_id)) {
                $hasAccess = true;
            }
        } else {
            $hasAccess = true;
        }

        if ($hasAccess) {
            // Ambil detail kegiatan untuk mendapatkan namanya
            $kegiatan = $kegiatanModel->find($id);
            session()->set('aktif_kegiatan', $kegiatan['id']);
            session()->set('nama_kegiatan', $kegiatan['nama_kegiatan']);
            session()->set('is_rt', $kegiatan['is_rt']);
            session()->set('level_wilayah', $kegiatan['level_wilayah']);
        }

        // Kembali ke halaman yang sedang dibuka (Refresh)
        $target = $this->request->getGet('return') ?? base_url('/');
        return redirect()->to($target)->with('message', 'Kegiatan berhasil diubah ke: ' . session()->get('nama_kegiatan'));
        // return redirect()->back()->with('message', 'Kegiatan berhasil diubah ke: ' . session()->get('nama_kegiatan'));
    }
}
