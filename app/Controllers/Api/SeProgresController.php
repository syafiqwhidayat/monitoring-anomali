namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class SeProgresController extends ResourceController
{
protected $format = 'json';

public function uploadProgres()
{
$file = $this->request->getFile('file_monitoring');
$idUser = $this->request->getPost('id_user') ?? 1; // Default id_user system/superadmin
$idKegiatan = $this->request->getPost('id_kegiatan') ?? 2; // Default ID Kegiatan SE2026

if (!$file || !$file->isValid()) {
return $this->fail('File tidak valid atau tidak ditemukan.');
}

$originalName = $file->getClientName();
$today = date('Y-m-d'); // Kelompokkan log berdasarkan tanggal hari ini murni

// 1. Pindahkan file CSV ke folder temporary
$newName = $file->getRandomName();
$file->move(WRITEPATH . 'uploads/api_temp', $newName);
$filePath = WRITEPATH . 'uploads/api_temp/' . $newName;

$db = Database::connect();

// 2. Insert Log Awal dengan Status 'proses'
$db->table('se_log_upload')->insert([
'id_kegiatan' => $idKegiatan,
'jenis' => 'api_progres',
'nama_file' => $originalName,
'status' => 'proses',
'total_baris' => 0,
'berhasil' => 0,
'gagal' => 0,
'id_user' => $idUser,
'created_at' => date('Y-m-d H:i:s')
]);
$logId = $db->insertID();

// 3. Mulai pemrosesan file CSV
$totalBaris = 0;
$berhasil = 0;
$gagal = 0;
$errorDetails = [];
$detectedKab = null;

$db->transStart();

try {
if (($handle = fopen($filePath, "r")) !== FALSE) {
// Baca separator CSV (Kustomisasi mendeteksi titik koma ';')
$header = fgetcsv($handle, 0, ";");

$batchData = [];
while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
if (empty($row[0])) continue; // Skip jika baris kosong

$totalBaris++;

// Ambil 4 digit kode kabupaten dari baris pertama sebagai sampel validasi
if ($detectedKab === null) {
$detectedKab = substr($row[1], 0, 4); // Mengambil dari KODE_SUBSLS_16

// HAPUS DATA LAMA KABUPATEN INI DI HARI INI (REPLACE LOGIC)
$db->table('se_progres_subsls')
->where('tanggal', $today)
->like('id_subsls', $detectedKab, 'after')
->delete();
}

// Mapping Data Kolom CSV sesuai urutan instruksi Anda
try {
$batchData[] = [
'id_sls' => $row[0], // KODE_SLS_14
'id_subsls' => $row[1], // KODE_SUBSLS_16
'nama_county' => $row[2], // NAMA_KABUPATEN
'nama_kabupaten' => $row[2],
'nama_kecamatan' => $row[3], // NAMA_KECAMATAN
'nama_nagari' => $row[4], // NAMA_NAGARI
'total' => (int)$row[5],
'open' => (int)$row[6],
'submitted_by_pencacah' => (int)$row[7],
'draft' => (int)$row[8],
'rejected_by_pengawas' => (int)$row[9],
'approved_by_pengawas' => (int)$row[10],
'revoked_by_pengawas' => (int)$row[11],
'submitted_respondent' => (int)$row[12],
'tanggal' => $today
];
$berhasil++;
} catch (\Throwable $e) {
$gagal++;
$errorDetails[] = "Baris {$totalBaris}: " . $e->getMessage();
}

// Tulis per chunk 200 baris agar menghemat memori RAM server
if (count($batchData) >= 200) {
$db->table('se_progres_subsls')->insertBatch($batchData);
$batchData = [];
}
}

// Sisa batch data dimasukkan
if (!empty($batchData)) {
$db->table('se_progres_subsls')->insertBatch($batchData);
}

fclose($handle);
}

unlink($filePath); // Hapus file temporary
$db->transComplete();

// 4. Update Log Akhir (Selesai/Gagal)
$statusAkhir = ($gagal === 0 && $berhasil > 0) ? 'selesai' : (($berhasil > 0) ? 'selesai' : 'gagal');

$db->table('se_log_upload')->where('id', $logId)->update([
'status' => $statusAkhir,
'total_baris' => $totalBaris,
'berhasil' => $berhasil,
'gagal' => $gagal,
'error_details' => !empty($errorDetails) ? json_encode($errorDetails) : null,
'updated_at' => date('Y-m-d H:i:s')
]);

return $this->respond([
'status' => 200,
'message' => "Progres Kab [{$detectedKab}] berhasil diperbarui untuk tanggal {$today}.",
'summary' => ['total' => $totalBaris, 'berhasil' => $berhasil, 'gagal' => $gagal]
]);

} catch (\Throwable $th) {
$db->transRollback();
if (file_exists($filePath)) unlink($filePath);

// Tandai log sebagai gagal total
$db->table('se_log_upload')->where('id', $logId)->update([
'status' => 'gagal',
'error_details' => $th->getMessage() . "\n" . $th->getTraceAsString(),
'updated_at' => date('Y-m-d H:i:s')
]);

return $this->failServerError('Gagal memproses file progres: ' . $th->getMessage());
}
}
}