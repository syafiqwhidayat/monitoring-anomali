<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container" jenis-konf="0">
    <div class="row">
        <div class="col">
            <h1>Konfirmasi Bulk</h1>
            <p>Hati-hati, jika isian konfirmasi diisi kosong, akan menghapus semua isian sebelumnya</p>
        </div>
    </div>
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col">
            <form
                action="<?= base_url('/anomali/updateKonfirmasi'); ?>"
                method="post">
                <input type="hidden" name="bulk" value=1>
                <div class="mb-3">
                    <label for="kdAnom" class="form-label">Kode Anomali</label>
                    <select class="form-control form-select" name="kode_anomali" aria-label="Default select example">
                        <?php foreach ($listKodeAnom as $l) : ?>
                            <option value="<?= $l['id']; ?>"><?= $l['kode_anomali']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_show" value="not_show_id_1">
                        <label class="form-check-label" for="UpdateAll">
                            <i class="bi <?= ($data['is_show']) ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-warning'; ?>"></i> Is <?= ($data['is_show']) ? '' : 'Not'; ?> Show
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="defAnom" class="form-label">Konfirmasi Anomali</label>
                    <textarea class="form-control" id="defAnom"
                        aria-describedby="defAnomHelp"
                        name="konfirmasi"
                        rows="4"></textarea>
                    <div id="defAnomHelp" class="form-text"></div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>