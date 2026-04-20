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
        return $this->response->download(FCPATH . 'assets/templates/template_anomali.xlsx', null);
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
        $file = $this->request->getFile('fileAnom');

        if (!$file->isValid()) return redirect()->back()->with('error', 'File tidak valid');

        // Load spreadsheet
        $spreadsheet = IOFactory::load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $anomalyModel = $this->anomaliModel;
        $errors = []; // Tempat menampung error per baris
        $successCount = 0;

        // Loop mulai dari baris ke-2 (asumsi baris 1 adalah header)
        foreach ($sheetData as $index => $row) {
            if ($index === 0) continue;

            $rowNum = $index + 1; // Untuk penanda baris di pesan error

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
                $errors[$rowNum] = $validation->getErrors();
            } else {
                // 3. Jika lolos validasi, simpan ke database
                // $anomalyModel->insert($data);
                $id_assigment = $this->assigmentModel->getOrInsert($data);
                $listAnomali = $this->anomaliModel->getAnomaliByAssigment($id_assigment);
                // pecah string berdasarkan koma
                $list_anomali = explode(',', $data['anomali']);
                dd($list_anomali);

                $successCount++;
            }
        }
        dd($sheetData);

        // Kirim hasil ke view
        return view('upload_result', [
            'errors' => $errors,
            'successCount' => $successCount
        ]);
    }
}
