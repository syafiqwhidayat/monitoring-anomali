<?php

namespace App\Controllers;

use App\Models\WilayahTugasModel;
use App\Models\WilUploadLogModel;
use App\Models\UserModel;
use CodeIgniter\Model;

class Wilayah extends BaseController
{
    protected $wilayahTugasModel;
    protected $logModel;

    public function __construct()
    {
        $this->wilayahTugasModel = new WilayahTugasModel();
        $this->logModel = new WilUploadLogModel();
    }
    public function index()
    {
        return null;
    }

    public function manajWilayahTugas()
    {
        // Data Dummy Wilayah Tugas & Mitra
        $data['title'] = "Manajemen Wiayah Tugas";
        $isOrganik = session('isOrganik');

        $userModel = new \App\Models\UserModel;
        $idKegaiatan = session('aktif_kegiatan');

        // isian filter
        $data['id'] = $this->request->getGet('id') ?? null;
        $data['sel_prov'] = $this->request->getGet('sel-prov') ?? 13;
        $data['sel_kab'] = $this->request->getGet('sel-kab') ?? null;
        $data['sel_kec'] = $this->request->getGet('sel-kec') ?? null;
        $data['sel_des'] = $this->request->getGet('sel-des') ?? null;
        $data['sel_keyword'] = $this->request->getGet('sel-keyword') ?? null;

        // list filter
        $data['list_kab'] = $this->wilayahTugasModel->getWilayah('kab', $data['sel_prov']);
        $data['list_kec'] = $this->wilayahTugasModel->getWilayah('kec', $data['sel_prov'], $data['sel_kab']);
        $data['list_des'] = $this->wilayahTugasModel->getWilayah('des', $data['sel_prov'], $data['sel_kab'], $data['sel_kec']);

        // data
        $data['list_user'] = $this->wilayahTugasModel->getUserByKegiatan($idKegaiatan);
        $data['wilayah_tugas'] = $this->wilayahTugasModel->getWilayahTugasByKegaitan($idKegaiatan, $data['sel_kab'], $data['sel_kec'], $data['sel_des'], $data['sel_keyword']);

        return view('manajWilayah/manajWilayahTugas', $data);
    }

    public function upload()
    {
        // Logika import Excel/CSV master wilayah tugas
        return redirect()->back()->with('success', 'Master wilayah berhasil diupload.');
    }

    public function store()
    {
        $file = $this->request->getFile('file_wilayah');
        // dd($file->hasMoved());

        if ($file->isValid() && !$file->hasMoved()) {
            // 1. Simpan file ke folder writable/uploads
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $idKegiatan = session('aktif_kegiatan');
            $wilayahTugas = auth()->user()->wilayah_kerja;

            // 2. Buat catatan awal di tabel upload_logs (status: pending)
            $wilayahLogModel = new \App\Models\WilUploadLogModel();
            $logId = $wilayahLogModel->insert([
                'nama_file' => $newName,
                'status'    => 'pending',
                'id_user'   => auth()->id(), // Siapa yang upload
                'id_kegiatan' => $idKegiatan,
            ]);

            // 3. PANGGIL COMMAND DI BACKGROUND
            // Kita kirimkan Nama File dan ID Log sebagai parameter
            // Tanda '&' di akhir perintah adalah kunci agar berjalan di background
            // $command = "php " . FCPATH . "../spark proses:wilayah " . $newName . " " . $logId . " > /dev/null 2>&1 &"; //command linux
            $command = "start /B php " . FCPATH . "../spark proses:wilayah " . $newName . " " . $logId . " " . $idKegiatan . " " . $wilayahTugas; //command windows
            shell_exec($command);

            return redirect()->to('/wilayah/logs')->with('message', 'Upload berhasil! Sistem sedang memproses data di latar belakang.');
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }

    public function logsWilayah()
    {
        $data['title'] = 'Log Upload Wilayah';
        $data['logs'] = [
            (object) [
                'id'            => 1,
                'nama_file'     => 'daftar_ppl_kec_pulau_punjung.xlsx',
                'status'        => 'selesai',
                'total_baris'   => 150,
                'berhasil'      => 145,
                'gagal'         => 5,
                'error_details' => json_encode([
                    ['baris' => 12, 'data' => 'Budi Santoso', 'pesan' => ['Email bukan domain @bps.go.id']],
                    ['baris' => 45, 'data' => 'Susi Susanti', 'pesan' => ['Wilayah kerja tidak terdaftar']]
                ]),
                'created_at'    => '2026-04-27 09:00:00'
            ],
            (object) [
                'id'            => 2,
                'nama_file'     => 'data_pml_dharmasraya_final.xlsx',
                'status'        => 'proses',
                'total_baris'   => 500,
                'berhasil'      => 210,
                'gagal'         => 0,
                'error_details' => '[]',
                'created_at'    => '2026-04-27 10:15:30'
            ],
            (object) [
                'id'            => 3,
                'nama_file'     => 'wilayah_tugas_mitra_2026.xlsx',
                'status'        => 'gagal',
                'total_baris'   => 0,
                'berhasil'      => 0,
                'gagal'         => 0,
                'error_details' => json_encode([
                    ['baris' => '-', 'data' => 'Sistem', 'pesan' => ['File corrupt atau format tidak sesuai']]
                ]),
                'created_at'    => '2026-04-26 14:20:00'
            ],
            (object) [
                'id'            => 4,
                'nama_file'     => 'rekap_desa_nagari.xlsx',
                'status'        => 'pending',
                'total_baris'   => 85,
                'berhasil'      => 0,
                'gagal'         => 0,
                'error_details' => '[]',
                'created_at'    => '2026-04-27 10:30:00'
            ],
        ];
        $datalog = $this->logModel->select('wilayah_upload_log.*,idn.secret AS email')
            ->join('auth_identities idn', 'id_user = idn.user_id')
            ->where('id_kegiatan', session()->get('aktif_kegiatan'))->orderBy('created_at', 'DESC');

        $data['logs'] = $datalog->findAll();
        // dd($data['logs']);
        // $data['logs'] = $logModel->select('*')->orderBy('created_at', 'DESC')->findAll();

        return view('manajWilayah/logUploadWilayahTugas', $data);
    }

    public function logDetil($id)
    {
        // Simulasi Data Dummy berdasarkan ID
        // Dalam aplikasi nyata, data ini diambil dari $logModel->find($id)
        $dummyLogs = [
            1 => [
                ['baris' => 5, 'data' => 'Ahmad Fauzi', 'pesan' => 'Email tidak valid (harus domain @bps.go.id)'],
                ['baris' => 12, 'data' => 'Siti Aminah', 'pesan' => 'Kode Wilayah (3201) tidak ditemukan di database'],
                ['baris' => 45, 'data' => 'Budi Sudarsono', 'pesan' => 'Jabatan tidak sesuai dengan referensi'],
            ],
            2 => [
                ['baris' => 2, 'data' => 'Tono Supono', 'pesan' => 'Data NIP duplikat dengan baris sebelumnya'],
            ]
        ];
        $logModel = model('WilUploadLogModel');
        $log = $logModel->find($id);
        $errors = json_decode($log->error_details, true);



        // Ambil data berdasarkan ID yang dikirim dari View, jika tidak ada pakai array kosong
        // $errors = isset($dummyLogs[$id]) ? $dummyLogs[$id] : [];

        // Jika ID tidak ditemukan di dummy
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
            if (is_array($err['pesan'])) {
                $pesanTampil = implode(', ', $err['pesan']);
            } else {
                $pesanTampil = $err['pesan'];
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

        return $html;
    }

    public function downloadTemplate()
    {
        return $this->response->download(FCPATH . 'assets\templates\template_wilayah_tugas.xlsx', null);
    }

    public function edit()
    {
        $data['id'] = $this->request->getGet('id') ?? null;
        $data['id_ppl'] = $this->request->getGet('sel-ppl') ?? null;
        $data['id_pml'] = $this->request->getGet('sel-pml') ?? null;

        $updateData = [];

        if (!empty($data['id_ppl'])) {
            $updateData['id_ppl'] = $data['id_ppl'];
        }

        if (!empty($data['id_pml'])) {
            $updateData['id_pml'] = $data['id_pml'];
        }

        $wiltug = null;

        if (!empty($updateData)) {
            $wiltug = $this->wilayahTugasModel->update($data['id'], $updateData);
        }

        if ($wiltug) {
            return redirect()->back()->with('message', 'User berhasil diedit');
        } else {
            return redirect()->back()->withInput()->with('errors', 'gagal edit wilayah tugas');
        }
    }
}
