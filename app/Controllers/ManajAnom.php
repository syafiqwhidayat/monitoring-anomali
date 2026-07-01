<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\LogUploadModel;
use Config\Services;
use Faker\Provider\Lorem;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ManajAnom extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;
    protected $logModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
        $this->logModel = new LogUploadModel();
    }

    public function manajemenList()
    {
        $perPage = 10;
        $idKegiatanAktif = session()->get('aktif_kegiatan');
        $data['title'] = "Manajemen Anomali";

        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        $data['filterFlag'] = $this->request->getGet('fil-flag') ?? '';
        $data['message'] = null;
        $isRT = (session()->get('is_rt') == 1);

        // data filter
        $data['listLevel'] = [
            [
                'id' => '',
                'nama' => "Semua Anomali",
            ],
        ];
        $data['listSelFlag'] = [];
        $data['listSelKdAnom'] = [
            [
                'id' => '',
                'nama' => 'Semua Anomali',
            ],
        ];

        $listSelKdAnom = $this->anomaliModel->getKdAnomaliByUser() ?? [];
        $listSelFlag = $this->anomaliModel->getFlagByUser() ?? [];
        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];

        $data['listSelKdAnom'] = array_merge($data['listSelKdAnom'], $listSelKdAnom ?? []);
        $data['listSelFlag'] = array_merge($data['listSelFlag'], $listSelFlag ?? []);
        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel ?? []);

        // data isian
        $model = $this->katAnomaliModel->getFilterKategori($idKegiatanAktif, $data['filterLevel'], $data['filterFlag']);

        $data['listAnom'] = $model->paginate($perPage, 'default');
        $data['pager'] = $model->pager;
        $data['currentPage'] = $model->pager->getCurrentPage();

        return view('manajAnom/manajemen', $data);
    }

    public function log()
    {
        $data['title'] = "Log Upload";
        $userWilayah = auth()->user()->wilayah_kerja;

        // filter
        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';
        // data filter
        $data['listLevel'] = [
            [
                'id' => '',
                'nama' => "Semua Anomali",
            ],
        ];
        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];
        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel ?? []);

        $data['logs'] = [];

        $datalog = $this->logModel->select('log_upload.*,idn.secret AS email')
            ->join('auth_identities idn', 'id_user = idn.user_id')
            ->where('id_kegiatan', session()->get('aktif_kegiatan'))
            ->whereIn('jenis', ['anomali', 'anomali_individu', 'anomali_individu_forced'])
            ->orderBy('created_at', 'DESC');

        if ($data['filterLevel'] != '') {
            $datalog->where('log_upload.wilayah', $data['filterLevel']);
        }
        if ($userWilayah !== '1300') {
            $datalog->where('wilayah', $userWilayah);
        }

        $data['logs'] = $datalog->findAll();


        return view('manajAnom/logUploadAnom', $data);
    }

    public function manajemenAction()
    {
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

        // cek apakah user punya hak update
        $kategoriAnom = $this->katAnomaliModel->find($id);
        if (!$kategoriAnom) {
            return redirect()->back()->with('error', 'Data Kategori Anomali tidak ditemukan.');
        }

        // PENGAMAN 2: Cek hak akses wilayah kerja (Gunakan substr jika panjang kode wilayah berbeda)
        $userWilayah = auth()->user()->wilayah_kerja; // Misal: 1311

        if ($kategoriAnom['level_anomali'] !== $userWilayah) {
            return redirect()->back()->with('error', 'User tidak punya akses edit Kategori Anomali');
        }

        if ($action === "toggle") {
            $currentShow = (int) $kategoriAnom['is_show'];
            $data        = ['is_show' => $currentShow === 1 ? 0 : 1];

            $this->katAnomaliModel->update($id, $data);
            return redirect()->back()->with('message', 'Status keterlihatan anomali berhasil diubah.');
        } elseif ($action === "delete") {
            $db = \Config\Database::connect();
            $db->transStart();

            // Hapus relasi anak
            $this->anomaliModel->where('id_kategori_anomali', $id)->delete();
            // Hapus master induk
            $this->katAnomaliModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal menghapus data anomali terkait.');
            }

            return redirect()->back()->with('message', 'Kategori dan seluruh data anomali terkait berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'aksi tidak valid');
        }
    }

    public function edit($id)
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        $data['data'] = $this->katAnomaliModel->find($id);
        // dd($data['data']);

        return view('manajAnom/edit', $data);
    }

    public function updateKategori()
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        // data yg akan diupdate
        $data = $this->request->getPost();
        $id = $data['id'];
        unset($data['id']);
        unset($data['kode_anomali']);

        // cek apakah user punya hak update
        $kategoriAnom = $this->katAnomaliModel->find($id);
        if ($kategoriAnom['level_anomali'] !== auth()->user()->wilayah_kerja) {
            return redirect()->to(base_url('/manajemen-anomali/list'))->with('error', 'User tidak punya akses edit Kategori Anomali');
        }



        // memastikan show untuk id yg sama
        if ($data['is_show'] == "show_id_" . $id) {
            $data['is_show'] = true;
        } else {
            $data['is_show'] = false;
        }


        if ($this->katAnomaliModel->update($id, $data) === false) {
            session()->setFlashdata($this->katAnomaliModel->errors());
            return redirect()->back()
                ->withInput()
                ->with('message_errors', 'Gagal Simpan Data');
        }

        return redirect()->to(base_url('/manajemen-anomali/list'))->with('message', 'data berhasil di update');
    }

    public function downloadTemplate($jenis = 'anomali')
    {
        // return $this->response->download(FCPATH . 'assets\templates\template_anomali.xlsx', null);
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Atur Header & Contoh Data Berdasarkan Jenis
        if ($jenis === 'anomali') {
            $title = "template_anomali.xlsx";
            $headers = ['KODE PROV', 'KODE KAB', 'KODE KEC', 'KODE DESA', 'KODESUBSLS', 'ID-ASSIGMENT', 'KD-ROSTER', 'NAMA KRT/USAHA', 'NAMA ROSTER', 'KODE ANOMALI'];
            $example = ['13', '11', '010', '002', '001200', 'uenl-f51sdffe-fdsfasdf-4525dd', 'K1', 'BUDI', 'ANI', '00-AN001;00-AN002'];
        } else {
            // Berlaku untuk anomali_individu maupun anomali_individu_forced (strukturnya sama)
            $title = "template_anomali_individu.xlsx";
            // Index [0] sampai [6] rekonstruksi id_assignment, [9] kode_anomali, [10] isi_fasih, [8] konfirmasi
            $headers = ['KODE PROV', 'KODE KAB', 'KODE KEC', 'KODE DESA', 'KODESUBSLS', 'ID-ASSIGMENT', 'KD-ROSTER', 'NAMA KRT/USAHA', 'NAMA ROSTER', 'KODE ANOMALI', 'DATA FASIH', 'KONFIRMASI'];
            $example = ['13', '11', '010', '002', '001200', 'uenl-f51sdffe-fdsfasdf-4525dd', 'K1', 'BUDI', 'ANI', '00-AN001', 'Jumlah Pendapatan = 340000', ''];
        }

        // Tulis Header ke Baris 1
        foreach ($headers as $colIndex => $headerText) {
            // Mengubah indeks angka (1, 2, 3...) menjadi huruf kolom ('A', 'B', 'C'...)
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);

            // Tulis nilai ke koordinat sel, contoh: A1, B1, C1...
            $sheet->setCellValue($colLetter . '1', $headerText);

            // Set font menjadi Bold
            $sheet->getStyle($colLetter . '1')->getFont()->setBold(true);
        }

        // Tulis Contoh Data ke Baris 2
        foreach ($example as $colIndex => $exampleValue) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);

            // Tulis nilai ke baris 2, contoh: A2, B2, C2...
            $sheet->setCellValue($colLetter . '2', $exampleValue);
        }

        // Auto-size kolom agar rapi saat dibuka user
        foreach (range(1, count($headers)) as $colID) {
            $colLetter = Coordinate::stringFromColumnIndex($colID);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Stream ke Browser untuk didownload
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $title . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function store($jenis = 'anomali')
    {
        $file = $this->request->getFile('file_anomali');

        if ($file->isValid() && !$file->hasMoved()) {
            // 1. Simpan file ke folder writable/uploads
            $oldName = $file->getName();
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $idKegiatan = session()->get('aktif_kegiatan');
            $isRT = session()->get('is_rt');
            $levelAnom = auth()->user()->wilayah_kerja;
            $idUser = auth()->id();

            // 2. Buat catatan awal di tabel upload_logs (status: pending)
            $logId = $this->logModel->insert([
                'nama_file' => $newName,
                'nama_file_awal' => $oldName,
                'status'    => 'pending',
                'id_user'   => $idUser, // Siapa yang upload
                'id_kegiatan' => $idKegiatan,
                'jenis' => $jenis,
                'wilayah' => $levelAnom,
            ]);

            // 3. PANGGIL COMMAND DI BACKGROUND
            // Kita kirimkan Nama File dan ID Log sebagai parameter
            // Tanda '&' di akhir perintah adalah kunci agar berjalan di background
            // $command = "php " . FCPATH . "../spark proses:wilayah " . $newName . " " . $logId . " > /dev/null 2>&1 &"; //command linux
            // $command = "start /B php " . FCPATH . "../spark proses:anomali " . $newName . " " . $logId . " " . $idKegiatan .  " " . $levelAnom; //command windows
            // $command = "start /B php " . FCPATH . "../spark proses:anomali " . $newName . " " . $logId . " " . $idKegiatan . " " . $levelAnom . " > NUL 2> NUL";
            // shell_exec($command);
            switch ($jenis) {
                case 'anomali_individu':
                    $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali_individu ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' ' . $idUser . ' > NUL 2>&1"';
                case 'anomali_individu_forced':
                    $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali_individu ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' ' . $idUser . ' 1 > NUL 2>&1"';
                default:
                    $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' > NUL 2>&1"';
            }
            $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' > NUL 2>&1"';
            // 2. Gunakan blok try-catch untuk menangkap jika fungsi dilarang
            try {
                if (function_exists('popen')) {
                    \pclose(\popen($command, "r"));
                } elseif (function_exists('shell_exec')) {
                    \shell_exec($command);
                } else {
                    // Catat di log jika semua fungsi eksekusi mati
                    log_message('error', 'Semua fungsi eksekusi shell (popen, shell_exec) dinonaktifkan di server.');

                    // buat agar pesan akan di proses secara berkala
                    $this->logModel->update($logId, [
                        'status' => 'pending',
                        'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => 'File akan di proses secara berkala sesuai antrian']])
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
            }

            return redirect()->to('/manajemen-anomali/log')->with('message', 'Upload berhasil! Sistem sedang memproses data di latar belakang.');
        }
    }

    public function logDetil($id)
    {
        $log = $this->logModel->find($id);
        $errors = json_decode($log['error_details'], true);
        // dd($errors);
        $data['errors'] = $errors;

        if (empty($errors)) {
            return '
        <div class="text-center p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
            <p class="text-secondary">Tidak ada rincian kesalahan untuk ID ini atau proses berhasil 100%.</p>
        </div>';
        }

        // Susun Tabel HTML dengan format Tabler
        $html = '<div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th class="w-1">Baris</th>
                            <th>Nama/Data</th>
                            <th>Keterangan Error</th>
                        </tr>
                    </thead>
                    <tbody>';

        // dd($errors);
        foreach ($errors as $err) {
            $pesanTampil = '';
            if (is_array($err['messages'])) {
                $pesanTampil = implode(', ', $err['messages']);
            } else {
                $pesanTampil = $err['messages'];
            }
            $html .= '<tr>
                    <td><span class="badge bg-red-lt">' . $err['baris'] . '</span></td>
                    <td class="small fw-bold text-uppercase">' . $err['data'] . '</td>
                    <td class="text-danger small">' . $pesanTampil . '</td>
                  </tr>';
        }

        $html .= '    </tbody>
                </table>
            </div>';

        return view('log_upload/log_comp_error', $data);
    }
}
