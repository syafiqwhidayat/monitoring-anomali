<?php

namespace App\Models;

use CodeIgniter\Model;

class WilUploadLogModel extends Model
{
    protected $table            = 'wilayah_upload_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object'; // Kita gunakan object agar lebih mudah diakses di View
    protected $useSoftDeletes   = false;

    // Daftarkan kolom yang boleh diisi
    protected $allowedFields    = [
        'nama_file',
        'status',
        'total_baris',
        'berhasil',
        'gagal',
        'error_details',
        'id_user',
        'id_kegiatan'
    ];

    // Otomatis mencatat waktu buat dan update
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Fungsi pembantu untuk mengambil detail error yang sudah diubah kembali
     * dari JSON menjadi Array PHP.
     */
    public function getErrors($id)
    {
        $log = $this->find($id);
        if ($log && $log->error_details) {
            return json_decode($log->error_details, true);
        }
        return [];
    }
}
