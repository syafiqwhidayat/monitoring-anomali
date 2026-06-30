<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Anomali_Per_Assignment_" . date('Ymd') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
    .text-string {
        mso-number-format: "\@";
        text-align: center;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="11" style="font-size: 16px; font-weight: bold; text-align: center; height: 40px; background-color: #e9ecef;">
                REKAPITULASI JAWABAN ANOMALI BERDASARKAN OBJEK (ASSIGNMENT)
            </th>
        </tr>
        <tr style="background-color: #0d6efd; color: white; font-weight: bold; text-align: center; height: 30px;">
            <th style="width: 140px;">Kode Assignment</th>
            <th style="width: 200px;">Nama KRT</th>
            <th style="width: 200px;">Nama ART</th>
            <th style="width: 150px;">Nama PPL</th>
            <th style="width: 150px;">Nama PML</th>
            <th style="width: 120px;">Kode Wilayah</th>
            <th style="width: 100px;">Kode Anomali</th>
            <th style="width: 350px;">Detil Anomali</th>
            <th style="width: 380px; background-color: #ffda6a; color: black;">Isi Konfirmasi / Tanggapan Lapangan</th>
            <th style="width: 100px;">Status Is_Lap</th>
            <th style="width: 100px;">Status Is_Insert</th>
            <th style="width: 140px;">Waktu Sinkronisasi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rowspans = array_count_values(array_column($listAnom, 'id_assignment_obj'));
        $displayed_assignment = [];

        foreach ($listAnom as $row):
            $current_id = $row['id_assignment_obj'];
            $is_first_row = !in_array($current_id, $displayed_assignment);
        ?>
            <tr>
                <?php if ($is_first_row):
                    $displayed_assignment[] = $current_id;
                ?>
                    <td rowspan="<?= $rowspans[$current_id]; ?>" class="text-string"><?= $row['kd_assigment']; ?></td>
                    <td rowspan="<?= $rowspans[$current_id]; ?>" style="vertical-align: top;"><?= $row['nm_krt'] ?? $row['nm_nrt'] ?? '-'; ?></td>
                    <td rowspan="<?= $rowspans[$current_id]; ?>" style="vertical-align: top;"><?= $row['nm_art'] ?? '-'; ?></td>
                    <td rowspan="<?= $rowspans[$current_id]; ?>" style="vertical-align: top;"><?= $row['nama_ppl'] ?? '-'; ?></td>
                    <td rowspan="<?= $rowspans[$current_id]; ?>" style="vertical-align: top;"><?= $row['nama_pml'] ?? '-'; ?></td>
                    <td rowspan="<?= $rowspans[$current_id]; ?>" class="text-string"><?= $row['id_wilayah']; ?></td>
                <?php endif; ?>

                <td style="text-align: center; font-weight: bold;"><?= $row['kode_anomali']; ?></td>
                <td><?= $row['detil_anomali']; ?></td>
                <td style="<?= empty($row['konfirmasi']) ? 'color: #999; text-align: center;' : 'color: #0056b3;'; ?>">
                    <?= !empty($row['konfirmasi']) ? $row['konfirmasi'] : '[ Belum Ada Konfirmasi ]'; ?>
                </td>
                <td style="text-align: center;"><?= $row['is_lap'] == 1 ? 'Kondisi Lapangan' : 'Bukan'; ?></td>
                <td style="text-align: center; font-weight: 500;"><?= $row['is_insert'] == 1 ? 'Masih Anomali' : 'Clean'; ?></td>
                <td style="text-align: center;"><?= date('Y-m-d H:i', strtotime($row['date_updated'])); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($listAnom)): ?>
            <tr>
                <td colspan="11" style="text-align: center; font-style: italic; height: 35px;">Data rekap kosong.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>