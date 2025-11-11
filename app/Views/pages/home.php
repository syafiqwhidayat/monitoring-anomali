<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Halaman Awal Monitoring Anomali</h1>
            <?php
            d($data);
            ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>