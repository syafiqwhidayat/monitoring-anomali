<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Konfirmasi_Fasih_" . $filterWilayah . "_" . date('Ymd') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
    .text-clean {
        mso-number-format: "\@";
        text-align: center;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="8" style="font-size: 16px; font-weight: bold; text-align: center; height: 35px; background-color: #f2f2f2;">
                DAFTAR CATATAN EVALUASI ANOMALI PETUGAS (KOREKSI ULANG)
            </th>
        </tr>
        <tr style="background-color: #EE8911; color: white; font-weight: bold; text-align: center; height: 30px;">
            <th style="width: 50px;">No</th>
            <th style="width: 120px;">Kode Wilayah</th>
            <th style="width: 120px;">Kode Anomali</th>
            <th style="width: 350px; text-align: left;">Kriteria Kesalahan Data</th>
            <th style="width: 180px;">Kode Assignment</th>
            <th style="width: 220px; text-align: left;">Nama Objek / Ruta</th>
            <th style="width: 400px; background-color: #94C11F; color: black; text-align: left;">Isi Jawaban Petugas Sebelumnya</th>
            <th style="width: 160px;">Tanggal Update Terakhir</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($listAnom as $row): ?>
            <tr>
                <td style="text-align: center;"><?= $no++; ?></td>
                <td class="text-clean"><?= $row['id_wilayah']; ?></td>
                <td style="text-align: center; font-weight: bold; color: red;"><?= $row['kode_anomali']; ?></td>
                <td><?= $row['detil_anomali']; ?></td>
                <td class="text-clean"><?= $row['kd_assigment']; ?></td>
                <td><?= $row['nm_krt'] ?? $row['nm_art'] ?? $row['nm_nrt'] ?? '-'; ?></td>
                <td style="color: #b02a37; font-style: italic; background-color: #94C11F;"><?= $row['konfirmasi']; ?></td>
                <td style="text-align: center;"><?= date('Y-m-d H:i', strtotime($row['date_updated'])); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($listAnom)): ?>
            <tr>
                <td colspan="8" style="text-align: center; font-style: italic; height: 30px;">Tidak ada data anomali berulang.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>