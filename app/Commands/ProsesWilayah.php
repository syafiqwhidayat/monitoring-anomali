<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\UserModel;
use App\Models\WilayahTugasModel;
use App\Models\KegiatanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use SebastianBergmann\Environment\Console;

class ProsesWilayah extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';
    protected $wilayah_kerja = '1311';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'proses:wilayah';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Memproses upload data wilayah dari Excel di background.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'proses:wialyah [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */

    protected $mapUserName = [];
    protected $mapUsers = [];


    public function run(array $params)
    {
        // 1. Ambil nama file dan ID Log dari parameter
        $fileName = $params[0] ?? null;
        $logId = $params[1] ?? null;
        $idKegiatan    = $params[2] ?? null;
        $wilayahKerja    = $params[3] ?? null;

        // cek apakah parameter langkap.
        if (!$fileName || !$logId || !$idKegiatan || !$wilayahKerja) {
            CLI::error("Nama file atau ID Log tidak ditemukan.");
            return;
        }

        // cek provinsi tidak bisa menambahkan wilayah tugas
        if ($wilayahKerja === '1300') {
            CLI::error("Tidak bisa untuk upload wilayah tugas 1300");
            return;
        }



        // inisiasi model
        $kegiatanModel = new KegiatanModel();
        $logModel  = new LogUploadModel();
        $userModel = new UserModel();
        $wilayaTugasModel = new WilayahTugasModel();

        // mengamambil kegiatan tertentu
        $kegiatan = $kegiatanModel->find($idKegiatan);

        // mengambil level pada kegiatan. default nya 4
        $levelWilayah       = $kegiatan['level_wilayah'] ?? 4;

        // Update status log menjadi 'proses'
        $logModel->update($logId, ['status' => 'proses']);

        // mapped user
        $listUser = $userModel
            ->distinct()
            ->from('users u')
            ->select('u.id as id,u.username as username, u.wilayah_kerja as wilayah_kerja,iden.secret as email')
            ->join('auth_identities iden', 'u.id=iden.user_id')
            ->asArray()->findAll();

        if (!empty($listUser)) {
            foreach ($listUser as $use) {
                $this->mapUsers[strtolower($use['email'])] = ['id' => $use['id'], 'wilayah_kerja' => $use['wilayah_kerja'], 'username' => $use['username']];
                $this->mapUserName[$use['username']] = ['id' => $use['id']];
            }
        }

        $mapWilayahTugas = [];
        $listWilayahTugas = $wilayaTugasModel->where([
            'id_kegiatan' => $idKegiatan
        ])->asArray()->findAll();
        if (!empty($listWilayahTugas)) {
            foreach ($listWilayahTugas as $tugas) {
                $mapWilayahTugas[$tugas['id_wilayah']] = $tugas['id'];
            }
        }

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $totalBaris = count($sheetData) - 1; // Kurangi 1 karena header
            $berhasil = 0;
            $gagal = 0;
            $errorDetails = [];

            $db = \Config\Database::connect();
            $db->transStart(); // Mulai Transaksi

            // 3. Validasi Manual
            $validation = \Config\Services::validation();


            // 2. Mulai membaca data (Skip baris 0/header)
            for ($i = 1; $i <= $totalBaris; $i++) {
                $row = $sheetData[$i];
                CLI::write("sedang proses bari ke- $i");

                // data ppl dan pml
                $dataUserPPL = [
                    'email'         => strtolower($row[7]), // Kolom H
                    'name'          => ucwords($row[7]),
                    'wilayah_kerja' => $wilayahKerja, // Kolom C
                    'username'      => $this->generateUniqueUsername($row[7]), // Jalankan fungsi untuk generate username
                ];
                $dataUserPML = [
                    'email'         => strtolower($row[6]), // Kolom H
                    'name'         =>  ucwords($row[6]), // Kolom H
                    'wilayah_kerja' => $wilayahKerja, // Kolom C
                    'username'      => $this->generateUniqueUsername($row[6]), // Jalankan fungsi untuk generate username
                ];

                $ruleEmail = [
                    'email' => "required|valid_email",
                ];


                $validation->reset();
                $validation->setRules($ruleEmail);

                if (!$validation->run($dataUserPPL)) {
                    $gagal++;
                    $errorDetails[] = [
                        'baris' => $i + 1,
                        'data'  => $dataUserPPL['name'] ?? 'Baris ' . ($i + 1),
                        'pesan' => $validation->getErrors()
                    ];
                    continue;
                }
                // validasi Email PML
                if (!$validation->run($dataUserPML)) {
                    $gagal++;
                    $errorDetails[] = [
                        'baris' => $i + 1,
                        'data'  => $dataUserPML['name'] ?? 'Baris ' . ($i + 1),
                        'pesan' => $validation->getErrors()
                    ];
                    continue;
                }

                // mengkalkulasi id wilayah
                $idWilayah = (trim($row[0] ?? '')) . (trim($row[1] ?? '')) . (trim($row[2] ?? '')) . (trim($row[3] ?? '')) . (trim($row[4] ?? '')) . (trim($row[5] ?? ''));
                $wilayahData = [
                    'idWilayah' => $idWilayah,
                ];

                // validasi id wilayah
                $validation->reset();
                $ruleWilayah = [
                    'idWilayah' => "required|exact_length[$levelWilayah]|is_not_unique[wilayah.id]"
                ];
                $messageWilayah = [
                    'idWilayah' => [
                        'exact_length' => "id wialyah tidak sesuai. harusnya  $levelWilayah digit",
                        'is_not_unique' => "kode wilayah tidak terdefinisi di database",
                        'regex_match' => "anda tidak punya akses untuk wilayah: $idWilayah",
                    ]
                ];
                // menambahkan rule jika untuk kabupaten kota tertentu
                if ($wilayahKerja !== '1300') {
                    $ruleWilayah['idWilayah'] .= "|regex_match[/^$wilayahKerja/]";
                }
                $validation->setRules($ruleWilayah, $messageWilayah);

                // validasi Wilayah
                if (!$validation->run($wilayahData)) {
                    $gagal++;
                    $errorDetails[] = [
                        'baris' => $i + 1,
                        'data'  => $wilayah['idWilayah'] ?? 'Baris ' . ($i + 1),
                        'pesan' => $validation->getErrors()
                    ];
                    continue;
                }

                // Jika user belum ada, Simpan User ke Database
                if (!isset($this->mapUsers[$dataUserPPL['email']])) {
                    $userPPL = new \App\Entities\User($dataUserPPL);
                    $userPPL->password = 'password123'; // Password default
                    if ($userModel->save($userPPL)) {
                        $userPPL->id = $userModel->getInsertID();
                        if (str_contains($dataUserPPL['email'], '@bps.go.id')) {
                            $userPPL->addGroup('operator');
                        }
                        // semua akun punya role mitra
                        $userPPL->addGroup('mitra');
                        $this->mapUsers[$dataUserPPL['email']]['id'] = $userPPL->id;
                        $this->mapUsers[$dataUserPPL['email']]['wilayah_kerja'] = $dataUserPPL['wilayah_kerja'];
                        $this->mapUserName[$dataUserPPL['username']] = $userPPL->id;
                    } else {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => $dataUserPPL['email'],
                            'pesan' => $userModel->errors()
                        ];
                        continue;
                    }
                } else {
                    // jika usernya ditemukan
                    // cek apakah user berada pada wilayah_kerja yang sesuai?
                    if ($this->mapUsers[$dataUserPPL['email']]['wilayah_kerja'] !== $wilayahKerja) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => $dataUserPPL['email'],
                            'pesan' => "User tidak berada di wilayah kerja $wilayahKerja"
                        ];
                        continue;
                    }
                }

                if (!isset($this->mapUsers[$dataUserPML['email']])) {
                    $userPML = new \App\Entities\User($dataUserPML);
                    $userPML->password = 'password123'; // Password default
                    if ($userModel->save($userPML)) {
                        CLI::write('berhasil dijalankan');
                        $userPML->id = $userModel->getInsertID();
                        if (str_contains($dataUserPML['email'], '@bps.go.id')) {
                            $userPML->addGroup('operator');
                        }
                        // setiap organik punya role mitra
                        $userPML->addGroup('mitra');
                        $this->mapUsers[$dataUserPML['email']]['id'] = $userPML->id;
                        $this->mapUsers[$dataUserPML['email']]['wilayah_kerja'] = $dataUserPML['wilayah_kerja'];
                        $this->mapUserName[$dataUserPML['username']] = $userPML->id;
                    } else {
                        CLI::write('gagal ini dijalankan');
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => $dataUserPML['email'],
                            'pesan' => $userModel->errors()
                        ];
                        continue;
                    }
                } else {
                    // jika user ditemukan
                    // cek apakah user berada pada wilayah_kerja yang sesuai?
                    if ($this->mapUsers[$dataUserPML['email']]['wilayah_kerja'] !== $wilayahKerja) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => $dataUserPML['email'],
                            'pesan' => "User tidak berada di wilayah kerja $wilayahKerja"
                        ];
                        continue;
                    }
                }

                $dataWilayahTugas = [
                    // 'id_wilayah' => $idWilayah,
                    // 'id_kegiatan' => $idKegiatan,
                    'id_ppl' => $this->mapUsers[$dataUserPPL['email']]['id'],
                    'id_pml' => $this->mapUsers[$dataUserPML['email']]['id'],
                ];

                if (isset($mapWilayahTugas[$idWilayah])) {
                    // Jika wilayah tugas sudah ada, lakukan Update berdasarkan ID primer yang ditemukan
                    $dad = $mapWilayahTugas[$idWilayah];
                    CLI::write($dad);
                    if (!$wilayaTugasModel->update($mapWilayahTugas[$idWilayah], $dataWilayahTugas)) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => "ID Wilayah: " . $idWilayah,
                            'pesan' => "Gagal Mengubah Wilayah Tugas Sebelumnya",
                        ];
                        continue;
                    }
                } else {
                    // Jika wilayah tugas belum ada, lakukan Insert baru wilayah tugas
                    $dataWilayahTugas['id_kegiatan'] = $idKegiatan;
                    $dataWilayahTugas['id_wilayah'] = $idWilayah;
                    if (!$wilayaTugasModel->insert($dataWilayahTugas)) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => "ID Wilayah: " . $idWilayah,
                            'pesan' => "Gagal membuat wilayah tugas baru"
                        ];
                        continue;
                    }
                }
                $berhasil++;
            }
            // tidak update databse jika gagal
            $db->transComplete();

            // 5. Update Log saat Selesai
            $logModel->update($logId, [
                'status'        => 'selesai',
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ]);

            CLI::write("Proses selesai. Berhasil: $berhasil, Gagal: $gagal", 'green');
        } catch (\Exception $e) {
            CLI::error("ADA ERROR: " . $e->getMessage());
            CLI::error("DI BARIS: " . $e->getLine());
            CLI::error("FILE: " . $e->getFile());
            $logModel->update($logId, [
                'status' => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'pesan' => [$e->getMessage()]]])
            ]);
        }
    }
    public function generateUniqueUsername($email)
    {
        $email = strtolower($email);
        // jika sudah ada, kembalikan
        if (isset($this->mapUsers[$email])) {
            $username = $this->mapUsers[$email]['username'];
            return $username;
        }

        // jika tidak ada, maka buat username
        // 1. Ambil bagian depan email
        $baseUsername = explode('@', $email)[0];

        // 2. Bersihkan karakter aneh (opsional, Shield biasanya hanya boleh karakter alfanumerik/titik)
        $baseUsername = preg_replace('/[^a-zA-Z0-9._]/', '', $baseUsername);

        $username = $baseUsername;
        $i = 1;

        // 3. Cek ke database, jika ada yang sama, tambah angka di belakangnya
        while (isset($this->mapUserName[$username])) {
            $username = $baseUsername . $i;
            $i++;
        }
        $this->mapUserName[$username] =  0;

        return $username;
    }
}
