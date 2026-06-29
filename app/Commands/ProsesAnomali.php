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
    protected $mappedAssigment = [];
    protected $levelAnomali;
    protected $validation;
    protected $db;

    public function run(array $params)
    {
        // definisi parameter
        $fileName           = $params[0] ?? null;
        $logId              = $params[1] ?? null;
        $this->idKegiatan   = $params[2] ?? null;
        $this->levelAnomali = $params[3] ?? null;

        // cek parameter
        if (!$fileName || !$logId || !$this->idKegiatan || !$this->levelAnomali) {
            CLI::error("Nama file atau ID Log atau ID Kegiatan tidak ditemukan.");
            return;
        }

        // defini model
        $this->logModel  = new LogUploadModel();
        $this->assigmentModel = new AssigmentModel();
        $this->anomaliModel = new AnomaliModel();
        $this->kategoriModel = new KatAnomaliModel();
        $this->kegiatanModel = new KegiatanModel();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();

        // definisi variabel
        $this->listKategori = $this->kategoriModel->getIdKategori($this->idKegiatan);
        $kegiatan = $this->kegiatanModel->find($this->idKegiatan);
        $isRT               = $kegiatan['is_rt'] ?? null;
        $levelWilayah       = $kegiatan['level_wilayah'] ?? 4;

        // 2. Cache Kategori Anomali (Menghindari N+1 Query)
        $this->mappedKategori = $this->loadKategoriToMap($this->idKegiatan);
        $this->mappedAssigment = $this->loadAssigmentToMap($this->idKegiatan);

        // Update status log menjadi 'proses'
        $this->logModel->update($logId, ['status' => 'proses']);


        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // definisi validasi data
            $rule = null;
            $message = null;
            $ruleRT = [
                'kode_prov' => 'required|exact_length[2]',
                'kode_kab'  => 'required|exact_length[2]',
                'kode_kec'  => 'required|exact_length[3]',
                'kode_desa' => 'required|exact_length[3]',
                'kode_sls'  => 'required|exact_length[6]',
                'nurt'      => 'required|max_length[244]',
                'nuart'     => 'required|max_length[244]',
                'anomali'   => 'required',
                'id_wilayah' => 'required|exact_length[' . $levelWilayah . ']|is_not_unique[wilayah.id]',
            ];
            $ruleNRT = [
                'kode_prov' => 'required|exact_length[2]',
                'kode_kab'  => 'required|exact_length[2]',
                'kode_kec'  => 'permit_empty|exact_length[3]',
                'kode_desa' => 'permit_empty|exact_length[3]',
                'kode_sls'  => 'permit_empty|exact_length[6]',
                'kode_nrt'      => 'required|max_length[255]',
                'nama_nrt'     => 'required',
                'anomali'   => 'required',
                'id_wilayah' => 'required|exact_length[' . $levelWilayah . ']|is_not_unique[wilayah.id]',
            ];
            $message = [
                'id_wilayah' => [
                    'is_not_unique' => 'id wilayah tidak ditemukan di master wilayah',
                    'exact_length' => 'id wilayah tidak sama dengan yang didefinisikan di kegiatan'
                ],
            ];

            if ($isRT) {
                $rule = $ruleRT;
            } else {
                $rule = $ruleNRT;
            }

            // set valiasion
            $this->validation->reset();
            $this->validation->setRules($rule, $message);

            $totalBaris = count($sheetData) - 1; // Kurangi 1 karena header
            $berhasil = 0;
            $gagal = 0;
            $errorDetails = [];
            $totalSuccess = 0;
            $rowNum = 1;


            $groupedData = [];

            for ($i = 1; $i < count($sheetData); $i++) {
                $roww = $sheetData[$i];
                if (empty($roww[1])) continue; // Skip jika kd_kab kosong

                $kdKab = $roww[0] . $roww[1];
                $groupedData[$kdKab][] = $roww;
            }

            foreach ($groupedData as $kdKab => $rows) {
                CLI::write("Kabuten yg dijalankan: $kdKab");
                $len = count($rows);
                CLI::write("panjang data: $len");

                // fungsi untuk mengupdate hanya pada list anomali tertentu yg muncul di file upload.
                // Kumpulkan semua kode anomali unik khusus untuk kabupaten ini dari rows Excel
                $uniqueKodesInExcel = [];
                for ($i = 0; $i < $len; $i++) {
                    $row = $rows[$i];
                    // Posisi kolom anomali: jika isRT kolom indeks 9, jika non-RT kolom indeks 7
                    $anomaliStr = $isRT ? ($row[9] ?? '') : ($row[7] ?? '');

                    // Pecah jika dalam satu cell ada multiple anomali dipisahkan koma
                    $arrAnomali = explode(',', rtrim($anomaliStr, ','));
                    foreach ($arrAnomali as $kode) {
                        $cleanKode = strtoupper(trim($kode));
                        if (!empty($cleanKode)) {
                            $uniqueKodesInExcel[$cleanKode] = true;
                        }
                    }
                }

                // Petakan kode anomali dari Excel ke ID Kategori Anomali menggunakan cache map
                // targetKategoriIds berisi idKategori anomli yg muncul di file
                $targetKategoriIds = [];
                foreach (array_keys($uniqueKodesInExcel) as $kodeAnom) {
                    if (isset($this->mappedKategori[$kodeAnom]['id'])) {
                        $targetKategoriIds[] = $this->mappedKategori[$kodeAnom]['id'];
                    }
                }

                // Jika file kosong atau tidak ada kategori valid, tidak perlu set is_insert = 0
                if (empty($targetKategoriIds)) {
                    CLI::write("⚠️ Tidak ada kategori anomali valid dalam data Excel untuk Kab $kdKab.", 'yellow');
                    // continue;
                } else {
                    // Cari semua ID di tabel anomali menurut kegiatan tertentu dan kabupaten tertentu.
                    $idsQuery = $this->anomaliModel
                        ->builder()
                        ->select('anomali.id')
                        ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali')
                        ->where('k.id_kegiatan', $this->idKegiatan)
                        ->where('left(anomali.id_wilayah,4)', $kdKab)
                        ->where('k.level_anomali', $this->levelAnomali)
                        ->whereIn('anomali.id_kategori_anomali', $targetKategoriIds);

                    $ids = $idsQuery->get()->getResultArray();

                    // Ambil array ID Anomali dari kegiatan tertentu saja
                    $idsArray = array_column($ids, 'id');

                    // batas mulai transaksi data.
                    $this->db->transStart();

                    // Update semua anomali dari kegitan tertentu is_insert = 0
                    if (!empty($idsArray)) {
                        $this->anomaliModel
                            ->builder()
                            ->whereIn('id', $idsArray)
                            ->set(['is_insert' => 0])
                            ->update();
                    }
                }


                try {
                    // 2. Mulai membaca data (Skip baris 0/header)
                    for ($i = 0; $i < $len; $i++) {
                        $row = $rows[$i];
                        $data = null;

                        $rowNum = $rowNum + 1; // Untuk penanda baris di pesan error

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
                                'id_assigment' => trim($row[0] . $row[1] . $row[2] . $row[3] . $row[4]) . '_' . trim($row[5]) . '_' . trim($row[6]),
                                'id_wilayah' => trim($row[0] . $row[1] . $row[2] . $row[3] . $row[4]),
                            ];
                        } else {
                            $data = [
                                'kode_prov' => $row[0],
                                'kode_kab'  => $row[1],
                                'kode_kec'  => $row[2],
                                'kode_desa' => $row[3],
                                'kode_sls'  => $row[4],
                                'nurt'      => $row[5],
                                'nama_krt'     => ucwords($row[6]),
                                'anomali'   => strtoupper($row[7]),
                                'id_assigment' => trim($row[0] . $row[1] . $row[2] . $row[3] . $row[4]) . '_' . trim($row[5]),
                                'id_wilayah' => trim($row[0] . $row[1] . $row[2] . $row[3] . $row[4]),
                            ];
                        }

                        // melakukan validasi data
                        if (!$this->validation->run($data)) {
                            $errorDetails[] = [
                                'baris' => $rowNum,
                                'data' => $data['id_assigment'],
                                'messages' => $this->validation->getErrors(),
                            ];
                            $gagal++;

                            continue;
                        } else {
                            // 3. Jika lolos validasi, simpan ke database

                            // mendapatkan id assigment
                            // $id_assigment = $this->assigmentModel->getOrInsert($data, $this->idKegiatan);

                            // cek apakah id assigment sudah ada
                            $id_assigment = null;
                            if (!isset($this->mappedAssigment[$data['id_assigment']])) {
                                $datum = [
                                    'kd_assigment' => $data['id_assigment'],
                                    'id_wilayah' => $data['id_wilayah'],
                                    'id_kegiatan' => $this->idKegiatan,
                                    'kd_krt' => $data['nurt'] ?? null,
                                    'kd_art' => $data['nuart'] ?? null,
                                    'nm_krt' => $data['nama_krt'] ?? null,
                                    'nm_art' => $data['nama_art'] ?? null,
                                    'kd_nrt' => $data['kode_nrt'] ?? null,
                                    'nm_nrt' => $data['nama_nrt'] ?? null,
                                ];

                                $id_assigment = $this->assigmentModel->insert($datum);
                                if (!$id_assigment) {
                                    $existing = $this->assigmentModel
                                        ->where('id_kegiatan', $this->idKegiatan)
                                        ->where('kd_assigment', $data['id_assigment'],)
                                        ->first();
                                    if (!$existing) {
                                        $errorDetails[] = [
                                            'baris' => $rowNum,
                                            'data' => $data['id_assigment'],
                                            'messages' => $this->assigmentModel->errors(),
                                        ];
                                        $gagal++;
                                        continue;
                                    }
                                    $id_assigment = $existing['id'] ?? null;
                                }
                                // Simpan ke cache agar tidak double insert di baris berikutnya
                                if ($id_assigment) {
                                    $this->mappedAssigment[$data['id_assigment']]['id'] = $id_assigment;
                                }
                            } else {
                                // jika ada cek apakah punya akses untuk menambahkan.
                                $id_assigment = $this->mappedAssigment[$data['id_assigment']]['id'];
                            }


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
                            // CLI::write("id assgiment : $id_assigment");
                            $listAnomali = $this->anomaliModel->getAnomaliByAssigment($id_assigment);

                            // pecah string berdasarkan koma
                            $anomaliTambahan = explode(',', rtrim($data['anomali'], ','));
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

                    if (!empty($idsArray)) {
                        // 1. Cek anomali yg kosong, tapi tidak muncul lagi (Completed by system)
                        $this->db->table('anomali')
                            ->whereIn('id', $idsArray)
                            ->where('is_insert', 0)
                            ->groupStart() // Jaga-jaga jika ada NULL
                            ->where('konfirmasi', '')
                            ->orWhere('konfirmasi', null)
                            ->groupEnd()
                            ->update([
                                'is_sistem'  => 1,
                                'konfirmasi' => 'System: Sudah diperbaiki di fasih'
                            ]);

                        $jumlahUpdateSistem = $this->db->affectedRows();
                        CLI::write("Berhasil update is sistem : $jumlahUpdateSistem", 'cyan');

                        // 2. Cek anomali yg sebelumnya is_sistem, tapi muncul lagi (Re-open)
                        $this->db->table('anomali')
                            ->whereIn('id', $idsArray)
                            ->where('is_sistem', 1)
                            ->where('is_insert', 1) // Syarat muncul lagi: is_insert jadi 1
                            ->update([
                                'is_sistem'  => 0,
                                'konfirmasi' => ''
                            ]);

                        $jumlahBalik = $this->db->affectedRows();
                        CLI::write("Berhasil mengembalikan ke aktif : $jumlahBalik", 'yellow');
                    } else {
                        // jika tidak ada anomali yg diubah dari upload sebelumnya.
                        CLI::write("ℹ️ Tidak ada ID anomali untuk diproses di kabupaten ini.", 'white');
                    }

                    //batas write db selesai 
                    $this->db->transComplete();
                    CLI::write("data berhasil disimpan untuk kab: $kdKab");
                } catch (\Throwable $th) {
                    if ($this->db->transStatus() === false) {
                        $this->db->transRollback();
                    } // Batalkan jika error
                    CLI::error($th->getMessage());
                    $this->logModel->update($logId, [
                        'status' => 'gagal',
                        'error_details' => json_encode([['baris' => '-', 'data' => 'Sistem', 'messages' => [$th->getMessage()]]])
                    ]);
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
            $this->logModel->update($logId, $datum);

            CLI::write("Proses selesai. Berhasil: $berhasil, Gagal: $gagal", 'green');
        } catch (\Throwable $th) {
            // --- CATCH LUAR ---
            // Menangkap error yang sangat parah (misal: memori limit atau tabel hilang)
            CLI::error("Sistem berhenti total: " . $th->getMessage());
            $this->logModel->update($logId, [
                'status'        => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'System Runner', 'messages' => [$th->getMessage()]]]),
            ]);
        };
    }

    public function insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $id_wilayah)
    {
        // Memetakan anomali yang SUDAH ADA di DB: [id_kategori => id_anomali_tabel]
        $mappedExisting = [];
        if (!empty($listAnomali)) {
            foreach ($listAnomali as $list) {
                $mappedExisting[$list['id_kategori_anomali']] = $list['id'];
                // CLI::write($list['id_kategori_anomali']);
            }
        }

        foreach ($anomaliTambahan as $kodeAnom) {
            $idKat = null;
            // Cek apakah kode anomali dari Excel ada di master kategori kita
            // cek apakah kode anomali sudah ada
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
                    if ($existing['level_anomali'] !== $this->levelAnomali) {
                        return "Kode anomali $kodeAnom sudah terdaftar di wilayah lain, silahkan menggunakan kode anomali lainnya.";
                    }
                    $idKat = $existing['id'] ?? null;
                }
                // Simpan ke cache agar tidak double insert di baris berikutnya
                if ($idKat) {
                    $this->mappedKategori[$kodeAnom]['id'] = $idKat;
                    $this->mappedKategori[$kodeAnom]['level_wilayah'] = $this->levelAnomali;
                }
            } else {
                // jika ada cek apakah punya akses untuk menambahkan.
                if ($this->mappedKategori[$kodeAnom]['level_wilayah'] !== $this->levelAnomali) {
                    return "User tidak punya akses untuk menambahkan ANOMALI: $kodeAnom";
                }
                $idKat = $this->mappedKategori[$kodeAnom]['id'];
            }
            // CLI::write($idKat);
            $dataSave = null;

            // cek apakah kategori ditemukan.
            if ($idKat) {
                // data sabe anomali
                $dataSave = [
                    'id_kategori_anomali' => $idKat,
                    'id_wilayah'          => $id_wilayah,
                    'id_assigment'        => $id_assigment,
                    'is_insert'           => 1,
                    'konfirmasi'          => '',
                ];
            } else {
                // Log atau tampilkan pesan jika kategori benar-benar tidak bisa ditemukan/dibuat
                $pesan = "Gagal mendapatkan ID Kategori untuk kode: $this->idKegiatan";
                return $pesan;
            };

            // jika sudah ada anomali pada assigment tersebut, maka tinggal update anomali. jika belum maka insert.
            // CLI::write($idKat);
            if (isset($mappedExisting[$idKat])) {
                // jika ditemukan anomali diassigment ini
                // UPDATE: Menggunakan ID unik dari tabel anomali (primary key)
                $idAnomaliTabel = $mappedExisting[$idKat];

                // $updateData['is_insert']  = 1;
                // $this->anomaliModel->builder()->resetQuery();
                // $this->anomaliModel->update($idAnomaliTabel, $updateData);

                // menggunakan db langsung. tidak pakai model
                $this->db->table('anomali')
                    ->where('id', $idAnomaliTabel)
                    ->update(['is_insert' => 1]);
                CLI::write("id anomali : $idAnomaliTabel");
            } else {
                // jika tidak ditemukan id kategori anomali
                // INSERT baru
                $this->anomaliModel->builder()->resetQuery();
                $this->anomaliModel->insert($dataSave);
                $newAnomaliId = $this->anomaliModel->getInsertID();
                // CLI::write("id anomali baru : $jes");
                if ($newAnomaliId == 0) {
                    $err = $this->anomaliModel->errors();
                    foreach ($err as $rr) {
                    }
                    continue;
                }

                // menambahkan pada mapped Exiting
                $mappedExisting[$idKat] = $newAnomaliId;

                // insert langusng pakai db
                // $this->db->table('anomali')->insert($dataSave);
                // $newAnomaliId = $this->db->insertID();
                // CLI::write("✨ Berhasil Insert Anomali Baru ID: $newAnomaliId (Kategori: $idKat)");
            }
        }
    }

    public function loadKategoriToMap($id_kegiatan)
    {
        $listKategori = $this->kategoriModel->where('id_kegiatan', $id_kegiatan)->findAll();
        $map = [];
        foreach ($listKategori as $kat) {
            $map[$kat['kode_anomali']] = ['id' => $kat['id'], 'level_wilayah' => $kat['level_anomali']];
        }
        return $map;
    }
    public function loadAssigmentToMap($id_kegiatan)
    {
        $listAssigment = $this->assigmentModel->where('id_kegiatan', $id_kegiatan)->findAll();
        $map = [];
        foreach ($listAssigment as $ass) {
            $map[$ass['kd_assigment']] = ['id' => $ass['id']];
        }
        return $map;
    }
}
