<?php

namespace App\Models;

use CodeIgniter\Model;

class IdenAnadesModel extends Model
{
    protected $table            = 'identifikasi_anades';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_kegiatan',
        'kode_wilayah',
        'kode_variabel',
        'deskripsi',
        'id_kategori_anomali',
        'n_batas_bawah',
        'n_q1',
        'median',
        'n_q3',
        'n_batas_atas',
        'n_rata',
        'n_outlier',
        'n_histogram'
    ];

    // Ambil daftar anades beserta info relasi nama kategori anomalinya
    public function getAnadesByKegiatan($idKegiatan)
    {
        return $this->select('identifikasi_anades.*, k.kode_anomali, k.detil_anomali')
            ->join('kategori_anomali k', 'k.id = identifikasi_anades.id_kategori_anomali', 'left')
            ->where('identifikasi_anades.id_kegiatan', $idKegiatan)
            ->findAll();
    }
}
