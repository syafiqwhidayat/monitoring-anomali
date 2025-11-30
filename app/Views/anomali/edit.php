<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Edit Anomali</h1>
            <p>untuk id: <?= $data['id']; ?></p>
        </div>
    </div>
    <?php
    $errors['kode_anomali'] = '';
    $errors = session()->getFlashdata('errors');
    ?>
    <?php if (session()->getFlashdata('message_errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message_errors'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col">
            <form
                action="<?= base_url('/anomali/updateKategori'); ?>"
                method="post">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                <div class="mb-3">
                    <label for="kdAnom" class="form-label">Kode Anomali</label>
                    <input type="text" name="kode_anomali" class="form-control <?= ($errors['kode_anomali']) ? 'is-invalid' : ''; ?>"
                        id="kdAnom" aria-describedby="kdAnomHelp"
                        value="<?= (old('kode_anomali')) ? old('kode_anomali') : $data['kode_anomali']; ?>">
                    <div class="invalid-feedback">
                        <?= $errors['kode_anomali']; ?>
                    </div>
                    <div id="kdAnomHelp" class="form-text">Kode Anomali tidak dapat di edit</div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_show" value="not_show_id_<?= $data['id']; ?>">
                        <input
                            class="form-check-input" type="checkbox"
                            role="switch" id="is_show_checkbox" name="is_show"
                            value="show_id_<?= $data['id']; ?>" <?= ($data['is_show']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="UpdateAll">
                            <i class="bi <?= ($data['is_show']) ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-warning'; ?>"></i> Is <?= ($data['is_show']) ? '' : 'Not'; ?> Show
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="defAnom" class="form-label">Definisi Anomali</label>
                    <textarea class="form-control" id="defAnom"
                        aria-describedby="defAnomHelp"
                        name="definisi_anomali"
                        rows="4"><?= $data['definisi_anomali']; ?></textarea>
                    <div id="defAnomHelp" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="detAnom" class="form-label">Detil Anomali</label>
                    <textarea
                        class="form-control"
                        id="detAnom"
                        name="detil_anomali"
                        aria-describedby="detAnomHelp"
                        rows="3"><?= $data['detil_anomali']; ?></textarea>
                    <div id="detAnomHelp" class="form-text"></div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>