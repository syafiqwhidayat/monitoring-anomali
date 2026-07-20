<?php

namespace App\Models;

use CodeIgniter\Model;

class IdenKategorikModel extends Model
{
    // Hubungkan langsung ke nama tabel baru khusus kategori
    protected $table            = 'identifikasi_kategori';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    // Sesuaikan kolom di bawah ini dengan struktur field yang ada di tabel identifikasi_kategori Anda
    protected $allowedFields    = [
        'id_kegiatan',
        'kode_wilayah',
        'kode_variabel',
        'deskripsi',
        'jenis',
        'data',
        'id_kategori_anomali'
    ];

    // Aktifkan timestamp jika tabel Anda mencatat waktu input/update data
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
