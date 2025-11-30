<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Manajemen Anomali</h1>
        </div>
    </div>
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col d-flex justify-content-end">
            <a class="btn btn-success" aria-current="page" href="<?= base_url('/anomali/upload'); ?>">Upload Anomali</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kode Anomali</th>
                        <th scope="col">Deskripsi Anmali</th>
                        <th scope="col">Detil Anomali</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listAnom as $l): ?>
                        <tr>
                            <th scope="row"><i class="bi <?= ($l['is_show']) ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-warning'; ?>"></i></th>
                            <td><button type=" button" class="btn btn-primary p-1 d-flex align-items-center"><?= $l['kode_anomali']; ?></button></td>
                            <td><?= $l['definisi_anomali']; ?></td>
                            <td><?= $l['detil_anomali']; ?></td>
                            <td>
                                <form action="<?= base_url('/anomali/manajemen-see'); ?>" method="POST">
                                    <input type="hidden" name="id" value="<?= $l['id']; ?>">
                                    <button type="submit"
                                        name="action" value="toggle"
                                        class="btn btn-primary p-1 d-flex align-items-center"><img src="<?= ($l['is_show']) ? '/img/icons/eye-slash.svg' : '/img/icons/eye.svg'; ?>" alt="Ikon Simpan" width="16" height="16"></button>
                                    <a aria-current="page" class="btn btn-warning p-1 d-flex justify-content-center a-butt" href="<?= base_url('/anomali/edit/' . $l['id']); ?>"><img src="/img/icons/edit.svg" alt="Ikon Simpan" width="16" height="16"></a>
                                    <button type="button"
                                        name="action" value="delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-id="<?= $l['id']; ?>"
                                        data-kode="<?= $l['kode_anomali']; ?>"
                                        class="btn btn-danger p-1 d-flex align-items-center"><img src="/img/icons/trash.svg" alt="Ikon Simpan" width="16" height="16"></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data dengan Kode-Anomali: <span id="anomali-id-display"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form id="deleteFormModal" action="<?= base_url('/anomali/manajemen-see'); ?>" method="POST">
                                <input type="hidden" name="id" id="delete_id_input">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?= $pager->links('default', 'my_pager'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>