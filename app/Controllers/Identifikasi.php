<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AnadesModel;

class Identifikasi extends BaseController
{
    protected $anadesModel;

    public function __construct()
    {
        $this->anadesModel = new AnadesModel();
    }

    public function anades()
    {
        $idKegiatan = session()->get('aktif_kegiatan') ?? 1; // Default fallback ke 1

        // Ambil data anades variabel
        $dataAnades = $this->anadesModel->getAnadesByKegiatan($idKegiatan);

        // Ambil list dropdown anomali dari DB ex: untuk modal select
        $db = \Config\Database::connect();
        $listAnomali = $db->table('kategori_anomali')
            ->where('id_kegiatan', $idKegiatan)
            ->get()->getResultArray();

        $data = [
            'title'       => 'Analisis Deskriptif & Distribusi Variabel',
            'dataAnades'  => $dataAnades,
            'listAnomali' => $listAnomali
        ];

        return view('identifikasi/anades', $data);
    }

    // Endpoint untuk menyimpan hasil edit via AJAX POST
    public function update()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $rules = [
                'kode_variabel' => 'required',
                'deskripsi'     => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['status' => false, 'msg' => 'Validasi gagal!']);
            }

            $updateData = [
                'kode_variabel'       => $this->request->getPost('kode_variabel'),
                'deskripsi'           => $this->request->getPost('deskripsi'),
                'id_kategori_anomali' => $this->request->getPost('id_kategori_anomali') ?: null
            ];

            if ($this->anadesModel->update($id, $updateData)) {
                return $this->response->setJSON(['status' => true, 'msg' => 'Variabel berhasil diperbarui!']);
            }

            return $this->response->setJSON(['status' => false, 'msg' => 'Gagal mengupdate database.']);
        }
    }
}
