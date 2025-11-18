<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Edit Anomali</h1>
            <p>untuk id: <?= $data['id']; ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <form>
                <div class="mb-3">
                    <label for="kdAnom" class="form-label">Kode Anomali</label>
                    <input type="text" class="form-control" id="kdAnom" aria-describedby="kdAnomHelp" value="<?= $data['kode_anomali']; ?>" readonly>
                    <div id="kdAnomHelp" class="form-text">Kode Anomali tidak dapat di edit</div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="UpdateAll" value="<?= $data['is_show']; ?>" <?= ($data['is_show']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="UpdateAll">
                            <i class="bi <?= ($data['is_show']) ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-warning'; ?>"></i> Is <?= ($data['is_show']) ? '' : 'Not'; ?> Show
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="defAnom" class="form-label">Definisi Anomali</label>
                    <textarea class="form-control" id="defAnom" aria-describedby="defAnomHelp" rows="4"><?= $data['definisi_anomali']; ?></textarea>
                    <div id="defAnomHelp" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="detAnom" class="form-label">Detil Anomali</label>
                    <textarea class="form-control" id="detAnom" aria-describedby="detAnomHelp" rows="3"><?= $data['detil_anomali']; ?></textarea>
                    <div id="detAnomHelp" class="form-text"></div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>