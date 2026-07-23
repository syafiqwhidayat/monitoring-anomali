<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class KategorikController extends BaseController
{
    use ResponseTrait;

    public function uploadCsv()
    {
        // 1. Validasi Input Form
        $rules = [
            'id_kegiatan' => 'required|numeric',
            'file_csv'    => 'uploaded[file_csv]|ext_in[file_csv,csv]|max_size[file_csv,4096]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $idKegiatan = $this->request->getPost('id_kegiatan');
        $file = $this->request->getFile('file_csv');

        if (!$file->isValid()) {
            return $this->fail($file->getErrorString());
        }

        $filePath = $file->getTempName();

        // 2. Baca CSV Menggunakan SplFileObject (Tahan terhadap 'New Line' di dalam kolom JSON)
        try {
            $fileObj = new \SplFileObject($filePath);
            $fileObj->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
            $fileObj->setCsvControl(',', '"', '\\');
        } catch (\Exception $e) {
            return $this->fail('Gagal membaca file CSV: ' . $e->getMessage());
        }

        // Ambil baris pertama sebagai header
        $header = $fileObj->current();
        if (!$header) {
            return $this->fail('File CSV kosong.');
        }

        // Bersihkan header dari karakter aneh / BOM UTF-8
        $header = array_map(function ($h) {
            return trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h));
        }, $header);

        // Map nama kolom ke indeks array
        $colMap = array_flip($header);

        // Validasi kolom minimal yang wajib ada
        $requiredColumns = ['kode_wilayah', 'kode_variabel', 'jenis', 'data'];
        foreach ($requiredColumns as $col) {
            if (!isset($colMap[$col])) {
                return $this->fail("Kolom wajib '{$col}' tidak ditemukan di CSV.");
            }
        }

        // 3. Ambil Data Eksisting Menggunakan $db Murni
        $db = \Config\Database::connect();
        $builder = $db->table('identifikasi_kategori'); // Sesuaikan dengan nama tabel asli Anda

        $existingRows = $builder->where('id_kegiatan', $idKegiatan)->get()->getResultArray();
        $existingMap = [];
        foreach ($existingRows as $exist) {
            $mapKey = $exist['kode_wilayah'] . '|' . $exist['kode_variabel'];
            $existingMap[$mapKey] = $exist;
        }

        $dataToInsert = [];
        $dataToUpdate = [];
        $rowCount = 0;

        // Pindah ke baris kedua (lewati header)
        $fileObj->next();

        // 4. Looping Baris Data CSV
        while (!$fileObj->eof()) {
            $row = $fileObj->current();
            $fileObj->next();

            // Skip jika baris kosong atau tidak valid
            if (empty($row) || count($row) < count($header) || $row[0] === null) {
                continue;
            }

            $kodeWilayah  = trim($row[$colMap['kode_wilayah']]);
            $kodeVariabel = trim($row[$colMap['kode_variabel']]);
            $deskripsi    = isset($colMap['deskripsi']) ? trim($row[$colMap['deskripsi']]) : '';
            $jenis    = isset($colMap['jenis']) ? trim($row[$colMap['jenis']]) : 'mono';

            // Ambil numerik secara aman
            // $n_batas_bawah = isset($colMap['n_batas_bawah']) ? (float)$row[$colMap['n_batas_bawah']] : 0.0;
            // $n_q1          = isset($colMap['n_q1'])          ? (float)$row[$colMap['n_q1']] : 0.0;
            // $median        = isset($colMap['median'])        ? (float)$row[$colMap['median']] : 0.0;
            // $n_q3          = isset($colMap['n_q3'])          ? (float)$row[$colMap['n_q3']] : 0.0;
            // $n_batas_atas  = isset($colMap['n_batas_atas'])  ? (float)$row[$colMap['n_batas_atas']] : 0.0;
            // $n_rata        = isset($colMap['n_rata'])        ? (float)$row[$colMap['n_rata']] : 0.0;

            // Bersihkan format JSON string Python (ubah ' ke ")
            $dataJson = null;
            if (isset($colMap['data'])) {
                $rawData = trim($row[$colMap['data']]);
                if (!empty($rawData) && $rawData !== 'NULL' && $rawData !== '[]') {
                    $dataJson = str_replace("'", '"', $rawData);
                }
            }

            // $histogramJson = null;
            // if (isset($colMap['n_histogram'])) {
            //     $rawHistogram = trim($row[$colMap['n_histogram']]);
            //     if (!empty($rawHistogram) && $rawHistogram !== 'NULL' && $rawHistogram !== '{}') {
            //         $histogramJson = str_replace("'", '"', $rawHistogram);
            //     }
            // }

            $currentKey = $kodeWilayah . '|' . $kodeVariabel;

            $data = [
                'id_kegiatan'   => $idKegiatan,
                'kode_wilayah'  => $kodeWilayah,
                'kode_variabel' => $kodeVariabel,
                'deskripsi'     => $deskripsi,
                'jenis'         => $jenis,
                'data'          => $dataJson,
                // 'n_batas_bawah' => $n_batas_bawah,
                // 'n_q1'          => $n_q1,
                // 'median'        => $median,
                // 'n_q3'          => $n_q3,
                // 'n_batas_atas'  => $n_batas_atas,
                // 'n_rata'        => $n_rata,
                // 'n_outlier'     => $outlierJson,
                // 'n_histogram'   => $histogramJson,
            ];

            if (isset($existingMap[$currentKey])) {
                // UPDATE: Simpan ID target untuk klausa WHERE nanti
                $data['id'] = $existingMap[$currentKey]['id'];
                $dataToUpdate[] = $data;
            } else {
                // INSERT: Set kategori anomali awal null jika data baru
                $data['id_kategori_anomali'] = null;
                $dataToInsert[] = $data;
            }

            $rowCount++;
        }

        // 5. Eksekusi Batch Menggunakan $db Murni (Query Builder)
        $db->transStart();

        if (!empty($dataToInsert)) {
            $builder->insertBatch($dataToInsert);
        }

        if (!empty($dataToUpdate)) {
            // updateBatch menggunakan $db murni membutuhkan parameter nama field primary key di argumen kedua
            $builder->updateBatch($dataToUpdate, 'id');
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return $this->fail('Terjadi kesalahan saat memproses simpan database menggunakan DB Builder.');
        }

        return $this->respondCreated([
            'status'  => true,
            'message' => "Berhasil memproses (Murni \$db) sebanyak {$rowCount} baris data identifikasi kategori!",
        ]);
    }
}
