<div class="nav-item dropdown">
    <a href="<?= base_url(); ?>" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
        Kegiatan : <strong><?= session()->get('nama_kegiatan') ?? 'Pilih...' ?></strong>
    </a>
    <div class="dropdown-menu">
        <?php foreach ($list_role as $role): ?>
            <a href="<?= base_url('set-role/' . $role) ?>?return=<?= current_url() ?>" class="dropdown-item">
                <?= esc($role) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>