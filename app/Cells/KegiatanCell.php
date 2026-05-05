<?php

namespace App\Cells;

class KegiatanCell
{
    // Fungsi ini akan mengambil data secara mandiri
    public function dropdown()
    {
        // inisiasi model
        $wilayahTugasModel = new \App\Models\WilayahTugasModel();
        $kegiatanModel = new \App\Models\KegiatanModel();
        $daftar_kegiatan = null;

        // mendapatkan daftar kegiatan aktif
        if (auth()->user()->getGroups()[0] == 'mitra') {
            $daftar_kegiatan = $wilayahTugasModel->getKegiatanByUser(auth()->id());
        } else {
            $daftar_kegiatan = $kegiatanModel->getKegiatanDesc();
        }

        return view('layout/cells/dropdown_kegiatan', [
            'daftar_kegiatan' => $daftar_kegiatan,
            'aktif'           => session()->get('aktif_kegiatan')
        ]);
    }
}
