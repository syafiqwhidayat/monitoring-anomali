<div class="card">
    <div class="card-header">
        <h3 class="card-title">Profil Saya</h3>
    </div>
    <form action="/profile/update" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?= $user->email ?>" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= $user->username ?>" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Anda</label>
                        <div>
                            <?php foreach ($roles as $role): ?>
                                <span class="badge bg-blue-lt"><?= esc($role) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $user->nama) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Password (Kosongkan jika tidak ganti)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="pass_confirm" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>