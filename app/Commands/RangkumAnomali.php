<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RangkumAnomali extends BaseCommand
{
    protected $group       = 'AI';
    protected $name        = 'ai:rangkum';
    protected $description = 'Merangkum jawaban konfirmasi anomali berdasarkan antrean request di tabel kesimpulan_anomali.';

    protected $options = [
        '--kategori' => 'ID Kategori untuk uji coba',
        '--wilayah'  => 'Kode Wilayah untuk uji coba',
    ];

    public function run(array $params)
    {
        $apiKey = env('gemini.apiKey');
        $model  = env('gemini.model') ?? 'gemini-1.5-flash';

        if (empty($apiKey)) {
            CLI::error('API Key Gemini belum disetting di file .env!');
            return;
        }

        // Ambil filter CLI jika sedang uji coba manual
        $filterKategori = CLI::getOption('kategori');
        $filterWilayah  = CLI::getOption('wilayah');

        $db = \Config\Database::connect();

        // 1. Query langsung ke tabel kesimpulan_anomali
        $requestQuery = $db->table('kesimpulan_anomali')
            ->select('kesimpulan_anomali.*, kategori_anomali.detil_anomali, kategori_anomali.definisi_anomali')
            ->join('kategori_anomali', 'kategori_anomali.id = kesimpulan_anomali.id_kategori_anomali');

        // Jika sedang UJI COBA: abaikan flag is_request
        if ($filterKategori || $filterWilayah) {
            if ($filterKategori) {
                $requestQuery->where('kesimpulan_anomali.id_kategori_anomali', $filterKategori);
            }
            if ($filterWilayah) {
                $requestQuery->where('kesimpulan_anomali.kode_wilayah', $filterWilayah);
            }
        } else {
            // Jika berjalan normal via CRON: Hanya proses yang is_request = 1
            $requestQuery->where('kesimpulan_anomali.is_request', 1);
        }

        $requests = $requestQuery->get()->getResultArray();

        if (empty($requests)) {
            CLI::write('Tidak ada antrean kesimpulan anomali (is_request = 1) yang perlu diproses.', 'yellow');
            return;
        }

        CLI::write("Ditemukan " . count($requests) . " antrean request kesimpulan. Memulai proses...", 'cyan');

        foreach ($requests as $req) {
            $idKategori   = $req['id_kategori_anomali'];
            $kodeWilayah  = $req['kode_wilayah'];
            $detilAnomali = $req['detil_anomali'] ?? $req['definisi_anomali'] ?? 'Tidak ada detail.';

            CLI::write("--------------------------------------------------", 'white');
            CLI::write("Memproses Kategori ID: {$idKategori} | Wilayah: {$kodeWilayah}", 'yellow');

            // Deteksi level wilayah berdasarkan panjang karakter kode_wilayah
            // Kode wilayah provinsi berakhiran '00' (contoh: 1300) dan panjangnya 4 digit atau kurang
            $isProvinsi = (strlen($kodeWilayah) == 4 && substr($kodeWilayah, 2, 2) == '00') || strlen($kodeWilayah) == 2;
            $kodeProv2Digit = $isProvinsi ? substr($kodeWilayah, 0, 2) : '';

            // Jalankan proses update akumulasi kesimpulan
            $this->prosesInkremental($db, $apiKey, $model, $idKategori, $detilAnomali, $kodeWilayah, $isProvinsi, $kodeProv2Digit, $req);

            // Jeda agar tidak terkena limitasi API Gemini (Free Tier)
            sleep(3);
        }

        CLI::write('Proses antrean kesimpulan selesai!', 'green');
    }

    private function prosesInkremental($db, $apiKey, $model, $idKategori, $detilAnomali, $kodeWilayah, $isProvinsi, $kodeProv2Digit, $existing)
    {
        $kesimpulanLama = $existing['hasil_kesimpulan'];
        $terakhirUpdate = $existing['date_updated'] ?? $existing['date_created'];

        // 1. Ambil data jawaban konfirmasi baru di tabel anomali
        $ansQuery = $db->table('anomali')
            ->select('konfirmasi')
            ->where('id_kategori_anomali', $idKategori)
            ->where('konfirmasi IS NOT NULL')
            ->where('konfirmasi !=', '')
            ->where('is_sistem !=', 1)
            ->where('date_deleted', null);

        if ($isProvinsi) {
            $ansQuery->where("LEFT(id_wilayah, 2)", $kodeProv2Digit);
        } else {
            $ansQuery->where("LEFT(id_wilayah, 4)", $kodeWilayah);
        }

        // Hanya ambil konfirmasi yang di-update setelah kesimpulan terakhir dibuat
        if ($terakhirUpdate && !empty($kesimpulanLama)) {
            $ansQuery->where("date_updated >", $terakhirUpdate);
        }

        $answers = $ansQuery->get()->getResultArray();

        // Jika tidak ada data konfirmasi baru
        if (empty($answers)) {
            CLI::write("   -> Tidak ada data konfirmasi baru sejak update terakhir ({$terakhirUpdate}).", 'white');
            return;
        }

        // Gabungkan jawaban konfirmasi baru
        $daftarJawabanBaru = [];
        foreach ($answers as $ans) {
            if (trim($ans['konfirmasi']) != '') {
                $daftarJawabanBaru[] = "- " . trim($ans['konfirmasi']);
            }
        }
        $teksJawabanBaru = implode("\n", $daftarJawabanBaru);

        CLI::write("   -> Menemukan " . count($daftarJawabanBaru) . " konfirmasi baru. Mengirim ke Gemini...", 'blue');

        // 2. Desain prompt kombinasi
        $prompt = "Anda adalah AI analis data statistik. Tugas Anda adalah memperbarui kesimpulan kualitatif berdasarkan adanya data konfirmasi baru dari petugas lapangan.\n\n"
            . "Konteks/Detail Masalah Anomali:\n"
            . "\"{$detilAnomali}\"\n\n";

        if (!empty($kesimpulanLama)) {
            $prompt .= "Berikut adalah Poin Kesimpulan yang Telah Dibuat Sebelumnya:\n"
                . "{$kesimpulanLama}\n\n"
                . "Berikut adalah Kumpulan Jawaban Konfirmasi BARU dari petugas yang masuk setelah kesimpulan di atas dibuat:\n"
                . "{$teksJawabanBaru}\n\n"
                . "TUGAS ANDA:\n"
                . "Analisis apakah jawaban baru di atas memuat alasan/pola baru yang belum tercover di kesimpulan lama. "
                . "Gabungkan dan perbarui kesimpulan tersebut menjadi satu kesatuan baru dengan syarat mutlak: TETAP BERUPA POIN-POIN RINGKAS ANTARA 2 SAMPAI MAKSIMAL 5 POIN SAJA. "
                . "Jika ada temuan baru yang penting dari jawaban baru tersebut, masukkan dengan mengeliminasi atau melebur poin lama yang kurang krusial agar jumlahnya tidak lebih dari 5 poin.";
        } else {
            $prompt .= "Berikut adalah Kumpulan Jawaban Konfirmasi dari petugas di lapangan:\n"
                . "{$teksJawabanBaru}\n\n"
                . "TUGAS ANDA:\n"
                . "Buatlah kesimpulan kualitatif ringkas berbentuk 2 sampai maksimal 5 poin inti mengenai:\n"
                . "1. Mengapa data terkonfirmasi demikian (alasan mayoritas lapangan).\n"
                . "2. Kondisi riil atau tindakan perbaikan konkret yang dilaporkan.\n"
                . "3. Jika tidak ada teks jawaban baru maka return kesimpulan sebelumnya, jika belum ada kesimpulan sebelumnya, maka isikan (-).\n";
        }

        $prompt .= "\n\nKetentuan:\n"
            . "- Gunakan bahasa Indonesia formal dan lugas.\n"
            . "- Langsung ke poin-poin tanpa kalimat pembuka atau penutup.";

        // 3. Panggil API Gemini
        $hasilRangkuman = $this->panggilGeminiAPI($apiKey, $model, $prompt);

        if ($hasilRangkuman) {
            // Update data hasil kesimpulan
            $db->table('kesimpulan_anomali')
                ->where('id', $existing['id'])
                ->update([
                    'hasil_kesimpulan' => trim($hasilRangkuman),
                    'date_updated'     => date('Y-m-d H:i:s')
                ]);

            CLI::write("   ✓ Kesimpulan berhasil diperbarui ke database.", 'green');
        } else {
            CLI::error("   ✗ Gagal mendapatkan respon dari AI.");
        }
    }

    private function panggilGeminiAPI($apiKey, $model, $prompt)
    {
        $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key=" . $apiKey;

        $data = [
            "contents" => [["parts" => [["text" => $prompt]]]]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }
}
