<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<h3>Hasil Import Data</h3>

<div class="alert alert-info">
    Total Data Berhasil: <strong><?= $totalSuccess ?></strong>
</div>

<h4>Laporan Sukses Per Wilayah</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID Wilayah (SLS)</th>
            <th>Jumlah Berhasil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($successReport as $wilayah => $jumlah): ?>
            <tr>
                <td><?= $wilayah ?></td>
                <td><?= $jumlah ?> baris</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr>

<?php if (!empty($errors)): ?>
    <h4 class="text-danger">Daftar Gagal Import</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Baris</th>
                <th>ID Assigment</th>
                <th>Pesan Error</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($errors as $err): ?>
                <tr>
                    <td><?= $err['baris'] ?></td>
                    <td><code><?= $err['id_assigment'] ?></code></td>
                    <td>
                        <ul class="text-danger">
                            <?php foreach ($err['messages'] as $m): ?>
                                <li><?= $m ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-success">Semua data berhasil divalidasi!</div>
<?php endif; ?>


<?= $this->endSection(); ?>