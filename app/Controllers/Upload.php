<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
// use Config\Services;
// use Faker\Provider\Lorem;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\AnomalyModel;
use App\Models\AssigmentModel;
use SebastianBergmann\CodeCoverage\Node\File;

use function PHPUnit\Framework\isNull;

class Upload extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;
    protected $assigmentModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
        $this->assigmentModel = new AssigmentModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Upload Anomali'
        ];
        return view('upload/uploadPage', $data);
    }

    public function downloadTemplate()
    {
        return $this->response->download(FCPATH . 'assets\templates\template_anomali.xlsx', null);
    }

    public function get_file()
    {
        $data = [
            'title' => 'Upload Anomali'
        ];

        $fileSam = $this->request->getFile('fileAnom');

        return view('upload/uploadResult', $data);
    }

    public function import()
    {
        // mengambil file
        $file = $this->request->getFile('fileAnom');

        if (!$file->isValid()) return redirect()->back()->with('error', 'File tidak valid');

        // membaca file
        $spreadsheet = IOFactory::load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();


        $anomalyModel = $this->anomaliModel;
        $errors = []; // Tempat menampung error per baris
        $successReport = []; // Mengelompokkan sukses berdasarkan id_wilayah
        $totalSuccess = 0;

        // Loop mulai dari baris ke-2 (asumsi baris 1 adalah header)
        foreach ($sheetData as $index => $row) {
            if ($index === 0) continue;

            $rowNum = $index + 1; // Untuk penanda baris di pesan error

            // Identitas unik untuk laporan error
            $currentIdAssigment = $row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5] . $row[6];

            // Mapping data dari kolom Excel
            $data = [
                'kode_prov' => $row[0],
                'kode_kab'  => $row[1],
                'kode_kec'  => $row[2],
                'kode_desa' => $row[3],
                'kode_sls'  => $row[4],
                'nurt'      => $row[5],
                'nuart'     => $row[6],
                'nama_krt'  => $row[7],
                'nama_art'  => $row[8],
                'anomali'   => $row[9],
                'id_assigment' => $row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5] . $row[6],
            ];

            // 1. Jalankan Validasi CI4
            $validation = \Config\Services::validation();
            $validation->setRules([
                'kode_prov' => 'required|exact_length[2]',
                'kode_kab'  => 'required|exact_length[2]',
                'kode_kec'  => 'required|exact_length[3]',
                'kode_desa' => 'required|exact_length[3]',
                'kode_sls'  => 'required|exact_length[6]',
                'nurt'      => 'required|exact_length[3]',
                'nuart'     => 'required|exact_length[2]',
                'anomali'   => 'required'
            ]);

            if (!$validation->run($data)) {
                // 2. Jika gagal, simpan pesan error berdasarkan nomor baris
                $errors[] = [
                    'baris' => $rowNum,
                    'id_assigment' => $currentIdAssigment,
                    'messages' => $validation->getErrors()
                ];
            } else {
                // 3. Jika lolos validasi, simpan ke database

                // mendapatkan id assigment
                $id_assigment = $this->assigmentModel->getOrInsert($data);

                //  mendapatkan daftar anomali pada assigment tersebut
                $listAnomali = $this->anomaliModel->getAnomaliByAssigment($id_assigment);

                // pecah string berdasarkan koma
                $anomaliTambahan = explode(',', $data['anomali']);
                $anomaliTambahan = array_map('trim', $anomaliTambahan);

                $id_wilayah = substr($id_assigment, 0, 16);

                $this->insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $id_wilayah);

                // Kelompokkan sukses per id_wilayah
                if (!isset($successReport[$id_wilayah])) {
                    $successReport[$id_wilayah] = 0;
                }
                $successReport[$id_wilayah]++;
                $totalSuccess++;
            }
        }

        // Kirim hasil ke view
        return view('upload/uploadResult', [
            'title' => "Result Import anomali",
            'errors' => $errors,
            'successReport' => $successReport,
            'totalSuccess' => $totalSuccess
        ]);
    }

    public function insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $id_wilayah)
    {
        // Reset kolom is insert
        $this->anomaliModel->set('is_insert', 0);

        // cek apakah kategori sudah ada
        // mengembalikan map nilainya
        $mappedKategori = $this->cekOrInsertKategori(1, $anomaliTambahan);


        // cek apakah anomalinya belum ada
        if (isNull($listAnomali)) {
            // tambahkan semua anomali
            foreach ($anomaliTambahan as $anom) {
                $this->anomaliModel->insert(
                    [
                        'id_kategori_anomali' => $mappedKategori[$anom],
                        'id_wilayah' => $id_wilayah,
                        'id_assigment' => $id_assigment,
                        'is_insert' => 1,
                    ]
                );
            };
        } else {
            // ada anomali yg sudah masuk dan ada yg belum.
            // memetakan data lama berdasarkan kat_anomali
            $mappedExisting = [];
            foreach ($listAnomali as $list) {
                $mappedExisting[$list['id_kategori_anomali']] = $list['id'];
            }

            // $listKategori = $this->katAnomaliModel->getIdKategori(1);


            // dd($mappedExisting);

            foreach ($anomaliTambahan as $anom) {
                $data = [
                    'id_kategori_anomali' => $mappedKategori[$anom],
                    'id_wilayah' => $id_wilayah,
                    'id_assigment' => $id_assigment,
                    'is_insert' => 1,
                ];

                if (isset($mappedExisting[$anom])) {
                    // Jika sudah ada. maka update
                    $this->anomaliModel->update($mappedExisting[$anom], $data);
                } else {
                    // jika data belum ada
                    $this->anomaliModel->insert($data);
                }
            }
        }
    }

    public function cekOrInsertKategori($id_kegiatan, $anomaliTambahan)
    {
        // fungsi untuk menambakan kategori yg belum ada dan return nilai id kategorinya jika sudah ada

        $listKategori = $this->katAnomaliModel->getIdKategori($id_kegiatan);
        $mappedKategori = [];
        foreach ($listKategori as $list) {
            $mappedKategori[$list['kode_anomali']] = $list['id'];
        }

        // insert jika belum ada
        foreach ($anomaliTambahan as $anom) {
            if (!isset($mappedKategori[$anom])) {
                $data = [
                    'id_kegiatan' => $id_kegiatan,
                    'kode_anomali' => $anom,
                    'is_show' => 0,
                ];
                $idbaru = $this->katAnomaliModel->insert($data);
                if ($idbaru) {
                    $mappedKategori[$anom] = $idbaru;
                } else {
                    // Jika gagal karena validasi (duplikat), ambil ID yang sudah ada di DB
                    $existing = $this->katAnomaliModel
                        ->where('id_kegiatan', $id_kegiatan)
                        ->where('kode_anomali', $anom)
                        ->first();

                    if ($existing) {
                        $mappedKategori[$anom] = $existing['id'];
                    }
                }
            }
        }
        return ($mappedKategori);
    }
}
