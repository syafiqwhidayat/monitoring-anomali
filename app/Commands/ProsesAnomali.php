<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogUploadModel;
use App\Models\AssigmentModel;
use App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use App\Models\KegiatanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProsesAnomali extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'proses:anomali';
    protected $description = 'Memproses data anomali dari file Excel upload secara efisien dan akurat.';
    protected $usage       = 'proses:anomali [namaFile] [logId] [idKegiatan] [level_anomali]';

    protected $logModel;
    protected $assigmentModel;
    protected $anomaliModel;
    protected $kategoriModel;
    protected $kegiatanModel;

    protected $idKegiatan;
    protected $levelAnomali;
    protected $mappedKategori = [];
    protected $mappedAssigment = [];
    protected $validation;
    protected $db;

    public function run(array $params)
    {
        $fileName           = $params[0] ?? null;
        $logId              = $params[1] ?? null;
        $this->idKegiatan   = $params[2] ?? null;
        $this->levelAnomali = $params[3] ?? null;

        if (!$fileName || !$logId || !$this->idKegiatan || !$this->levelAnomali) {
            throw new \InvalidArgumentException("Parameter tidak lengkap: namaFile, logId, idKegiatan, dan level_anomali wajib diisi.");
        }

        // Inisialisasi Model & Service
        $this->logModel       = new LogUploadModel();
        $this->assigmentModel = new AssigmentModel();
        $this->anomaliModel   = new AnomaliModel();
        $this->kategoriModel  = new KatAnomaliModel();
        $this->kegiatanModel  = new KegiatanModel();
        $this->validation     = \Config\Services::validation();
        $this->db             = \Config\Database::connect();

        $kegiatan     = $this->kegiatanModel->find($this->idKegiatan);
        $isRT         = (bool)($kegiatan['is_rt'] ?? false);
        $levelWilayah = $kegiatan['level_wilayah'] ?? 4;

        // Cache Master Kategori Anomali Awal
        $this->mappedKategori = $this->loadKategoriToMap($this->idKegiatan);

        // Update status log
        $this->logModel->update($logId, ['status' => 'proses']);

        try {
            $filePath = WRITEPATH . 'uploads/' . $fileName;
            if (!file_exists($filePath)) {
                throw new \Exception("File tidak ditemukan: $filePath");
            }

            // Membaca spreadsheet secara efisien
            $reader      = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();

            // Aturan Validasi
            $ruleRT = [
                'kode_prov'  => 'required|exact_length[2]',
                'kode_kab'   => 'required|exact_length[2]',
                'kode_kec'   => 'required|exact_length[3]',
                'kode_desa'  => 'required|exact_length[3]',
                'kode_sls'   => 'required|exact_length[6]',
                'nurt'       => 'required|max_length[244]',
                'nuart'      => 'required|max_length[244]',
                'anomali'    => 'required',
                'id_wilayah' => "required|exact_length[{$levelWilayah}]|is_not_unique[wilayah.id]",
            ];

            $ruleNRT = [
                'kode_prov'  => 'required|exact_length[2]',
                'kode_kab'   => 'required|exact_length[2]',
                'kode_kec'   => 'permit_empty|exact_length[3]',
                'kode_desa'  => 'permit_empty|exact_length[3]',
                'kode_sls'   => 'permit_empty|exact_length[6]',
                'kode_nurt'  => 'required|max_length[255]',
                'nama_nrt'   => 'required',
                'anomali'    => 'required',
                'id_wilayah' => "required|exact_length[{$levelWilayah}]|is_not_unique[wilayah.id]",
            ];

            $rule    = $isRT ? $ruleRT : $ruleNRT;
            $message = [
                'id_wilayah' => [
                    'is_not_unique' => 'ID Wilayah tidak ditemukan di master wilayah',
                    'exact_length'  => 'ID Wilayah tidak sesuai dengan panjang level wilayah kegiatan'
                ],
            ];

            $totalBaris   = count($sheetData) - 1;
            $berhasil     = 0;
            $gagal        = 0;
            $errorDetails = [];

            // Grouping data berdasarkan Kabupaten
            $groupedData = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                if (empty($row[1])) continue;

                $kdKab = $row[0] . $row[1];
                $groupedData[$kdKab][] = [
                    'line' => $i + 1,
                    'row'  => $row
                ];
            }

            $isInteractive = stream_isatty(STDOUT);

            // Iterasi per Kabupaten
            foreach ($groupedData as $kdKab => $items) {
                $totalItems = count($items);
                CLI::write("Memproses Kabupaten: {$kdKab} (Total: {$totalItems} baris)", 'cyan');

                // Load assignment per kabupaten
                $this->loadAssigmentByKabToMap($this->idKegiatan, $kdKab);

                // 1. Dapatkan/Buat Kategori Baru sebelum reset is_insert
                $uniqueKodesInExcel = [];
                foreach ($items as $item) {
                    $row        = $item['row'];
                    $anomaliStr = $isRT ? ($row[9] ?? '') : ($row[7] ?? '');
                    $arrAnomali = explode(',', rtrim($anomaliStr, ','));

                    foreach ($arrAnomali as $kode) {
                        $cleanKode = strtoupper(trim($kode));
                        if (!empty($cleanKode)) {
                            $uniqueKodesInExcel[$cleanKode] = true;
                        }
                    }
                }

                // Auto-register kode anomali baru ke master agar ID terpetakan penuh
                foreach (array_keys($uniqueKodesInExcel) as $kodeAnom) {
                    if (!isset($this->mappedKategori[$kodeAnom])) {
                        $idKat = $this->kategoriModel->insert([
                            'id_kegiatan'   => $this->idKegiatan,
                            'kode_anomali'  => $kodeAnom,
                            'is_show'       => 0,
                            'level_anomali' => $this->levelAnomali,
                        ]);
                        if (!$idKat) {
                            $existing = $this->kategoriModel
                                ->where('id_kegiatan', $this->idKegiatan)
                                ->where('kode_anomali', $kodeAnom)
                                ->first();
                            $idKat = $existing['id'] ?? null;
                        }

                        if ($idKat) {
                            $this->mappedKategori[$kodeAnom] = [
                                'id'            => $idKat,
                                'level_wilayah' => $this->levelAnomali
                            ];
                        }
                    }
                }

                $targetKategoriIds = [];
                foreach (array_keys($uniqueKodesInExcel) as $kodeAnom) {
                    if (isset($this->mappedKategori[$kodeAnom]['id'])) {
                        $targetKategoriIds[] = $this->mappedKategori[$kodeAnom]['id'];
                    }
                }

                // Mulai Transaksi Per Kabupaten
                $this->db->transStart();

                // Set Flag awal is_insert = 0 untuk scope kabupaten & kategori terkait
                if (!empty($targetKategoriIds)) {
                    $this->db->table('anomali')
                        ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali')
                        ->where('k.id_kegiatan', $this->idKegiatan)
                        ->where('LEFT(anomali.id_wilayah, 4)', $kdKab)
                        ->where('k.level_anomali', $this->levelAnomali)
                        ->whereIn('anomali.id_kategori_anomali', $targetKategoriIds)
                        ->update(['anomali.is_insert' => 0]);
                }

                // 2. Olah Baris Data
                $step = 0;
                foreach ($items as $item) {
                    $step++;
                    if ($isInteractive) {
                        CLI::showProgress($step, $totalItems);
                    } else {
                        if ($step % 500 === 0 || $step === $totalItems) {
                            $percent = round(($step / $totalItems) * 100);
                            CLI::write("[" . date('Y-m-d H:i:s') . "] Kab {$kdKab} Progress: {$percent}% ({$step}/{$totalItems})");
                        }
                    }

                    $rowNum = $item['line'];
                    $row    = $item['row'];

                    if ($isRT) {
                        $data = [
                            'kode_prov'    => $row[0] ?? '',
                            'kode_kab'     => $row[1] ?? '',
                            'kode_kec'     => $row[2] ?? '',
                            'kode_desa'    => $row[3] ?? '',
                            'kode_sls'     => $row[4] ?? '',
                            'nurt'         => $row[5] ?? '',
                            'nuart'        => $row[6] ?? '',
                            'nama_krt'     => ucwords(trim($row[7] ?? '')),
                            'nama_art'     => ucwords(trim($row[8] ?? '')),
                            'anomali'      => strtoupper(trim($row[9] ?? '')),
                            'id_assigment' => trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')) . '_' . trim($row[5] ?? '') . '_' . trim($row[6] ?? ''),
                            'id_wilayah'   => trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')),
                        ];
                    } else {
                        $data = [
                            'kode_prov'    => $row[0] ?? '',
                            'kode_kab'     => $row[1] ?? '',
                            'kode_kec'     => $row[2] ?? '',
                            'kode_desa'    => $row[3] ?? '',
                            'kode_sls'     => $row[4] ?? '',
                            'kode_nurt'    => $row[5] ?? '',
                            'nama_nrt'     => ucwords(trim($row[6] ?? '')),
                            'anomali'      => strtoupper(trim($row[7] ?? '')),
                            'id_assigment' => trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')) . '_' . trim($row[5] ?? ''),
                            'id_wilayah'   => trim(($row[0] ?? '') . ($row[1] ?? '') . ($row[2] ?? '') . ($row[3] ?? '') . ($row[4] ?? '')),
                        ];
                    }

                    // Reset dan Validasi Baris
                    $this->validation->reset();
                    $this->validation->setRules($rule, $message);

                    if (!$this->validation->run($data)) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => $data['id_assigment'],
                            'messages' => $this->validation->getErrors(),
                        ];
                        $gagal++;
                        continue;
                    }

                    // Get/Create Assignment ID
                    $id_assigment = null;
                    if (!isset($this->mappedAssigment[$data['id_assigment']])) {
                        $datum = [
                            'kd_assigment' => $data['id_assigment'],
                            'id_wilayah'   => $data['id_wilayah'],
                            'id_kegiatan'  => $this->idKegiatan,
                            'kd_krt'       => $data['nurt'] ?? null,
                            'kd_art'       => $data['nuart'] ?? null,
                            'nm_krt'       => $data['nama_krt'] ?? null,
                            'nm_art'       => $data['nama_art'] ?? null,
                            'kd_nrt'       => $data['kode_nurt'] ?? null,
                            'nm_nrt'       => $data['nama_nrt'] ?? null,
                        ];

                        $id_assigment = $this->assigmentModel->insert($datum);
                        if (!$id_assigment) {
                            $existing = $this->assigmentModel
                                ->where('id_kegiatan', $this->idKegiatan)
                                ->where('kd_assigment', $data['id_assigment'])
                                ->first();
                            $id_assigment = $existing['id'] ?? null;
                        }

                        if ($id_assigment) {
                            $this->mappedAssigment[$data['id_assigment']] = ['id' => $id_assigment];
                        }
                    } else {
                        $id_assigment = $this->mappedAssigment[$data['id_assigment']]['id'];
                    }

                    if (!$id_assigment) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => $data['id_assigment'],
                            'messages' => ['Gagal menyimpan data assignment.'],
                        ];
                        $gagal++;
                        continue;
                    }

                    // Insert/Update Anomali per Baris
                    $listAnomali     = $this->anomaliModel->getAnomaliByAssigment($id_assigment);
                    $anomaliTambahan = array_map('trim', explode(',', rtrim($data['anomali'], ',')));

                    $errStatus = $this->insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $data['id_wilayah']);
                    if ($errStatus) {
                        $errorDetails[] = [
                            'baris'    => $rowNum,
                            'data'     => $data['id_assigment'],
                            'messages' => [$errStatus],
                        ];
                        $gagal++;
                        continue;
                    }

                    $berhasil++;
                }

                // 3. Post-Processing Status Sistem Per Kabupaten (Secara Bulk)
                if (!empty($targetKategoriIds)) {
                    // Update yang tidak lagi ada di file upload
                    $this->db->table('anomali')
                        ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali')
                        ->where('k.id_kegiatan', $this->idKegiatan)
                        ->where('LEFT(anomali.id_wilayah, 4)', $kdKab)
                        ->where('k.level_anomali', $this->levelAnomali)
                        ->whereIn('anomali.id_kategori_anomali', $targetKategoriIds)
                        ->where('anomali.is_insert', 0)
                        ->groupStart()
                        ->where('anomali.konfirmasi', '')
                        ->orWhere('anomali.konfirmasi IS NULL', null, false)
                        ->groupEnd()
                        ->update([
                            'anomali.is_sistem'  => 1,
                            'anomali.konfirmasi' => 'System: Sudah diperbaiki di fasih'
                        ]);

                    // Reset status sistem jika anomali muncul kembali di file upload
                    $this->db->table('anomali')
                        ->join('kategori_anomali k', 'k.id = anomali.id_kategori_anomali')
                        ->where('k.id_kegiatan', $this->idKegiatan)
                        ->where('LEFT(anomali.id_wilayah, 4)', $kdKab)
                        ->where('k.level_anomali', $this->levelAnomali)
                        ->whereIn('anomali.id_kategori_anomali', $targetKategoriIds)
                        ->where('anomali.is_sistem', 1)
                        ->where('anomali.is_insert', 1)
                        ->update([
                            'anomali.is_sistem'  => 0,
                            'anomali.konfirmasi' => ''
                        ]);
                }

                // Commit Transaksi Per Kabupaten
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    CLI::error("\nGagal melakukan commit transaksi untuk Kab: $kdKab", 'red');
                } else {
                    CLI::write("\nSelesai memproses Kab: $kdKab", 'green');
                }
            }

            // Update Log Selesai
            $this->logModel->update($logId, [
                'status'        => 'selesai',
                'total_baris'   => $totalBaris,
                'berhasil'      => $berhasil,
                'gagal'         => $gagal,
                'error_details' => json_encode($errorDetails)
            ]);

            CLI::write("Proses Selesai. Total Berhasil: $berhasil, Gagal: $gagal", 'green');
        } catch (\Throwable $th) {
            CLI::error("Fatal Error: " . $th->getMessage());
            $this->logModel->update($logId, [
                'status'        => 'gagal',
                'error_details' => json_encode([['baris' => '-', 'data' => 'System Execution', 'messages' => [$th->getMessage()]]]),
            ]);
        }
    }

    public function insertAnomali($listAnomali, $anomaliTambahan, $id_assigment, $id_wilayah)
    {
        $mappedExisting = [];
        if (!empty($listAnomali)) {
            foreach ($listAnomali as $list) {
                $mappedExisting[$list['id_kategori_anomali']] = $list['id'];
            }
        }

        foreach ($anomaliTambahan as $kodeAnom) {
            if (empty($kodeAnom)) continue;

            if (!isset($this->mappedKategori[$kodeAnom])) {
                return "Gagal mengidentifikasi ID Kategori untuk kode: $kodeAnom";
            }

            $katInfo = $this->mappedKategori[$kodeAnom];
            if ($katInfo['level_wilayah'] != $this->levelAnomali) {
                return "Akses ditolak untuk menambahkan ANOMALI: $kodeAnom";
            }

            $idKat = $katInfo['id'];

            if (isset($mappedExisting[$idKat])) {
                $idAnomaliTabel = $mappedExisting[$idKat];
                $this->db->table('anomali')
                    ->where('id', $idAnomaliTabel)
                    ->update(['is_insert' => 1]);
            } else {
                $dataSave = [
                    'id_kategori_anomali' => $idKat,
                    'id_wilayah'          => $id_wilayah,
                    'id_assigment'        => $id_assigment,
                    'is_insert'           => 1,
                    'konfirmasi'          => '',
                ];

                $this->anomaliModel->insert($dataSave);
                $newAnomaliId = $this->anomaliModel->getInsertID();

                if ($newAnomaliId) {
                    $mappedExisting[$idKat] = $newAnomaliId;
                }
            }
        }

        return null;
    }

    public function loadKategoriToMap($id_kegiatan)
    {
        $listKategori = $this->kategoriModel->where('id_kegiatan', $id_kegiatan)->findAll();
        $map = [];
        foreach ($listKategori as $kat) {
            $map[$kat['kode_anomali']] = [
                'id'            => $kat['id'],
                'level_wilayah' => $kat['level_anomali']
            ];
        }
        return $map;
    }

    public function loadAssigmentByKabToMap($id_kegiatan, $kdKab)
    {
        $listAssigment = $this->assigmentModel
            ->where('id_kegiatan', $id_kegiatan)
            ->where('LEFT(id_wilayah, 4)', $kdKab)
            ->findAll();

        $this->mappedAssigment = [];
        foreach ($listAssigment as $ass) {
            $this->mappedAssigment[$ass['kd_assigment']] = ['id' => $ass['id']];
        }
    }
}
