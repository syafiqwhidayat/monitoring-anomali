<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\LogUploadModel;
use Config\Services;
use Faker\Provider\Lorem;

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
            ->where('jenis', 'anomali')
            ->orderBy('created_at', 'DESC');

        if ($data['filterLevel'] != '') {
            $datalog->where('log_upload.wilayah', $data['filterLevel']);
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
        if ($kategoriAnom['level_anomali'] !== auth()->user()->wilayah_kerja) {
            return redirect()->back()->with('error', 'User tidak punya akses edit Kategori Anomali');
        }

        if ($action === "toggle") {
            $is_show = $this->request->getVar('is_show');
            $data = ['is_show' => !$is_show];
            $this->katAnomaliModel->update($id, $data);
            return redirect()->back()->with('message', 'Status Lihat diubah');
        } elseif ($action === "delete") {
            $this->katAnomaliModel->delete($id);
            return redirect()->back()->with('message', 'anomali berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'aksi tidak valid');
        }
        // return view('anomali/manajemen');
    }

    public function edit($id)
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        $data['data'] = $this->katAnomaliModel->find($id);
        // dd($data['data']);

        return view('ManajAnom/edit', $data);
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

    public function downloadTemplate()
    {
        return $this->response->download(FCPATH . 'assets\templates\template_anomali.xlsx', null);
    }

    public function store()
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

            // 2. Buat catatan awal di tabel upload_logs (status: pending)
            $logId = $this->logModel->insert([
                'nama_file' => $newName,
                'nama_file_awal' => $oldName,
                'status'    => 'pending',
                'id_user'   => auth()->id(), // Siapa yang upload
                'id_kegiatan' => session()->get('aktif_kegiatan'),
                'jenis' => 'anomali',
                'wilayah' => auth()->user()->wilayah_kerja,
            ]);

            // 3. PANGGIL COMMAND DI BACKGROUND
            // Kita kirimkan Nama File dan ID Log sebagai parameter
            // Tanda '&' di akhir perintah adalah kunci agar berjalan di background
            // $command = "php " . FCPATH . "../spark proses:wilayah " . $newName . " " . $logId . " > /dev/null 2>&1 &"; //command linux
            // $command = "start /B php " . FCPATH . "../spark proses:anomali " . $newName . " " . $logId . " " . $idKegiatan .  " " . $levelAnom; //command windows
            // $command = "start /B php " . FCPATH . "../spark proses:anomali " . $newName . " " . $logId . " " . $idKegiatan . " " . $levelAnom . " > NUL 2> NUL";
            $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' > NUL 2>&1"';
            // shell_exec($command);
            pclose(popen($command, "r"));

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
