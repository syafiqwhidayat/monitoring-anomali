<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="card card-body">
        <h1>Manajemen Anomali</h1>
    </div>

    <!-- filter -->
    <div class="card card-body">
        <div class="hr-text hr-text-left fs-5 mb-3">Filter Anomali</div>
        <div class="mb-3">
            <form action="<?= base_url('/manajemen-anomali/list') ?>" method="get">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Level Anomali</label>
                        <select name="fil-level" class="form-select" id="filter-level">
                            <?php foreach ($listLevel as $l): ?>
                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterLevel) ? 'selected' : ''; ?>>Anomali <?= ($l['id'] == null) ? "Semua" : $l['id']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Flag Prioritas</label>
                        <select name="fil-flag" class="form-select" id="filter-flag">
                            <option value="">Semua Jenis</option>
                            <?php foreach ($listSelFlag as $l): ?>
                                <option value="<?= $l['value']; ?>" <?= ($l['value'] == $filterFlag) ? 'selected' : ''; ?>> Prioritas <?= $l['value']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="tombolFilterEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-4 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                            </svg>
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Allert Error -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('error'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Allert Pesan -->
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('message'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- ini tabel -->
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kode Anomali</th>
                        <th scope="col">Flag</th>
                        <th scope="col">Level</th>
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
                            <td><button type=" button" class="btn btn-warning p-1 "><i class="fas fa-flag"> <?= $l['flag']; ?> </i></button></td>
                            <td>
                                <span class="badge bg-blue-lt">
                                    <?= $l['level_anomali']; ?>
                                </span>
                            </td>
                            <td><?= $l['definisi_anomali'] ?? '<span class="fst-italic text-muted">Belum didefinisikan</span>'; ?></td>
                            <td><?= $l['detil_anomali'] ?? '<span class="fst-italic text-muted">Belum didetilkan</span>'; ?></td>
                            <td>
                                <div>
                                    <form action="<?= base_url('/manajemen-anomali/action'); ?>" method="POST" class="m-0">
                                        <input type="hidden" name="id" value="<?= $l['id']; ?>">
                                        <input type="hidden" name="is_show" value="<?= $l['is_show']; ?>">
                                        <button type="submit" name="action" value="toggle"
                                            class="btn btn-primary p-1 d-flex align-items-center"
                                            <?= ($l['level_anomali'] != auth()->user()->wilayah_kerja) ? 'disabled' : ''; ?>>
                                            <img src="<?= ($l['is_show']) ? base_url('/img/icons/eye-slash.svg') : base_url('/img/icons/eye.svg'); ?>" width="16" height="16">
                                        </button>
                                    </form>

                                    <a class="btn btn-warning p-1 d-flex justify-content-center a-butt" href="<?= base_url('/manajemen-anomali/edit/' . $l['id']); ?>">
                                        <img src="<?= base_url("/img/icons/edit.svg"); ?>" width="16" height="16">
                                    </a>

                                    <button type="button"
                                        class="btn btn-danger p-1 d-flex align-items-center btn-trigger-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#konfirHapus"
                                        data-id="<?= $l['id']; ?>"
                                        data-kode="<?= $l['kode_anomali']; ?>">
                                        <img src='<?= base_url("/img/icons/trash.svg"); ?>' style="pointer-events: none;" width="16" height="16">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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
                    Menghapus Kategori Anomali (<strong id="anomali-id-display"></strong>) maka akan menghapus seluruh anomali dengan kategori tersebut pada Rumah Tangga.
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

                                <button type="submit" class="btn btn-danger w-100">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('js/scriptManajemenAnomali.js'); ?>"></script>
<?= $this->endSection(); ?>