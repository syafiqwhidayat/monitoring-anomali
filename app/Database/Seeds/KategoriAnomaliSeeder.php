<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriAnomaliSeeder extends Seeder
{
    public function run()
    {
        // $data = [
        //     [
        //         'id' => 1,
        //         'id_kegiatan' => 1,
        //         'kode_anomali'    => 'AN1',
        //         'definisi_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
        //         'detil_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
        //         'is_show'    => TRUE,
        //         'date_created'    => '2025-11-05 13:04:39'
        //     ],
        //     [
        //         'id' => 2,
        //         'id_kegiatan' => 1,
        //         'kode_anomali'    => 'AN2',
        //         'definisi_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
        //         'detil_anomali'    => 'Cek apakah ada kesalahan jenis kelamin kepala keluarga',
        //         'is_show'    => TRUE,
        //         'date_created'    => '2025-11-05 13:04:39'
        //     ],
        // ];

        $data = [
            [
                'id' => 1,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN01',
                'definisi_anomali' => 'Cek konsistensi jenis kelamin Kepala Keluarga.',
                'detil_anomali' => 'Jenis kelamin KK tidak sesuai dengan aturan sensus.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:04:39'
            ],
            [
                'id' => 2,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN02',
                'definisi_anomali' => 'Verifikasi usia responden yang berusia di bawah 5 tahun.',
                'detil_anomali' => 'Usia di bawah 5 tahun memiliki data pendidikan formal yang terisi.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:04:45'
            ],
            [
                'id' => 3,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN03',
                'definisi_anomali' => 'Cek data pekerjaan untuk responden berusia di atas 70 tahun.',
                'detil_anomali' => 'Responden lansia (>70) terdeteksi memiliki pekerjaan full-time.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:05:10'
            ],
            [
                'id' => 4,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN04',
                'definisi_anomali' => 'Pemeriksaan status perkawinan responden di bawah 15 tahun.',
                'detil_anomali' => 'Responden di bawah usia legal perkawinan terdeteksi menikah.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:05:30'
            ],
            [
                'id' => 5,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN05',
                'definisi_anomali' => 'Cek kelengkapan data alamat dan kode pos.',
                'detil_anomali' => 'Kolom kode pos kosong atau tidak valid.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:06:01'
            ],
            [
                'id' => 6,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN06',
                'definisi_anomali' => 'Konsistensi hubungan dengan Kepala Keluarga (Hubungan Ganda).',
                'detil_anomali' => 'Ditemukan lebih dari satu responden berstatus "Istri" dalam satu KK.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:06:22'
            ],
            [
                'id' => 7,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN07',
                'definisi_anomali' => 'Verifikasi status disabilitas dan kemampuan bekerja.',
                'detil_anomali' => 'Responden disabilitas berat terdeteksi bekerja di sektor formal.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:07:05'
            ],
            [
                'id' => 8,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN08',
                'definisi_anomali' => 'Cek kesesuaian tingkat pendidikan dengan jenis pekerjaan.',
                'detil_anomali' => 'Pendidikan SD memiliki pekerjaan sebagai Manajer/Profesional.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:07:40'
            ],
            [
                'id' => 9,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN09',
                'definisi_anomali' => 'Pemeriksaan data migrasi (tempat lahir dan tempat tinggal saat ini).',
                'detil_anomali' => 'Tempat lahir berada di luar negeri, tetapi tidak ada catatan migrasi.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:08:00'
            ],
            [
                'id' => 10,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN10',
                'definisi_anomali' => 'Cek keberadaan NIK yang duplikat dalam wilayah sensus.',
                'detil_anomali' => 'Nomor Induk Kependudukan (NIK) terdeteksi ganda.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:08:35'
            ],
            [
                'id' => 11,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN11',
                'definisi_anomali' => 'Validasi tanggal lahir (usia lebih dari 100 tahun).',
                'detil_anomali' => 'Responden berusia sangat lanjut (>100) memerlukan verifikasi data.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:09:12'
            ],
            [
                'id' => 12,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN12',
                'definisi_anomali' => 'Pemeriksaan data kepemilikan aset (aset dan pendapatan tidak konsisten).',
                'detil_anomali' => 'Pendapatan sangat rendah, namun memiliki aset properti mewah.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:09:40'
            ],
            [
                'id' => 13,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN13',
                'definisi_anomali' => 'Cek konsistensi data kepemilikan lahan pertanian.',
                'detil_anomali' => 'Lahan pertanian yang dicatat melebihi batas kepemilikan maksimum.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:10:05'
            ],
            [
                'id' => 14,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN14',
                'definisi_anomali' => 'Verifikasi nomor telepon (terdapat format yang tidak baku).',
                'detil_anomali' => 'Format nomor kontak responden tidak mengikuti standar nasional.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:10:30'
            ],
            [
                'id' => 15,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN15',
                'definisi_anomali' => 'Cek kelengkapan kolom penghasilan per bulan (terdapat nilai 0).',
                'detil_anomali' => 'Kolom penghasilan wajib terisi, namun bernilai nol (0).',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:11:00'
            ],
            [
                'id' => 16,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN16',
                'definisi_anomali' => 'Konsistensi status kepemilikan rumah (kontrak/milik sendiri).',
                'detil_anomali' => 'Ditemukan status kepemilikan ganda atau tidak jelas.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:11:34'
            ],
            [
                'id' => 17,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN17',
                'definisi_anomali' => 'Pemeriksaan kode wilayah (kecamatan dan desa tidak sesuai).',
                'detil_anomali' => 'Kombinasi kode kecamatan dan desa tidak terdaftar.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:12:01'
            ],
            [
                'id' => 18,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN18',
                'definisi_anomali' => 'Validasi data penggunaan air minum dan sanitasi.',
                'detil_anomali' => 'Air minum dari PDAM, namun tidak memiliki fasilitas sanitasi yang layak.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:12:30'
            ],
            [
                'id' => 19,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN19',
                'definisi_anomali' => 'Cek kelengkapan data vaksinasi pada anak-anak.',
                'detil_anomali' => 'Anak usia sekolah tidak memiliki catatan vaksinasi dasar lengkap.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:13:00'
            ],
            [
                'id' => 20,
                'id_kegiatan' => 1,
                'kode_anomali' => 'AN20',
                'definisi_anomali' => 'Pemeriksaan status kepemilikan hewan ternak (jumlah melebihi batas wajar).',
                'detil_anomali' => 'Ditemukan jumlah ternak yang tidak realistis untuk ukuran rumah tangga biasa.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:13:30'
            ]
        ];
        $data_tambahan = [
            [
                'id' => 21,
                'id_kegiatan' => 6,
                'kode_anomali' => 'AN21',
                'definisi_anomali' => 'Cek konsistensi data kepemilikan kendaraan pribadi.',
                'detil_anomali' => 'Kepemilikan kendaraan mewah tidak sebanding dengan pendapatan yang dicatat.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:14:05'
            ],
            [
                'id' => 22,
                'id_kegiatan' => 6,
                'kode_anomali' => 'AN22',
                'definisi_anomali' => 'Pemeriksaan kelengkapan kolom jenis pekerjaan (kosong).',
                'detil_anomali' => 'Responden usia produktif (25-55 tahun) tidak mencatat jenis pekerjaan.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:14:30'
            ],
            [
                'id' => 23,
                'id_kegiatan' => 7,
                'kode_anomali' => 'AN23',
                'definisi_anomali' => 'Validasi tanggal wawancara (terjadi setelah tanggal hari ini).',
                'detil_anomali' => 'Tanggal pengumpulan data terdeteksi di masa depan (kesalahan input).',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:15:00'
            ],
            [
                'id' => 24,
                'id_kegiatan' => 7,
                'kode_anomali' => 'AN24',
                'definisi_anomali' => 'Cek konsistensi jumlah anggota keluarga dengan jumlah kamar tidur.',
                'detil_anomali' => 'Jumlah penghuni terlalu banyak untuk jumlah kamar yang dicatat.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:15:25'
            ],
            [
                'id' => 25,
                'id_kegiatan' => 7,
                'kode_anomali' => 'AN25',
                'definisi_anomali' => 'Pemeriksaan data pendidikan terakhir (Tidak sekolah tetapi berpenghasilan tinggi).',
                'detil_anomali' => 'Tingkat pendidikan tidak sekolah namun memiliki profesi berpenghasilan premium.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:15:50'
            ],
            [
                'id' => 26,
                'id_kegiatan' => 8,
                'kode_anomali' => 'AN26',
                'definisi_anomali' => 'Validasi ketersediaan data kesehatan (golongan darah tidak valid).',
                'detil_anomali' => 'Kolom golongan darah terisi dengan karakter atau nilai yang tidak diizinkan.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:16:15'
            ],
            [
                'id' => 27,
                'id_kegiatan' => 8,
                'kode_anomali' => 'AN27',
                'definisi_anomali' => 'Cek anomali usia menikah pertama (dibawah usia legal).',
                'detil_anomali' => 'Usia pertama kali menikah responden terdeteksi di bawah 17 tahun.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:16:40'
            ],
            [
                'id' => 28,
                'id_kegiatan' => 8,
                'kode_anomali' => 'AN28',
                'definisi_anomali' => 'Pemeriksaan data kepemilikan BPJS/Asuransi Kesehatan.',
                'detil_anomali' => 'Pendapatan tinggi, namun tidak memiliki asuransi kesehatan.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:17:05'
            ],
            [
                'id' => 29,
                'id_kegiatan' => 9,
                'kode_anomali' => 'AN29',
                'definisi_anomali' => 'Konsistensi data status kepemilikan tanah (tidak punya tapi tercatat pertanian).',
                'detil_anomali' => 'Responden menyatakan tidak memiliki tanah tetapi terdata memiliki aktivitas pertanian.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:17:30'
            ],
            [
                'id' => 30,
                'id_kegiatan' => 9,
                'kode_anomali' => 'AN30',
                'definisi_anomali' => 'Cek anomali jumlah tanggungan keluarga (jumlah tidak realistis).',
                'detil_anomali' => 'Jumlah tanggungan keluarga melebihi batas yang wajar (misalnya >15 orang).',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:18:00'
            ],
            [
                'id' => 31,
                'id_kegiatan' => 9,
                'kode_anomali' => 'AN31',
                'definisi_anomali' => 'Pemeriksaan jenis lantai rumah dan status ekonomi.',
                'detil_anomali' => 'Lantai rumah dari tanah, namun status ekonomi tercatat sangat mampu.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:18:25'
            ],
            [
                'id' => 32,
                'id_kegiatan' => 10,
                'kode_anomali' => 'AN32',
                'definisi_anomali' => 'Validasi data penggunaan internet (terdapat penggunaan sangat tinggi dan rendah).',
                'detil_anomali' => 'Pengeluaran bulanan internet sangat besar, tetapi data pendidikan responden rendah.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:18:50'
            ],
            [
                'id' => 33,
                'id_kegiatan' => 10,
                'kode_anomali' => 'AN33',
                'definisi_anomali' => 'Cek data sumber air minum dengan sanitasi yang tersedia.',
                'detil_anomali' => 'Sumber air minum dari sumur terbuka, namun sanitasi modern.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:19:15'
            ],
            [
                'id' => 34,
                'id_kegiatan' => 10,
                'kode_anomali' => 'AN34',
                'definisi_anomali' => 'Pemeriksaan status kepemilikan sepeda motor (jumlah tidak wajar).',
                'detil_anomali' => 'Jumlah sepeda motor yang dimiliki per keluarga melebihi 5 unit.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:19:40'
            ],
            [
                'id' => 35,
                'id_kegiatan' => 11,
                'kode_anomali' => 'AN35',
                'definisi_anomali' => 'Konsistensi status gizi balita dengan pendapatan keluarga.',
                'detil_anomali' => 'Balita mengalami gizi buruk, padahal pendapatan keluarga tercatat tinggi.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:20:05'
            ],
            [
                'id' => 36,
                'id_kegiatan' => 11,
                'kode_anomali' => 'AN36',
                'definisi_anomali' => 'Validasi kepemilikan Kartu Keluarga Sejahtera (KKS) dan ekonomi.',
                'detil_anomali' => 'KKS terisi "Ya" namun rumah tangga memiliki aset mahal.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:20:30'
            ],
            [
                'id' => 37,
                'id_kegiatan' => 11,
                'kode_anomali' => 'AN37',
                'definisi_anomali' => 'Cek anomali lama tinggal di lokasi saat ini (kurang dari 1 tahun).',
                'detil_anomali' => 'Lama tinggal di lokasi saat ini kurang dari 1 tahun, tetapi klaim domisili tetap.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:20:55'
            ],
            [
                'id' => 38,
                'id_kegiatan' => 12,
                'kode_anomali' => 'AN38',
                'definisi_anomali' => 'Pemeriksaan data kepemilikan ternak besar (di wilayah non-pertanian).',
                'detil_anomali' => 'Kepemilikan ternak sapi/kerbau di wilayah pusat kota yang padat.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:21:20'
            ],
            [
                'id' => 39,
                'id_kegiatan' => 12,
                'kode_anomali' => 'AN39',
                'definisi_anomali' => 'Validasi data penggunaan listrik (sumber ilegal).',
                'detil_anomali' => 'Tercatat menggunakan listrik, namun tidak memiliki meteran PLN.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:21:45'
            ],
            [
                'id' => 40,
                'id_kegiatan' => 12,
                'kode_anomali' => 'AN40',
                'definisi_anomali' => 'Cek konsistensi tanggal meninggal dan usia (usia negatif).',
                'detil_anomali' => 'Tanggal meninggal tercatat sebelum tanggal lahir (kesalahan *entry*).',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:22:10'
            ],
            [
                'id' => 41,
                'id_kegiatan' => 13,
                'kode_anomali' => 'AN41',
                'definisi_anomali' => 'Pemeriksaan data kepemilikan laptop/komputer (tidak sinkron dengan pendidikan).',
                'detil_anomali' => 'Pendidikan tidak sekolah, tetapi memiliki beberapa perangkat komputer.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:22:35'
            ],
            [
                'id' => 42,
                'id_kegiatan' => 13,
                'kode_anomali' => 'AN42',
                'definisi_anomali' => 'Validasi jenis atap rumah (atap jerami di perumahan modern).',
                'detil_anomali' => 'Jenis atap tercatat sebagai bahan tradisional di lokasi urban.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:23:00'
            ],
            [
                'id' => 43,
                'id_kegiatan' => 13,
                'kode_anomali' => 'AN43',
                'definisi_anomali' => 'Cek anomali usia dan status pelajar/mahasiswa.',
                'detil_anomali' => 'Responden berusia >30 tahun tetapi masih tercatat sebagai pelajar.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:23:25'
            ],
            [
                'id' => 44,
                'id_kegiatan' => 14,
                'kode_anomali' => 'AN44',
                'definisi_anomali' => 'Konsistensi status perkawinan (menikah tapi tidak punya pasangan).',
                'detil_anomali' => 'Status menikah, namun pasangan tidak tercatat dalam KK yang sama.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:23:50'
            ],
            [
                'id' => 45,
                'id_kegiatan' => 14,
                'kode_anomali' => 'AN45',
                'definisi_anomali' => 'Pemeriksaan data pengeluaran (total pengeluaran sangat rendah/tinggi).',
                'detil_anomali' => 'Total pengeluaran rumah tangga tidak sesuai dengan standar hidup minimum.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:24:15'
            ],
            [
                'id' => 46,
                'id_kegiatan' => 14,
                'kode_anomali' => 'AN46',
                'definisi_anomali' => 'Cek anomali data jenis bahan bakar memasak (kayu bakar di apartemen).',
                'detil_anomali' => 'Penggunaan bahan bakar kayu/arang di bangunan bertingkat.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:24:40'
            ],
            [
                'id' => 47,
                'id_kegiatan' => 15,
                'kode_anomali' => 'AN47',
                'definisi_anomali' => 'Validasi data kepemilikan usaha (usia pemilik usaha tidak wajar).',
                'detil_anomali' => 'Usaha dimiliki oleh responden yang berusia sangat muda (<10 tahun).',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:25:05'
            ],
            [
                'id' => 48,
                'id_kegiatan' => 15,
                'kode_anomali' => 'AN48',
                'definisi_anomali' => 'Pemeriksaan tanggal pencatatan data (terlalu jauh dari tanggal upload).',
                'detil_anomali' => 'Data tercatat lebih dari 1 tahun yang lalu, dianggap basi.',
                'is_show' => FALSE,
                'date_created' => '2025-11-05 13:25:30'
            ],
            [
                'id' => 49,
                'id_kegiatan' => 15,
                'kode_anomali' => 'AN49',
                'definisi_anomali' => 'Cek kelengkapan data koordinat GPS lokasi survei.',
                'detil_anomali' => 'Kolom Latitude atau Longitude kosong atau bernilai nol.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:25:55'
            ],
            [
                'id' => 50,
                'id_kegiatan' => 15,
                'kode_anomali' => 'AN50',
                'definisi_anomali' => 'Konsistensi status kepemilikan TV (milik sendiri tetapi tidak ada listrik).',
                'detil_anomali' => 'Rumah tangga memiliki TV, tetapi tidak memiliki akses listrik.',
                'is_show' => TRUE,
                'date_created' => '2025-11-05 13:26:20'
            ]
        ];

        // Using Query Builder
        $this->db->table('kategori_anomali')->insertBatch($data);
    }
}
