<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="card card-body">
        <h1>Manajemen Anomali</h1>
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('message'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
    <div class="card card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kode Anomali</th>
                    <th scope="col">Flag</th>
                    <th scope="col">Deskripsi Anmali</th>
                    <th scope="col">Detil Anomali</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listAnom as $l): ?>
                    <tr>
                        <th scope="row"><i class="bi <?= ($l['is_show']) ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-warning'; ?>"></i></th>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type=" button" class="btn btn-primary rounded-pill"><?= $l['kode_anomali']; ?></button>
                            </div>
                        </td>
                        <td><button type=" button" class="btn btn-warning p-1 "><i class="fas fa-flag"></i><?= $l['flag']; ?></button></td>
                        <td><?= $l['definisi_anomali']; ?></td>
                        <td><?= $l['detil_anomali']; ?></td>
                        <td>
                            <form action="<?= base_url('/manajemen-anomali/action'); ?>" method="POST">
                                <input type="hidden" name="id" value="<?= $l['id']; ?>">
                                <input type="hidden" name="is_show" value="<?= $l['is_show']; ?>">
                                <button type="submit"
                                    name="action" value="toggle"
                                    class="btn btn-primary p-1 d-flex align-items-center"><img src="<?= ($l['is_show']) ? '/img/icons/eye-slash.svg' : '/img/icons/eye.svg'; ?>" alt="Ikon Simpan" width="16" height="16"></button>
                                <a aria-current="page" class="btn btn-warning p-1 d-flex justify-content-center a-butt" href="<?= base_url('/manajemen-anomali/edit/' . $l['id']); ?>"><img src="/img/icons/edit.svg" alt="Ikon Simpan" width="16" height="16"></a>
                                <button type="button"
                                    name="action" value="delete"
                                    data-bs-toggle="modal" data-bs-target="#konfirHapus"
                                    data-id="<?= $l['id']; ?>"
                                    data-kode="<?= $l['kode_anomali']; ?>"
                                    class="btn btn-danger p-1 d-flex align-items-center"><img src="/img/icons/trash.svg" alt="Ikon Simpan" width="16" height="16"></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <form id="deleteFormModal" action="<?= base_url('/manajemen-anomali/action'); ?>" method="POST">
                            <input type="hidden" name="id" id="delete_id_input">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-12">
                <?= $pager->links('default', 'my_pager'); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="konfirHapus" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="icon mb-2 text-danger icon-lg" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path
                        d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                </svg>
                <h3>Apakah anda yakin?</h3>
                <div class="text-secondary">
                    Menghapus Kategori Anomali maka akan menghapus seluruh anomali dengan kategori tersebut pada Rumah Tangga.
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal"> Cancel </a>
                        </div>
                        <div class="col">
                            <!-- <a href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                Ya, Hapus </a> -->
                            <form id="deleteFormModal" action="<?= base_url('/manajemen-anomali/action'); ?>" method="POST">
                                <input type="hidden" name="id" id="delete_id_input">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger w-100" data-bs-dismiss="modal">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>