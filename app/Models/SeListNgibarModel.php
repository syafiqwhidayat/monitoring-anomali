<?php

namespace App\Models;

use CodeIgniter\Model;

class SeListNgibarModel extends Model
{
    protected $table            = 'se_list_ngibar';
    protected $primaryKey       = 'id_assignment';
    protected $allowedFields    = ['id_assigment', 'id_log', 'id_wilayah', 'jenis_kegiatan', 'nama_usaha', 'alamat_usaha', 'kode_identitas', 'email', 'status', 'date_updated'];

    /**
     * Mendapatkan daftar assignment untuk wilayah tertentu, diurutkan berdasarkan update terbaru
     */
    public function getListByWilayah($idWilayah, $statusFilter = null)
    {
        $builder = $this->where('id_wilayah', $idWilayah);

        // Jika user memilih filter status spesifik
        if (!empty($statusFilter)) {
            $builder->where('status', $statusFilter);
        } else {
            // Default: Membatasi hanya 3 status ini (Open diabaikan)
            $builder->whereIn('status', ['Submitted Respondent', 'Draft', 'Rejected Admin']);
        }

        // Urutkan berdasarkan tanggal update aplikasi lain yang paling baru
        return $builder->orderBy('date_updated', 'DESC')->findAll();
    }

    public function getAvailabelWilayah()
    {
        return $this->select('id_wilayah')
            ->distinct()
            ->findAll();
    }
}
