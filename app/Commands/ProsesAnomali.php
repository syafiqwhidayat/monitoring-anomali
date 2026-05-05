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

        $fileName           = $params[0] ?? null;
        $logId              = $params[1] ?? null;
        $this->idKegiatan   = $params[2] ?? null;
        $this->levelAnomali = $params[3] ?? null;
        $this->listKategori = $this->kategoriModel->getIdKategori($this->idKegiatan);
        $kegiatan = $this->kegiatanModel->find($this->idKegiatan);
        $isRT               = $kegiatan['is_rt'] ?? null;
        $levelWilayah       = $kegiatan['level_wilayah'] ?? 4;


        if (!$fileName || !$logId || !$this->idKegiatan || !$this->levelAnomali) {
            CLI::error("Nama file atau ID Log atau ID Kegiatan tidak ditemukan.");
            return;
        }


        // 2. Cache Kategori Anomali (Menghindari N+1 Query)
        $this->mappedKategori = $this->loadKategoriToMap($this->idKegiatan);

        // Update status log menjadi 'proses'
        $this->logModel->update($logId, ['status' => 'proses']);

        $db = \Config\Database::connect();

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $totalBaris = count($sheetData) - 1; // Kurangi 1 karena header
            $berhasil = 0;
            $gagal = 0;
            $errorDetails = [];
            $totalSuccess = 0;

            // Cari semua ID di tabel anomali yang kategorinya punya id_kegiatan tertentu
            $ids = $this->anomaliModel
                ->select('anomali.id')
                ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali')
                ->where('k.id_kegiatan', $this->idKegiatan)
                ->findAll();

            // Ambil array ID-nya saja
            $idsArray = array_column($ids, 'id');

            // Jika ada data, lakukan update berdasarkan ID
            if (!empty($idsArray)) {
                $this->anomaliModel->whereIn('id', $idsArray)
                    ->set(['is_insert' => 0])
                    ->update();
            }

            // 2. Mulai membaca data (Skip baris 0/header)

            $db->transStart(); // Mulai Transaksi

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
                        'messages' => $validation->getErrors(),
                    ];
                    $gagal++;

                    // continue;
                } else {
                    // 3. Jika lolos validasi, simpan ke database

                    // mendapatkan id assigment
                    $id_assigment = $this->assigmentModel->getOrInsert($data, $this->idKegiatan);
                    $ass = $data['nama_nrt'];
                    CLI::write("id assigment : $id_assigment");

                    // cek berhasil dapat id_assigment
                    if (!$id_assigment) {
                        $errorDetails[] = [
                            'baris' => $rowNum,
                            'data' => $data['id_assigment'],
                            'messages' => $this->assigmentModel->errors(),
                        ];
                        $gagal++;
                        continue;
                    }

                    //  mendapatkan daftar anomali pada assigment tersebut
                    $listAnomali = $this->anomaliModel->getAnomaliByAssigment($id_assigment);

                    // pecah string berdasarkan koma
                    $anomaliTambahan = explode(',', $data['anomali']);
                    $anomaliTambahan = array_map('trim', $anomaliTambahan);

                    $id_wilayah = $data['id_wilayah'];

                    $status = $this->insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $id_wilayah) ?? null;
                    if ($status) {
                        $errorDetails[] = [
                            'baris' => $rowNum,
                            'data' => $data['id_assigment'],
                            'messages' => $status,
                        ];
                        $gagal++;
                        continue;
                    }

                    // Kelompokkan sukses per id_wilayah
                    if (!isset($successReport[$id_wilayah])) {
                        $successReport[$id_wilayah] = 0;
                    }
                    $successReport[$id_wilayah]++;
                    $totalSuccess++;
                    $berhasil++;

                    // $db->transComplete(); // Commit Transaksi
                }
            }
            $db->transComplete();
            // 5. Update Log saat Selesai
            $datum = [
                'status'        => 'selesai',
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ];
            $this->logModel->update($logId, $datum);

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
                $l = $list['id_kategori_anomali'];
                CLI::write("listAnomali : $l");
            }
        }

        foreach ($anomaliTambahan as $kodeAnom) {
            // Cek apakah kode anomali dari Excel ada di master kategori kita
            $idKat = null;
            if (!isset($this->mappedKategori[$kodeAnom])) {
                $data = [
                    'id_kegiatan' => $this->idKegiatan,
                    'kode_anomali' => $kodeAnom,
                    'is_show' => 0,
                    'level_anomali' => $this->levelAnomali,
                ];
                $idKat = $this->kategoriModel->insert($data);
                $erro = $this->kategoriModel->errors();
                if (!$idKat) {
                    $existing = $this->kategoriModel
                        ->where('id_kegiatan', $this->idKegiatan)
                        ->where('kode_anomali', $kodeAnom)
                        ->first();
                    $idKat = $existing['id'] ?? null;
                }
                // Simpan ke cache agar tidak double insert di baris berikutnya
                if ($idKat) {
                    $this->mappedKategori[$kodeAnom] = $idKat;
                }
            } else {
                $idKat = $this->mappedKategori[$kodeAnom];
            }
            $dataSave = null;

            if ($idKat) {
                $dataSave = [
                    'id_kategori_anomali' => $idKat,
                    'id_wilayah'          => $id_wilayah,
                    'id_assigment'        => $id_assigment,
                    'is_insert'           => 1,
                ];
            } else {
                // Log atau tampilkan pesan jika kategori benar-benar tidak bisa ditemukan/dibuat
                $pesan = "Gagal mendapatkan ID Kategori untuk kode: $this->idKegiatan";
                CLI::error($pesan);
                foreach ($erro as $er) {
                    CLI::write($er);
                }
                return $pesan;
            };



            // jika sudah ada, maka tinggal update. jika belum maka insert.
            if (isset($mappedExisting[$idKat])) {
                // UPDATE: Menggunakan ID unik dari tabel anomali (primary key)
                $idAnomaliTabel = $mappedExisting[$idKat];
                CLI::write("id anomali : $idAnomaliTabel");
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
