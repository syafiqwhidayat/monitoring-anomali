<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3">Form Tambah Data Komik</h2>
            <?= $validation->listErrors(); ?>
            <form action="/comics/save" method="post">
                <?= csrf_field(); ?>
                <div class="row mb-3">
                    <label for="judul" class="col-sm-3 col-form-label">Judul Komik</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="judul" name="judul" autofocus value="<?= old('judul'); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="penulis" class="col-sm-3 col-form-label">Penulis</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="penulis" name="penulis" value="<?= old('penulis'); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="penerbit" class="col-sm-3 col-form-label">Penerbit</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= old('penerbit'); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="sampul" class="col-sm-3 col-form-label">Sampul</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="sampul" name="sampul" value="<?= old('sampul'); ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Data Komik</button>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>