<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="card card-body">
    <h5 class="card-title">Petunjuk Pengunggahan</h5>
    <p class="card-text">
        Pastikan data yang Anda unggah sesuai dengan format yang telah ditentukan.
        Silakan unduh template di bawah ini sebagai acuan:
    </p>

    <a href="<?= base_url('upload/download-template'); ?>" class="btn btn-outline-primary">
        <i class="bi bi-download"></i> Unduh Template Excel (.xlsx)
    </a>
</div>

<div class="card card-body">
    <form action="/uploadFile" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fileAnom" class="form-label">Upload File Anomali</label>
            <input class="form-control" type="file" id="fileAnom" name="fileAnom">
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="UpdateAll" checked>
                <label class="form-check-label" for="UpdateAll">
                    <strong>Update All Anomali</strong>
                    <br> Ketika update all, maka akan akan update seluruh anomali
                    <br> Jika tidak Hanya akan update anomali yg di upload
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?= $this->endSection(); ?>