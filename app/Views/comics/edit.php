<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h2 class="my-3">Form Ubah Data Komik</h2>
            <?= $validation->listErrors(); ?>
            <form action="/comics/update/<?= $comic['id']; ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" value="<?= $comic['slug']; ?>">
                <div class="row mb-3">
                    <label for="judul" class="col-sm-3 col-form-label">Judul Komik</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?= ($validation->hasError('judul')) ? 'is-invalid' : ''; ?>" id="judul" name="judul" autofocus value="<?= (old('judul')) ? old('judul') : $comic['judul']; ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('judul'); ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="penulis" class="col-sm-3 col-form-label">Penulis</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?= ($validation->hasError('penulis')) ? 'is-invalid' : ''; ?>" id="penulis" name="penulis" value="<?= (old('penulis')) ? old('penulis') : $comic['penulis']; ?>">
                    </div>
                    <div class="invalid-feedback">
                        <?= $validation->getError('penulis'); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="penerbit" class="col-sm-3 col-form-label">Penerbit</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?= ($validation->hasError('penerbit')) ? 'is-invalid' : ''; ?>" id="penerbit" name="penerbit" value="<?= (old('penerbit')) ? old('penerbit') : $comic['penerbit']; ?>">
                    </div>
                    <div class="invalid-feedback">
                        <?= $validation->getError('penerbit'); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="sampul" class="col-sm-3 col-form-label">Sampul</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="sampul" name="sampul" value="<?= (old('sampul')) ? old('sampul') : $comic['sampul']; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ubah Data Komik</button>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>