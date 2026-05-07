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
        if (session()->get('aktif_role') == 'mitra') {
            $daftar_kegiatan = $wilayahTugasModel->getKegiatanByUser(auth()->id());
        } else {
            $daftar_kegiatan = $kegiatanModel->getKegiatanDesc();
        }

        if (!$daftar_kegiatan && session()->get('aktif_kegiatan')) {
            $daftar_kegiatan = [['id' => null, 'nama' => 'tidak ada kegiatan terdaftar']];
            session()->set('aktif_kegiatan', null);
            session()->set('nama_kegiatan', 'Tidak ada kegiatan terdaftar');
        }

        if (!session()->get('aktif_kegiatan') && $daftar_kegiatan) {
            session()->set('aktif_kegiatan', $daftar_kegiatan[0]['id']);
            session()->set('nama_kegiatan', $daftar_kegiatan[0]['nama']);
        }

        return view('layout/cells/dropdown_kegiatan', [
            'daftar_kegiatan' => $daftar_kegiatan,
            'aktif'           => session()->get('aktif_kegiatan')
        ]);
    }
}
