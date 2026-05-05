<?php

namespace App\Commands;

use App\Database\Seeds\KategoriAnomaliSeeder;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\AssigmentModel;
use App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\KegiatanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use SebastianBergmann\Environment\Console;

use function PHPUnit\Framework\isNull;

class ProsesAnomali extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'proses:anomali';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'proses:anomali [namaFile] [logId] [idKegiatan] [level_anomali]';

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

    protected $logModel;
    protected $assigmentModel;
    protected $anomaliModel;
    protected $kategoriModel;
    protected $kegiatanModel;
    protected $listKategori;
    protected $idKegiatan;
    protected $mappedKategori = [];
    protected $levelAnomali;

    public function run(array $params)
    {
        $this->logModel  = new LogUploadModel();
        $this->assigmentModel = new AssigmentModel();
        $this->anomaliModel = new AnomaliModel();
        $this->kategoriModel = new KatAnomaliModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->listKategori = $this->kategoriModel->getIdKategori($this->idKegiatan);

        $kegiatan = $this->kegiatanModel->find($this->idKegiatan);

        $fileName           = $params[0] ?? null;
        $logId              = $params[1] ?? null;
        $this->idKegiatan   = $params[2] ?? null;
        $this->levelAnomali = $params[3] ?? null;
        $isRT               = $kegiatan['is_rt'] ?? null;
        $levelWilayah       = $kegiatan['level_wilayah'] ?? 4;

        if (!$fileName || !$logId || !$this->idKegiatan) {
            CLI::error("Nama file atau ID Log atau ID Kegiatan tidak ditemukan.");
            return;
        }


        // 2. Cache Kategori Anomali (Menghindari N+1 Query)
        $this->mappedKategori = $this->loadKategoriToMap($this->idKegiatan);

        // Update status log menjadi 'proses'
        $this->logModel->update($logId, ['status' => 'proses']);

        $db = \Config\Database::connect();
        $db->transStart(); // Mulai Transaksi

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $totalBaris = count($sheetData) - 1; // Kurangi 1 karena header
            $berhasil = 0;
            $gagal = 0;
            $errorDetails = [];
            $totalSuccess = 0;

            $this->anomaliModel->where('id_kegiatan', session()->get('aktif_kegiatan'))->set('is_insert', 0)->update();

            // 2. Mulai membaca data (Skip baris 0/header)

            for ($i = 1; $i <= $totalBaris; $i++) {
                $row = $sheetData[$i];
                $data = null;

                $rowNum = $i + 1; // Untuk penanda baris di pesan error

                if ($isRT) {
                    $data = [
                        'kode_prov' => $row[0],
                        'kode_kab'  => $row[1],
                        'kode_kec'  => $row[2],
                        'kode_desa' => $row[3],
                        'kode_sls'  => $row[4],
                        'nurt'      => $row[5],
                        'nuart'     => $row[6],
                        'nama_krt'  => ucwords($row[7]),
                        'nama_art'  => ucwords($row[8]),
                        'anomali'   => strtoupper($row[9]),
                        'id_assigment' => $row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5] . $row[6],
                        'id_wilayah' => $row[0] . $row[1] . $row[2] . $row[3] . $row[4],
                    ];
                } else {
                    $data = [
                        'kode_prov' => $row[0],
                        'kode_kab'  => $row[1],
                        'kode_kec'  => $row[2],
                        'kode_desa' => $row[3],
                        'kode_sls'  => $row[4],
                        'kode_nrt'      => $row[5],
                        'nama_nrt'     => ucwords($row[6]),
                        'anomali'   => strtoupper($row[7]),
                        'id_assigment' => $row[0] . $row[1] . $row[2] . $row[3] . $row[4] . $row[5],
                        'id_wilayah' => $row[0] . $row[1] . $row[2] . $row[3] . $row[4],
                    ];
                }
                $rule = null;
                $message = null;
                $ruleRT = [
                    'kode_prov' => 'required|exact_length[2]',
                    'kode_kab'  => 'required|exact_length[2]',
                    'kode_kec'  => 'required|exact_length[3]',
                    'kode_desa' => 'required|exact_length[3]',
                    'kode_sls'  => 'required|exact_length[6]',
                    'nurt'      => 'required|exact_length[3]',
                    'nuart'     => 'required|exact_length[2]',
                    'anomali'   => 'required',
                ];
                $messageRT = [];
                $ruleNRT = [
                    'kode_prov' => 'required|exact_length[2]',
                    'kode_kab'  => 'required|exact_length[2]',
                    'kode_kec'  => 'permit_empty|exact_length[3]',
                    'kode_desa' => 'permit_empty|exact_length[3]',
                    'kode_sls'  => 'permit_empty|exact_length[6]',
                    'kode_nrt'      => 'required|max_length[11]',
                    'nama_nrt'     => 'required',
                    'anomali'   => 'required',
                    'id_wilayah' => 'required|exact_length[' . $levelWilayah . ']',
                ];
                $messageNRT = [];

                if ($isRT) {
                    $rule = $ruleRT;
                    $message = $messageRT;
                } else {
                    $rule = $ruleNRT;
                    $message = $messageNRT;
                }

                $validation = \Config\Services::validation();

                // melakukan validasi data
                if (!$validation->setRules($rule, $message)->run($data)) {
                    $errorDetails[] = [
                        'baris' => $rowNum,
                        'data' => $data['id_assigment'],
                        'messages' => $validation->getErrors()
                    ];
                    $gagal++;
                    // $error = json_encode($errorDetails);
                    // CLI::write("gagal validasi $error", 'green');

                    // continue;
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
                    $berhasil++;

                    $db->transComplete(); // Commit Transaksi
                }
            }
            // 5. Update Log saat Selesai
            $datum = [
                'status'        => 'selesai',
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ];
            $datum = json_encode($datum);
            CLI::write("Datum: $datum");

            // CLI::write("Mencoba update ID: " . $logId);

            // $db = \Config\Database::connect();

            // $db->table('log_upload')
            //     ->where('id', $logId)
            //     ->update($datum);
            // if ($db->affectedRows() > 0) {
            //     CLI::write("Update Berhasil!", 'green');
            // } else {
            //     CLI::error("Update Gagal atau tidak ada data yang berubah.");
            // }
            $this->logModel->update($logId, $datum);

            // if (!$this->logModel->update($logId, $datum)) {
            //     $error = $this->logModel->errors();
            //     foreach ($error as $field => $message) {
            //         CLI::write("- $field: $message", 'red');
            //     }
            // };

            CLI::write("Proses selesai. Berhasil: $berhasil, Gagal: $gagal", 'green');
        } catch (\Throwable $th) {
            $db->transRollback(); // Batalkan jika error
            CLI::error($th->getMessage());
            $this->logModel->update($logId, [
                'status' => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => [$th->getMessage()]]])
            ]);
        }
    }

    // public function cekOrInsertKategori($id_kegiatan, $anomaliTambahan)
    // {
    //     // fungsi untuk menambakan kategori yg belum ada dan return nilai id kategorinya jika sudah ada
    //     $levelAnomali = auth()->user()->wilayah_kerja;

    //     $mappedKategori = [];
    //     foreach ($$this->listKategori as $list) {
    //         $mappedKategori[$list['kode_anomali']] = $list['id'];
    //     }

    //     // insert jika belum ada
    //     foreach ($anomaliTambahan as $anom) {
    //         if (!isset($mappedKategori[$anom])) {
    //             // jika kategori anomali belum ada
    //             $data = [
    //                 'id_kegiatan' => $id_kegiatan,
    //                 'kode_anomali' => $anom,
    //                 'is_show' => 0,
    //                 'level_anomali' => $levelAnomali,
    //             ];
    //             $idbaru = $this->kategoriModel->insert($data);
    //             if ($idbaru) {
    //                 $mappedKategori[$anom] = $idbaru;
    //             } else {
    //                 // Jika gagal karena validasi (duplikat), ambil ID yang sudah ada di DB
    //                 $existing = $this->kategoriModel
    //                     ->where('id_kegiatan', $id_kegiatan)
    //                     ->where('kode_anomali', $anom)
    //                     ->first();

    //                 if ($existing) {
    //                     $mappedKategori[$anom] = $existing['id'];
    //                 }
    //             }
    //         }
    //     }
    //     return ($mappedKategori);
    // }

    public function insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $id_wilayah)
    {
        // Memetakan anomali yang SUDAH ADA di DB: [id_kategori => id_anomali_tabel]
        $mappedExisting = [];
        if (!empty($listAnomali)) {
            foreach ($listAnomali as $list) {
                $mappedExisting[$list['id_kategori_anomali']] = $list['id'];
            }
        }

        foreach ($anomaliTambahan as $kodeAnom) {
            // Cek apakah kode anomali dari Excel ada di master kategori kita
            $idKat = null;
            if (!isset($this->mappedKategori[$kodeAnom])) {
                $data = [
                    'id_kegiatan' => $this->id_kegiatan,
                    'kode_anomali' => $kodeAnom,
                    'is_show' => 0,
                    'level_anomali' => $this->levelAnomali,
                ];
                $idKat = $this->kategoriModel->insert($data);
                // Simpan ke cache agar tidak double insert di baris berikutnya
                $this->mappedKategori[$kodeAnom] = $idKat;
            } else {
                $idKat = $this->mappedKategori[$kodeAnom];
            }


            $dataSave = [
                'id_kategori_anomali' => $idKat,
                'id_wilayah'          => $id_wilayah,
                'id_assigment'        => $id_assigment,
                'is_insert'           => 1,
            ];

            // jika sudah ada, maka tinggal update. jika belum maka insert.
            if (isset($mappedExisting[$idKat])) {
                // UPDATE: Menggunakan ID unik dari tabel anomali (primary key)
                $idAnomaliTabel = $mappedExisting[$idKat];
                $this->anomaliModel->update($idAnomaliTabel, $dataSave);
            } else {
                // INSERT baru
                $this->anomaliModel->insert($dataSave);
            }
        }

        // cek apakah kategori sudah ada
        // mengembalikan map nilainya
        // $mappedKategori = $this->cekOrInsertKategori($this->idKegiatan, $anomaliTambahan);

        // cek apakah anomalinya belum ada
        // if (isNull($listAnomali)) {
        //     // tambahkan semua anomali
        //     foreach ($anomaliTambahan as $anom) {
        //         $this->anomaliModel->insert(
        //             [
        //                 'id_kategori_anomali' => $mappedKategori[$anom],
        //                 'id_wilayah' => $id_wilayah,
        //                 'id_assigment' => $id_assigment,
        //                 'is_insert' => 1,
        //             ]
        //         );
        //     };
        // } else {
        //     // ada anomali yg sudah masuk dan ada yg belum.
        //     // memetakan data lama berdasarkan kat_anomali
        //     $mappedExisting = [];
        //     foreach ($listAnomali as $list) {
        //         $mappedExisting[$list['id_kategori_anomali']] = $list['id'];
        //     }

        //     foreach ($anomaliTambahan as $anom) {
        //         $data = [
        //             'id_kategori_anomali' => $mappedKategori[$anom],
        //             'id_wilayah' => $id_wilayah,
        //             'id_assigment' => $id_assigment,
        //             'is_insert' => 1,
        //         ];
        //         $idKat = $mappedKategori[$anom];
        //         if (isset($mappedExisting[$anom])) {
        //             // Jika sudah ada. maka update
        //             $this->anomaliModel->update($mappedExisting[$anom], $data);
        //         } else {
        //             // jika data belum ada
        //             $this->anomaliModel->insert($data);
        //         }
        //     }
        // }
    }

    public function loadKategoriToMap($id_kegiatan)
    {
        $listKategori = $this->kategoriModel->where('id_kegiatan', $id_kegiatan)->findAll();
        $map = [];
        foreach ($listKategori as $kat) {
            $map[$kat['kode_anomali']] = $kat['id'];
        }
        return $map;
    }
}
