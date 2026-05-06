<div class="nav-item dropdown">
    <a href="<?= base_url(); ?>" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
        Kegiatan : <strong><?= session()->get('nama_kegiatan') ?? 'Pilih...' ?></strong>
    </a>
    <div class="dropdown-menu">
        <?php foreach ($daftar_kegiatan as $keg): ?>
            <a href="<?= base_url('set-kegiatan/' . $keg['id']) ?>" class="dropdown-item">
                <?= esc($keg['nama']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>