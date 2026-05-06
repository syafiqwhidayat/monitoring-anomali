<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-xl">
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Edit User Organik</h2>
                <div class="text-muted mt-1">Ubah Identitas User pada Sistem Sidik Anomali</div>
                <?php if (session()->has('errors')) : ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        <div class="col-md-8">
            <form action="<?= base_url('/user/edit/store'); ?>" method="post" class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="id" value=<?= $id; ?>>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Alamat Email BPS</label>
                            <input type="email" name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                                placeholder="contoh@bps.go.id" value="<?= old('email') ? old('email') : $email ?>" required>
                            <div class="invalid-feedback"><?= session('errors.email') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>"
                                placeholder="Masukkan nama tanpa gelar" value="<?= old('name') ? old('name') : $name ?>" required>
                            <div class="invalid-feedback"><?= session('errors.name') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Wilayah Kerja (Kab/Kota)</label>
                            <select name="wilayah_kerja" class="form-select <?= session('errors.wilayah_kerja') ? 'is-invalid' : '' ?>"
                                value="<?= old('wilayah_kerja') ?>" required>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                <?php foreach ($wilayah_sumbar as $kode => $nama) : ?>
                                    <option value="<?= $kode; ?>" <?= (old('wilayah_kerja') == $kode) ? "selected" : (($wilayah_kerja == $kode) ? "selected" : "") ?>><?= $nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= session('errors.wilayah_kerja') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Role Akses</label>
                            <select name="role" class="form-select <?= session('errors.role') ? 'is-invalid' : '' ?>" required>
                                <?php foreach ($roles as $kode => $nama) : ?>
                                    <option value="<?= $kode; ?>" <?= (old('role') == $kode) ? "selected" : (($role == $kode) ? "selected" : "") ?>><?= $nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= session('errors.role') ?></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Ubah Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan Password Jika tidak ingin diubah"
                                value="<?= old('password') ?>">
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end bg-light">
                    <div class="d-flex">
                        <a href="<?= base_url('/user/organik'); ?>" class="btn btn-link">Batal</a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <circle cx="12" cy="14" r="2" />
                                <polyline points="14 4 14 8 8 8 8 4" />
                            </svg>
                            Simpan User
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <div class="card bg-primary-lt">
                <div class="card-body">
                    <h3 class="card-title text-primary">Informasi Role</h3>
                    <ul class="list-unstyled space-y-1">
                        <li>
                            <strong>Admin:</strong> Memiliki akses penuh termasuk manajemen user dan pengaturan kategori anomali.
                        </li>
                        <hr class="my-2">
                        <li>
                            <strong>Operator:</strong> Berfokus pada monitoring dan pembersihan anomali di wilayah masing-masing.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>