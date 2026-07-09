<?php

namespace App\Controllers;

use \App\Models\AnomaliModel;
use App\Models\KatAnomaliModel;
use Config\Services;
use Faker\Provider\Lorem;

class Monitoring extends BaseController
{
    protected $anomaliModel;
    protected $katAnomaliModel;

    public function __construct()
    {
        $this->anomaliModel = new AnomaliModel();
        $this->katAnomaliModel = new KatAnomaliModel();
    }

    public function index()
    {

        $data['title'] = "Monitoring Semua Anomali";

        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];

        $data['listStatus'] = [
            [
                'value' => 1,
                'nama' => "Aktif"
            ],
            [
                'value' => 0,
                'nama' => "Clean"
            ],
        ];
        $data['listLevel'] = [
            [
                'id' => '',
                'nama' => "Semua Anomali",
            ],
        ];
        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel ?? []);


        $data['filterStatus'] = $this->request->getGet('fil-stat') ?? 1;
        $data['filterLevel'] = $this->request->getGet('fil-level') ?? '';

        $dataHead = $this->anomaliModel->jumlahKonfirmasiByPublik(null, $data['filterStatus'], $data['filterLevel']);

        $data['dataCharJmlAnom'] = $this->dataChartJmlAnom(status: $data['filterStatus'], levelAnomali: $data['filterLevel']);
        $data['dataHead'] = [
            'total seluruh' => $dataHead[0]['jumlah_total'] ?? 0,
            'total public' => $dataHead[0]['jumlah_public'] ?? 0,
            'total non public' => $dataHead[0]['jumlah_non_public'] ?? 0
        ];
        $data['dataProses'] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('proses', status: $data['filterStatus'], levelAnomali: $data['filterLevel']) ?? [0, 0]),
        ];
        $data['dataProsesPublic'] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('public', status: $data['filterStatus'], levelAnomali: $data['filterLevel']) ?? [0, 0]),
        ];
        $data['dataProsesNonPublic'] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('non_public', status: $data['filterStatus'], levelAnomali: $data['filterLevel']) ?? [0, 0]),
        ];
        $data['dataProsesFlag1'] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('flag1', status: $data['filterStatus'], levelAnomali: $data['filterLevel']) ?? [0, 0]),
        ];
        $data['dataProsesFlag2'] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('flag2', status: $data['filterStatus'], levelAnomali: $data['filterLevel']) ?? [0, 0]),
        ];
        $data['dataProsesFlag3'] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('flag3', status: $data['filterStatus'], levelAnomali: $data['filterLevel']) ?? [0, 0]),
        ];
        $data['dataTimeline'] = $this->dataTimeline();
        $data['dataTop5'] = $this->anomaliModel->getTop5() ?? null;

        return view('monitoring/monitoringAll', $data);
    }

    public function view()
    {
        $userWilayah = auth()->user()->wilayah_kerja;
        $userWilayah = substr($userWilayah, -2);

        //    Pilihan
        $data['listKodeAnom'] = [[
            'id' => '',
            'nama' => "Pilih Kode Anomali",
            'level' => ''
        ]];
        $data['listStatus'] = [
            [
                'value' => 1,
                'nama' => "Aktif"
            ],
            [
                'value' => 0,
                'nama' => "Clean"
            ],
        ];
        // list kode anomali
        $listAnom = $this->anomaliModel->getKdAnomaliByUser() ?? [];
        $data['listKodeAnom'] = array_merge($data['listKodeAnom'], $listAnom);

        // filter terpilih
        $data['filterAnomali'] = $this->request->getGet('fil-anomali') ?? $data['listKodeAnom'][1]['id'];
        $data['sel_prov'] = $this->request->getGet('sel-prov') ?? 13;
        // $data['sel_kab'] = ($userWilayah === '00') ? ($this->request->getGet('sel-kab') ?? null) : null : $userWilayah;
        $data['sel_kab'] = ($userWilayah === '00') ? ($this->request->getGet('sel-kab') ?? null) : $userWilayah;
        $data['filterStatus'] = $this->request->getGet('fil-stat') ?? '';


        // list kabupaten
        if ($userWilayah === '00') {
            $data['list_kab'] = $this->anomaliModel->getWilayah('kab', $data['filterAnomali'], $data['sel_prov']);
        } else {
            $data['list_kab'] = $this->anomaliModel->getWilayah('kab', $data['filterAnomali'], $data['sel_prov'], $data['sel_kab']);
        }


        $general = $this->katAnomaliModel->getDataUmum($data['filterAnomali'])[0] ?? [];

        // jumlah data
        $dataHead = $this->anomaliModel->jumlahKonfirmasiByPublik($data['filterAnomali'], status: $data['filterStatus']); //data jumlah menurut wilayah.
        $data["title"] = "Monitoring Anomali " . $general['kode_anomali'];
        $data["kode_anomali"] = $general['kode_anomali'] ?? null;
        $data["is_public"] = $general['is_show'] ?? null;
        $data["flag"] = $general['flag'] ?? null;
        $data["detil"] = $general['detil_anomali'] ?? null;
        $data["definisi"] = $general['definisi_anomali'] ?? null;
        $data["data_proses_wilayah"] = $this->dataChartJmlAnom($data['filterAnomali']);
        $data["dataHead"] = [
            'total seluruh' => $dataHead[0]['jumlah_total'],
        ];
        $data["data_proses"] = [
            'label' => json_encode(['Selesai', 'Belum']),
            'nilai' => json_encode($this->dataChartProses('proses', id_kat: $data['filterAnomali'], status: $data['filterStatus'])),
        ];
        $data["dataTimeline"] = $this->dataTimeline($data['filterAnomali']);
        $data["dataWordCloud"] = json_encode($this->dataWordCloud($data['filterAnomali'], status: $data['filterStatus']));
        // dd($data['dataWordCloud']);
        $data["dataTop5"] = $this->anomaliModel->getTop5($data['filterAnomali']);
        return view('monitoring/monitoringSelc', $data);
    }

    public function manajemenAction()
    {
        $id = $this->request->getVar('id');
        $action = $this->request->getVar('action');

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
            "data" => [
                'id' => $id,
                'kode_anomali' => 'AN21',
                'definisi_anomali' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam nesciunt voluptatem dolorem pariatur reiciendis aspernatur provident itaque! Eveniet necessitatibus laboriosam, omnis excepturi sed architecto dolores repudiandae quisquam expedita facere. Sapiente.',
                'detil_anomali' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita, impedit quasi earum facilis officia est enim sit distinctio repellat recusandae!',
                'is_show' => true,
            ]

        ];

        $data['data'] = $this->katAnomaliModel->find($id);
        // dd($data);

        return view('ManajAnom/edit', $data);
    }

    public function updateKategori()
    {
        $data = [
            "title" => "Upload Anomali",
        ];

        $data = $this->request->getPost();

        if ($data['is_show'] == "show_id_" . $data['id']) {
            $data['is_show'] = true;
        } else {
            $data['is_show'] = false;
        }

        if ($this->katAnomaliModel->save($data) === false) {
            session()->setFlashdata($this->katAnomaliModel->errors());
            return redirect()->back()
                ->withInput()
                ->with('message_errors', 'Gagal Simpan Data');
        }

        return redirect()->to(base_url('/manajemen-anomali/list'))->with('message', 'data berhasil di update');



        // Jika BERHASIL, kembalikan JSON sukses

        // dd("berhasil validasi");
        // return $this->response->setJSON([
        //     'status' => 'success',
        //     'message' => 'Konfirmasi berhasil disimpan.',
        //     'id_updated' => $id
        // ]);

        // dd($konfirmasi);
    }

    public function upload()
    {
        $data = [
            "title" => "Upload Anomali"
        ];
        return view('manajAnom/upload', $data);
    }

    public function template()
    {
        $template = "ini adalah template";
        dd($template);
    }

    public function dataChartJmlAnom($id_kat = null, $status = null, $levelAnomali = null)
    {
        $dataModel = null;
        $judulBaris = null;

        if ($id_kat) {
            // jika id kategori ada, maka ambil jumlah konfirmasi by wilayah menurut kategori
            $dataModel = $this->anomaliModel->jumlahKonfirmasiByWiayah(idKat: $id_kat, level: $levelAnomali, status: $status);
            $judulBaris = array_column($dataModel, 'id_wil');
        } else {
            // jika id kategori null, maka ambil jumlah anomali by kategori menurut kegiatan
            $dataModel = $this->anomaliModel->jumlahKonfirmasiByAnoamli(level: $levelAnomali, status: $status);
            $judulBaris = array_column($dataModel, 'kode_anomali');
        };

        // map agar sesuai dengan format.
        $jumlah_terisi = array_column($dataModel, 'jumlah_terisi');
        $jumlah_total = array_column($dataModel, 'jumlah_total');

        $selisih = array_map(fn($a, $b) => $a - $b, $jumlah_total, $jumlah_terisi);
        $data =  [
            'labels' => json_encode($judulBaris),
            'datesets' => [
                [
                    'label' => json_encode(['Sudah Konfirmasi']),
                    'nilai' => json_encode($jumlah_terisi),
                ],
                [
                    'label' => json_encode(['Belum Konfirmasi']),
                    'nilai' => json_encode($selisih),
                ]
            ],
        ];
        return $data;
    }

    public function dataChartProses($string = "proses", $id_kat = '', $status = null, $levelAnomali = null)
    {
        $array = [];
        // $array = $this->anomaliModel->jumlahProses("non_public");
        // dd($array);
        switch ($string) {
            case "proses":
                $array = $this->anomaliModel->jumlahProses(jenis: "", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
            case "public":
                $array = $this->anomaliModel->jumlahProses(jenis: "public", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
            case "non_public":
                $array = $this->anomaliModel->jumlahProses(jenis: "non_public", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
            case "flag1":
                $array = $this->anomaliModel->jumlahProses(jenis: "flag1", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
            case "flag2":
                $array = $this->anomaliModel->jumlahProses(jenis: "flag2", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
            case "flag3":
                $array = $this->anomaliModel->jumlahProses(jenis: "flag3", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
            default:
                $array = $this->anomaliModel->jumlahProses(jenis: "", idKat: $id_kat, status: $status, levelAnomali: $levelAnomali);
                break;
        }
        // cek if na
        $data = [$array[0]['jumlah_terisi'], $array[0]['jumlah_total'] - $array[0]['jumlah_terisi']];

        $data = array_map(function ($val) {
            return ($val === null) ? 0 : $val;
        }, $data);
        return ($data);
    }

    public function dataTimeline($id_kat = '')
    {
        $hasil = $this->anomaliModel->jumlahByTanggal($id_kat);
        $dataCreated = array_column($hasil['dataCreated'], 'jumlah', 'tanggal');
        $dataUpdated = array_column($hasil['dataUpdated'], 'jumlah', 'tanggal');
        $tanggalCreated = array_column($hasil['dataCreated'], 'tanggal');
        $tanggalUpdated = array_column($hasil['dataUpdated'], 'tanggal');
        $tanggalGabung = array_unique(array_merge($tanggalCreated, $tanggalUpdated));
        sort($tanggalGabung);

        $runningTotalCreated = 0;
        $runningTotalUpdated = 0;
        $dataChartCreated = array_map(function ($tgl) use ($dataCreated, &$runningTotalCreated) {
            // Ambil jumlah hari ini (jika tidak ada, anggap 0)
            $jumlahHariIni = (int) ($dataCreated[$tgl] ?? 0);
            // Tambahkan ke total akumulasi
            $runningTotalCreated += $jumlahHariIni;

            return $runningTotalCreated;
            // return (int) ($dataCreated[$tgl] ?? 0);
        }, $tanggalGabung);
        $dataChartUpdated = array_map(function ($tgl) use ($dataUpdated, &$runningTotalUpdated) {
            // Ambil jumlah hari ini (jika tidak ada, anggap 0)
            $jumlahHariIni = (int) ($dataUpdated[$tgl] ?? 0);

            // Tambahkan ke total akumulasi
            $runningTotalUpdated += $jumlahHariIni;

            return $runningTotalUpdated;
            // return (int) ($dataUpdated[$tgl] ?? 0);
        }, $tanggalGabung);

        // ubah nilai null jadi yg terkecil
        $tglTerkecil = min(array_filter($tanggalGabung), 0);
        $tglBaru = date('Y-m-d', strtotime($tglTerkecil . " -1 day"));
        $tanggalGabung = array_map(function ($val) use ($tglBaru) {
            return $val ?? $tglBaru;
        }, $tanggalGabung);



        $data = [
            'labels' => json_encode($tanggalGabung ?? null),
            'datasets' => [
                [
                    'label' => json_encode(['Total Anomali']),
                    'nilai' => json_encode($dataChartCreated ?? 0)
                ],
                [
                    'label' => json_encode(['Progres Anomali']),
                    'nilai' => json_encode($dataChartUpdated ?? 0)
                ],
            ]
        ];
        return ($data);
    }

    public function dataWordCloud($id_kat = '', $status = null)
    {
        $data = $this->anomaliModel->wordCloudKonfirmasi($id_kat, status: $status);
        // dd($data);
        return ($data);
    }

    public function viewPetugas()
    {
        $db = \Config\Database::connect();
        $idKegiatan = session('aktif_kegiatan');

        if (!$idKegiatan) {
            return redirect()->to('/dashboard')->with('error', 'Silakan pilih kegiatan aktif terlebih dahulu.');
        }

        // --- 1. AMBIL DATA FILTER UTK KEBUTUHAN DROPDOWN ---
        $listKategori = $db->table('kategori_anomali')
            ->where('id_kegiatan', $idKegiatan)
            ->where('date_deleted', null)
            ->get()->getResultArray();

        $listKabkot = $db->table('wilayah')
            ->select('kd_kab, nm_kab')
            ->groupBy('kd_kab, nm_kab')
            ->get()->getResultArray();

        $data['listLevel'] = [
            [
                'id' => '',
                'nama' => "Semua Anomali",
            ],
        ];
        $listSelLevel = $this->anomaliModel->getLevelAnomByUser() ?? [];
        $data['listLevel'] = array_merge($data['listLevel'], $listSelLevel ?? []);

        // --- 2. TANGKAP INPUT FILTER REQUEST ---
        $filterLevel    = $this->request->getGet('level_anomali');
        $filterKab      = $this->request->getGet('kd_kab');
        $filterStatus   = $this->request->getGet('status_anomali'); // '1' = aktif, '0' = clean
        $filterKategori = $this->request->getGet('id_kategori_anomali');
        $searchPetugas  = $this->request->getGet('search_petugas');

        // --- 3. BASE QUERY GENERATOR (DIPAKAI BERSAMA) ---
        $baseBuilder = function () use ($db, $idKegiatan, $filterLevel, $filterKab, $filterStatus, $filterKategori) {
            $builder = $db->table('anomali a')
                ->join('kategori_anomali ka', 'ka.id = a.id_kategori_anomali')
                ->join('wilayah_tugas wt', 'wt.id_wilayah = a.id_wilayah AND wt.id_kegiatan = ka.id_kegiatan', 'left')
                ->where('ka.id_kegiatan', $idKegiatan)
                ->where('a.date_deleted', null);

            if (!empty($filterLevel)) {
                $builder->where('ka.level_anomali', $filterLevel); // Sesuaikan dengan nama field asli typo di skema: level_anomalivarchar/level_anomali
            }
            if (!empty($filterKab)) {
                $builder->where('SUBSTRING(a.id_wilayah, 3, 2)', $filterKab);
            }
            if ($filterStatus !== null && $filterStatus !== '') {
                $builder->where('a.is_insert', $filterStatus);
            }
            if (!empty($filterKategori)) {
                $builder->where('a.id_kategori_anomali', $filterKategori);
            }
            return $builder;
        };

        // --- 4. TOP 5 CARDS QUERY ---
        // Top Koseka
        $topKoseka = $baseBuilder()
            ->select('u.name as nama_petugas, COUNT(a.id) as total')
            ->join('users u', 'u.id = wt.id_koseka')
            ->groupBy('wt.id_koseka, u.name')
            ->orderBy('total', 'DESC')->limit(5)->get()->getResultArray();

        // Top PML
        $topPml = $baseBuilder()
            ->select('u.name as nama_petugas, COUNT(a.id) as total')
            ->join('users u', 'u.id = wt.id_pml')
            ->groupBy('wt.id_pml, u.name')
            ->orderBy('total', 'DESC')->limit(5)->get()->getResultArray();

        // Top PPL
        $topPpl = $baseBuilder()
            ->select('u.name as nama_petugas, COUNT(a.id) as total')
            ->join('users u', 'u.id = wt.id_ppl')
            ->groupBy('wt.id_ppl, u.name')
            ->orderBy('total', 'DESC')->limit(5)->get()->getResultArray();


        // --- 5. ACCORDION HIERARCHY QUERY (KOSEKA -> PML -> PPL) ---
        // Menggunakan conditional sum untuk status progres penyerapan data lapangan/kantor
        $mainBuilder = $baseBuilder()
            ->select("
                wt.id_koseka, u_kos.name as nama_koseka,
                wt.id_pml, u_pml.name as nama_pml,
                wt.id_ppl, u_ppl.name as nama_ppl,
                COUNT(a.id) as total_anomali,
                SUM(CASE WHEN a.konfirmasi IS NOT NULL AND LENGTH(TRIM(a.konfirmasi)) > 0 AND a.is_lap = 1 THEN 1 ELSE 0 END) as jml_lap,
                SUM(CASE WHEN a.konfirmasi IS NOT NULL AND LENGTH(TRIM(a.konfirmasi)) > 0 AND a.is_lap = 0 THEN 1 ELSE 0 END) as jml_non_lap,
                SUM(CASE WHEN a.konfirmasi IS NULL OR LENGTH(TRIM(a.konfirmasi)) = 0 THEN 1 ELSE 0 END) as jml_belum
            ", false)
            ->join('users u_kos', 'u_kos.id = wt.id_koseka', 'left')
            ->join('users u_pml', 'u_pml.id = wt.id_pml', 'left')
            ->join('users u_ppl', 'u_ppl.id = wt.id_ppl', 'left');

        if (!empty($searchPetugas)) {
            $mainBuilder->groupStart()
                ->like('u_kos.name', $searchPetugas)
                ->orLike('u_pml.name', $searchPetugas)
                ->orLike('u_ppl.name', $searchPetugas)
                ->groupEnd();
        }

        // Kelompokkan bertingkat dari Koseka, PML, lalu PPL
        $rawData = $mainBuilder->groupBy('wt.id_koseka, u_kos.name, wt.id_pml, u_pml.name, wt.id_ppl, u_ppl.name')
            ->orderBy('u_kos.name', 'ASC')
            ->orderBy('u_pml.name', 'ASC')
            ->orderBy('u_ppl.name', 'ASC')
            ->get()->getResultArray();

        // --- 6. STRUKTURISASI DATA HIERARKI KE PHP ---
        $hierarchy = [];
        foreach ($rawData as $row) {
            // Jika Koseka Kosong, jadikan Id PML sebagai Root Parent pengganti sesuai request
            $kosekaKey = !empty($row['id_koseka']) ? 'KOS-' . $row['id_koseka'] : 'PML-ROOT-' . $row['id_pml'];
            $kosekaName = !empty($row['nama_koseka']) ? $row['nama_koseka'] : 'Tanpa Koseka (' . ($row['nama_pml'] ?? 'Kosong') . ')';

            if (!isset($hierarchy[$kosekaKey])) {
                $hierarchy[$kosekaKey] = [
                    'nama' => $kosekaName,
                    'is_koseka' => !empty($row['id_koseka']),
                    'total' => 0,
                    'lap' => 0,
                    'non_lap' => 0,
                    'belum' => 0,
                    'pml' => []
                ];
            }

            $pmlKey = $row['id_pml'];
            if (!isset($hierarchy[$kosekaKey]['pml'][$pmlKey])) {
                $hierarchy[$kosekaKey]['pml'][$pmlKey] = [
                    'nama' => $row['nama_pml'] ?? 'Tanpa PML',
                    'total' => 0,
                    'lap' => 0,
                    'non_lap' => 0,
                    'belum' => 0,
                    'ppl' => []
                ];
            }

            $pplKey = $row['id_ppl'];
            $hierarchy[$kosekaKey]['pml'][$pmlKey]['ppl'][$pplKey] = [
                'nama' => $row['nama_ppl'] ?? 'Tanpa PPL',
                'total' => $row['total_anomali'],
                'lap' => $row['jml_lap'],
                'non_lap' => $row['jml_non_lap'],
                'belum' => $row['jml_belum']
            ];

            // Akumulasi Nilai ke Parent PML
            $hierarchy[$kosekaKey]['pml'][$pmlKey]['total']   += $row['total_anomali'];
            $hierarchy[$kosekaKey]['pml'][$pmlKey]['lap']     += $row['jml_lap'];
            $hierarchy[$kosekaKey]['pml'][$pmlKey]['non_lap'] += $row['jml_non_lap'];
            $hierarchy[$kosekaKey]['pml'][$pmlKey]['belum']   += $row['jml_belum'];

            // Akumulasi Nilai ke Parent Koseka
            $hierarchy[$kosekaKey]['total']   += $row['total_anomali'];
            $hierarchy[$kosekaKey]['lap']     += $row['jml_lap'];
            $hierarchy[$kosekaKey]['non_lap'] += $row['jml_non_lap'];
            $hierarchy[$kosekaKey]['belum']   += $row['jml_belum'];
        }

        // --- 7. MANUAL PAGINATION MAX 50 PARENT ---
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 50;
        $totalItems = count($hierarchy);
        $slicedHierarchy = array_slice($hierarchy, ($page - 1) * $perPage, $perPage, true);


        $data['title']          = 'Monitoring Progres Anomali Per Petugas';
        $data['topKoseka']      = $topKoseka;
        $data['topPml']         = $topPml;
        $data['topPpl']         = $topPpl;
        $data['listKategori']   = $listKategori;
        $data['listKabkot']     = $listKabkot;
        $data['hierarchy']      = $slicedHierarchy;
        $data['pager']          = [
            'current'   => $page,
            'total'     => ceil($totalItems / $perPage),
            'totalItems' => $totalItems
        ];
        $data['filters']        = [
            'level'    => $filterLevel,
            'kab'      => $filterKab,
            'status'   => $filterStatus,
            'kategori' => $filterKategori,
            'search'   => $searchPetugas
        ];

        return view('monitoring/monitoringPetugas', $data);
    }
}
