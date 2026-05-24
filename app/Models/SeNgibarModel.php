<?php

namespace App\Models;

use CodeIgniter\Model;

class SeNgibarModel extends Model
{
    protected $table            = 'se_mon_ngibar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_log', 'jenis_kegiatan', 'kode_wilayah', 'status', 'jumlah_assignment'];

    // Mendapatkan ID Log unggahan paling mutakhir
    public function getLatestLogId()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('se_upload_log');
        $row = $builder->select('id')->where('jenis', 'ngibar')->orderBy('id', 'DESC')->limit(1)->get()->getRow();
        return $row ? $row->id : null;
    }

    // Mendapatkan ringkasan atas 3 indikator utama berdasarkan log terupdate
    public function getLatestSummary($latestLogId)
    {
        if (!$latestLogId) return ['total_submit' => 0, 'total_rejected' => 0, 'total_draft' => 0];

        return $this->select("
            SUM(CASE WHEN status = 'Submited by Responden' THEN jumlah_assignment ELSE 0 END) as total_submit,
            SUM(CASE WHEN status = 'Rejected by Admin' THEN jumlah_assignment ELSE 0 END) as total_rejected,
            SUM(CASE WHEN status = 'Draft' THEN jumlah_assignment ELSE 0 END) as total_draft
        ")->where('id_log', $latestLogId)->first();
    }

    // Mengambil proporsi data pie chart terupdate
    public function getPieData($latestLogId, $jenisKegiatan = null)
    {
        if (!$latestLogId) return ['submitted' => 0, 'rejected' => 0, 'draft' => 0, 'open' => 0];

        $builder = $this->select("
            SUM(CASE WHEN status = 'Submited by Responden' THEN jumlah_assignment ELSE 0 END) as submitted,
            SUM(CASE WHEN status = 'Rejected by Admin' THEN jumlah_assignment ELSE 0 END) as rejected,
            SUM(CASE WHEN status = 'Draft' THEN jumlah_assignment ELSE 0 END) as draft,
            SUM(CASE WHEN status = 'Open' THEN jumlah_assignment ELSE 0 END) as open
        ")->where('id_log', $latestLogId);

        if ($jenisKegiatan) {
            $builder->where('jenis_kegiatan', $jenisKegiatan);
        }

        return $builder->first();
    }

    // Mengambil progress breakdown per wilayah terupdate
    public function getBarData($latestLogId, $jenisKegiatan = null)
    {
        $wilayahList = ['1301', '1302', '1303', '1304', '1305', '1306', '1307', '1308', '1309', '1310', '1311', '1312', '1371', '1372', '1373', '1374', '1375', '1376', '1377'];
        $result = [
            'categories' => ['1301', '1302', '1303', '1304', '1305', '1306', '1307', '1308', '1309', '1310', '1311', '1312', '1371', '1372', '1373', '1374', '1375', '1376', '1377'],
            'submitted'  => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'rejected'   => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'draft'      => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            'open'       => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ];

        if (!$latestLogId) return $result;

        $builder = $this->select("kode_wilayah,
            SUM(CASE WHEN status = 'Submited by Responden' THEN jumlah_assignment ELSE 0 END) as submitted,
            SUM(CASE WHEN status = 'Rejected by Admin' THEN jumlah_assignment ELSE 0 END) as rejected,
            SUM(CASE WHEN status = 'Draft' THEN jumlah_assignment ELSE 0 END) as draft,
            SUM(CASE WHEN status = 'Open' THEN jumlah_assignment ELSE 0 END) as open
        ")->where('id_log', $latestLogId);

        if ($jenisKegiatan) {
            $builder->where('jenis_kegiatan', $jenisKegiatan);
        }

        $queryData = $builder->groupBy('kode_wilayah')->findAll();

        foreach ($queryData as $row) {
            $index = array_search($row['kode_wilayah'], $wilayahList);
            if ($index !== false) {
                $result['submitted'][$index] = (int)$row['submitted'];
                $result['rejected'][$index]  = (int)$row['rejected'];
                $result['draft'][$index]     = (int)$row['draft'];
                $result['open'][$index]      = (int)$row['open'];
            }
        }
        return $result;
    }

    // Mendapatkan data runtun waktu (Line Chart) tren kumulatif setiap log upload
    public function getTimelineData()
    {
        $db = \Config\Database::connect();
        return $db->table('se_upload_log l')
            ->select("DATE_FORMAT(l.created_at, '%d/%m') as label,
                SUM(CASE WHEN s.status = 'Submited by Responden' THEN s.jumlah_assignment ELSE 0 END) as submitted,
                SUM(CASE WHEN s.status = 'Rejected by Admin' THEN s.jumlah_assignment ELSE 0 END) as rejected,
                SUM(CASE WHEN s.status = 'Draft' THEN s.jumlah_assignment ELSE 0 END) as draft,
                SUM(CASE WHEN s.status = 'Open' THEN s.jumlah_assignment ELSE 0 END) as open")
            ->join('se_mon_ngibar s', 's.id_log = l.id', 'left')
            ->where('jenis', 'ngibar')
            ->groupBy('l.id')
            ->orderBy('l.id', 'ASC')
            ->get()->getResultArray();
    }
}
