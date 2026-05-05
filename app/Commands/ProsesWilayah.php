<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\WilUploadLogModel;
use App\Models\UserModel;
use App\Models\WilayahTugasModel;
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
    public function run(array $params)
    {
        // 1. Ambil nama file dan ID Log dari parameter
        $fileName = $params[0] ?? null;
        $logId = $params[1] ?? null;
        $idKegiatan    = $params[2] ?? null;

        if (!$fileName || !$idKegiatan) {
            CLI::error("Nama file atau ID Log tidak ditemukan.");
            return;
        }

        $logModel  = new WilUploadLogModel();
        $userModel = new UserModel();
        $wilayaTugasModel = new WilayahTugasModel();

        // Update status log menjadi 'proses'
        $logModel->update($logId, ['status' => 'proses']);

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $totalBaris = count($sheetData) - 1; // Kurangi 1 karena header
            $berhasil = 0;
            $gagal = 0;
            $errorDetails = [];

            // 2. Mulai membaca data (Skip baris 0/header)
            for ($i = 1; $i <= $totalBaris; $i++) {
                $row = $sheetData[$i];

                $dataUserPPL = [
                    'email'         => $row[7], // Kolom H
                    'name'          => ucwords($row[7]),
                    'wilayah_kerja' => $this->wilayah_kerja, // Kolom C
                    'username'      => explode('@', $row[7])[0], // Ambil depan email untuk username
                ];
                $dataUserPML = [
                    'email'         => $row[6], // Kolom H
                    'name'         =>  ucwords($row[6]), // Kolom H
                    'wilayah_kerja' => $this->wilayah_kerja, // Kolom C
                    'username'      => explode('@', $row[6])[0], // Ambil depan email untuk username
                ];

                $idWilayah = [
                    'idWilayah' => $row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5]
                ];

                // 3. Validasi Manual
                $validation = \Config\Services::validation();

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
                // validasi id wilayah
                $validation->reset();
                $ruleWilayah = [
                    'idWilayah' => "required|is_not_unique[wilayah.id]"
                ];
                $messageWilayah = [
                    'is_not_unique' => 'wilayah tidak terdaftar',
                ];
                $validation->setRules($ruleWilayah, $messageWilayah);
                // validasi Email PPL
                if (!$validation->run($idWilayah)) {
                    $gagal++;
                    $errorDetails[] = [
                        'baris' => $i + 1,
                        'data'  => $idWilayah['idWilayah'] ?? 'Baris ' . ($i + 1),
                        'pesan' => $validation->getErrors()
                    ];
                    continue;
                }

                // cek apakah user sudah ada di database
                $userPPL = $userModel->findByCredentials(['email' => $dataUserPPL['email']]);
                $userPML = $userModel->findByCredentials(['email' => $dataUserPML['email']]);

                // 4. Simpan User ke Database
                if (!$userPPL) {
                    $userPPL = new \App\Entities\User($dataUserPPL);
                    $userPPL->password = 'password123'; // Password default
                    $userPPL = $userModel->save($userPPL);
                    if (!$userPPL) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => $dataUserPPL['email'],
                            'pesan' => $userModel->errors()
                        ];
                        continue;
                    }
                    $userPPL = $userModel->findByCredentials(['email' => $dataUserPPL['email']]);
                    if (str_contains($dataUserPPL['email'], '@bps.go.id')) {
                        $userPPL->addGroup('organik');
                    } else {
                        $userPPL->addGroup('mitra');
                    }
                };
                if (!$userPML) {
                    $userPML = new \App\Entities\User($dataUserPML);
                    $userPML->password = 'password123'; // Password default
                    $userPML = $userModel->save($userPML);

                    if ($userPML) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => $dataUserPML['email'],
                            'pesan' => $userModel->errors()
                        ];
                        continue;
                    }
                    $userPML = $userModel->findByCredentials(['email' => $dataUserPML['email']]);
                    if (str_contains($dataUserPML['email'], '@bps.go.id')) {
                        $userPML->addGroup('organik');
                    } else {
                        $userPML->addGroup('mitra');
                    }
                };
                $existing = $wilayaTugasModel->where([
                    'id_wilayah'  => $idWilayah['idWilayah'],
                    'id_kegiatan' => $idKegiatan
                ])->first();

                $dataWilayahTugas = [
                    'id_wilayah' => $idWilayah['idWilayah'],
                    'id_kegiatan' => $idKegiatan,
                    'id_ppl' => $userPPL->id,
                    'id_pml' => $userPML->id,
                ];

                if ($existing) {
                    // Jika wilayah tugas sudah ada, lakukan Update berdasarkan ID primer yang ditemukan
                    if (!$wilayaTugasModel->update($existing->id, $dataWilayahTugas)) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => "ID Wilayah: " . $idWilayah['idWilayah'],
                            'pesan' => "Gagal update wilayah tugas"
                        ];
                        continue;
                    }
                } else {
                    // Jika wilayah tugas belum ada, lakukan Insert baru wilayah tugas
                    if (!$wilayaTugasModel->insert($dataWilayahTugas)) {
                        $gagal++;
                        $errorDetails[] = [
                            'baris' => $i + 1,
                            'data'  => "ID Wilayah: " . $idWilayah['idWilayah'],
                            'pesan' => "Gagal input wilayah tugas baru"
                        ];
                        continue;
                    }
                }

                if (!$wilayaTugasModel->save($dataWilayahTugas)) {
                    $gagal++;
                    $errorDetails[] = [
                        'baris' => $i + 1,
                        'data'  => "gagal input wilayah tugas: " . $idWilayah,
                        'pesan' => 'gagal input wilayah tugas'
                    ];
                    continue;
                } else {
                    $berhasil++;
                };
            }

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
            $logModel->update($logId, [
                'status' => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'pesan' => [$e->getMessage()]]])
            ]);
        }
    }
}
