<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Pengaturan Profil</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="avatar avatar-xl mb-3 rounded-circle bg-primary-lt">
                            <?= strtoupper(substr($user->username, 0, 2)) ?>
                        </span>
                        <h3 class="m-0 mb-1"><?= esc($user->name ?: $user->username) ?></h3>
                        <div class="text-muted small">@<?= esc($user->username) ?></div>
                        <div class="mt-3">
                            <?php foreach ($roles as $role): ?>
                                <span class="badge bg-blue-lt text-capitalize"><?= $role ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="card-btn w-100 text-muted">
                            Terdaftar: <?= $user->created_at->format('d M Y') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <form action="<?= base_url('profile/update') ?>" method="post" class="card">
                    <?= csrf_field() ?>
                    <div class="card-header">
                        <h3 class="card-title">Detail Informasi</h3>
                    </div>
                    <div class="card-body">
                        <?php if (session('message')) : ?>
                            <div class="alert alert-success"><?= session('message') ?></div>
                        <?php endif ?>
                        <?php if (session('errors')) : ?>
                            <div class="alert alert-danger">Silakan periksa kembali inputan Anda.</div>
                        <?php endif ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat Email</label>
                                    <input type="text" class="form-control" value="<?= $user->email ?>" readonly disabled>
                                    <small class="form-hint">Email tidak dapat diubah.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?= $user->username ?>" readonly disabled>
                                    <small class="form-hint">Username bersifat permanen.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control <?= session('errors.nama') ? 'is-invalid' : '' ?>"
                                value="<?= old('nama', $user->nama) ?>" placeholder="Masukkan nama asli">
                            <div class="invalid-feedback"><?= session('errors.nama') ?></div>
                        </div>

                        <hr>
                        <h4 class="mb-3 text-primary">Keamanan</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" placeholder="Isi hanya jika ingin ganti">
                                    <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="pass_confirm" class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>" placeholder="Ulangi password baru">
                                    <div class="invalid-feedback"><?= session('errors.pass_confirm') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>