<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Upload Anomali</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <p>
                    Upload file akan memasukkan bulk anomali kedalam database.
                    Anomali yg tidak muncul di file upload terbaru akan dibuat sudah diselesaikan (dengan asumsi anomali tidak ditemukan lagi hasil run SQL terbaru),
                    <br> Template Anomali dapat diunduh dibawah:
                </p>
                <button href="/anomali/upload/template-anomali" type="button" class="btn btn-warning p-1 d-flex align-items-center"> <i class="bi bi-download"></i>Template Anomali</button>
            </div>
            <form>
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
                <div class="mb-3">
                    <label for="uploadFile" class="form-label">Upload Template Anomali</label>
                    <input class="form-control" type="file" id="formFile">
                </div>
                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>