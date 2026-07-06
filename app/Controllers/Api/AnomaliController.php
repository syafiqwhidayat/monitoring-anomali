<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AnomaliController extends BaseController
{
    use ResponseTrait;

    public function storeFromApi($jenis = 'anomali_individu')
    {
        // 1. Ambil Parameter Wajib yang Dikirim oleh Python
        $email        = $this->request->getPost('email');
        $password     = $this->request->getPost('password');
        $idKegiatan   = $this->request->getPost('id_kegiatan');
        $levelAnom    = $this->request->getPost('level_anomali'); // Kode Wilayah Kerja
        $fileAnomali  = $this->request->getFile('file_anomali');

        // Validasi kelengkapan parameter teks & file
        if (!$email || !$password || !$idKegiatan || !$levelAnom || !$fileAnomali) {
            return $this->fail('Parameter input atau file tidak lengkap.', 400);
        }

        // 2. AUTENTIKASI USER MENGGUNAKAN SERVICE CODEIGNITER SHIELD (PASTI BEKERJA)
        $auth = auth();

        $credentials = [
            'email'    => $email,
            'password' => $password
        ];

        // 1. Validasi kecocokan email & password terlebih dahulu via Shield
        $result = $auth->check($credentials);

        if (! $result->isOK()) {
            return $this->failUnauthorized('Autentikasi gagal. Email atau password salah.');
        }

        // 2. Cari data user secara pasti dari provider Shield berdasarkan email
        $userProvider = auth()->getProvider();
        $user = $userProvider->findByCredentials(['email' => $email]);

        if (! $user) {
            return $this->fail('User tidak ditemukan dalam sistem database Shield.', 404);
        }

        $idUser = $user->id; // Dapatkan ID User (user_id) untuk dicatat ke upload_logs

        // 3. Proses Pemindahan File yang Dikirim
        if ($fileAnomali->isValid() && !$fileAnomali->hasMoved()) {
            $oldName = $fileAnomali->getName();
            $newName = $fileAnomali->getRandomName();
            $fileAnomali->move(WRITEPATH . 'uploads', $newName);

            $db = \Config\Database::connect();

            // 4. Catat Awal ke Tabel upload_logs (Status: pending)
            // Menggunakan DB Builder langsung jika Anda tidak me-load Model di atas
            $db->table('log_upload')->insert([
                'nama_file'      => $newName,
                'nama_file_awal' => $oldName,
                'status'         => 'pending',
                'id_user'        => $idUser,
                'id_kegiatan'    => $idKegiatan,
                'jenis'          => $jenis,
                'wilayah'        => $levelAnom,
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            $logId = $db->insertID();

            // 5. Susun Command Spark (Windows Environment)
            switch ($jenis) {
                case 'anomali_individu':
                    $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali_individu ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' ' . $idUser . ' > NUL 2>&1"';
                    break;
                case 'anomali_individu_forced':
                    $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali_individu ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' ' . $idUser . ' 1 > NUL 2>&1"';
                    break;
                default:
                    $command = 'cmd /C "start /B php ' . FCPATH . '../spark proses:anomali ' . $newName . ' ' . $logId . ' ' . $idKegiatan . ' ' . $levelAnom . ' > NUL 2>&1"';
                    break;
            }

            // 6. Jalankan Perintah di Background Latar Belakang
            try {
                if (function_exists('popen')) {
                    \pclose(\popen($command, "r"));
                } elseif (function_exists('shell_exec')) {
                    \shell_exec($command);
                } else {
                    // Jika fungsi eksekusi shell di php.ini dimatikan server hosting
                    $db->table('upload_logs')->update([
                        'status' => 'pending',
                        'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => 'Shell_exec mati. File masuk antrian cronjob.']])
                    ], ['id' => $logId]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Gagal memicu spark: ' . $e->getMessage());
            }

            // Respons Sukses ke Python
            return $this->respondCreated([
                'status'  => true,
                'message' => "File {$oldName} berhasil didaftarkan ke log ID {$logId} dan sedang diproses.",
                'log_id'  => $logId
            ]);
        }

        return $this->fail('File rusak atau gagal diunggah ke server.', 400);
    }
}
