<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-xl">
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Manajemen Wilayah Tugas</h2>
                <div class="text-muted mt-1">Pemetaan wilayah SLS terhadap Mitra dan Petugas Organik</div>
            </div>
        </div>
    </div>

    <!-- Allert Error -->
    <?php if (session()->getFlashdata('message_errors')) : ?>
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
                <div><?= session()->getFlashdata('message_errors'); ?></div>
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

    <!-- filter -->
    <div class="card mb-3 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <form action="<?php base_url('wilayah') ?>" method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kabupaten</label>
                    <select name="sel-kab" class="form-select">
                        <option value="">Semua Kabupaten</option>
                        <?php foreach ($list_kab ?? [] as $kab): ?>
                            <option value="<?= $kab['id']; ?>" <?= ($sel_kab == $kab['id']) ? 'selected' : ''; ?>><?= $kab['nama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kecamatan</label>
                    <select name="sel-kec" class="form-select">
                        <option value="">Semua Kecamatan</option>
                        <?php foreach ($list_kec ?? [] as $kab): ?>
                            <option value="<?= $kab['id']; ?>" <?= ($sel_kec == $kab['id']) ? 'selected' : ''; ?>><?= $kab['nama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Desa</label>
                    <select name="sel-des" class="form-select">
                        <option value="">Semua Desa</option>
                        <?php foreach ($list_des ?? [] as $kab): ?>
                            <option value="<?= $kab['id']; ?>" <?= ($sel_des == $kab['id']) ? 'selected' : ''; ?>><?= $kab['nama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari Nama Mitra/SLS</label>
                    <input type="text" name="sel-keyword" class="form-control" value="<?= $sel_keyword; ?>" placeholder="Ketik nama...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- isi -->
    <div class="container-xl">
        <div class="card shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Kab / Kec</th>
                            <th>Desa / Kel</th>
                            <th>Kode & Nama SLS</th>
                            <th>Petugas PPL</th>
                            <th>Petugas PML</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wilayah_tugas as $wt) : ?>
                            <tr>
                                <td>
                                    <div class="small text-muted">
                                        <?php if (!empty($wt['kd_kab'])) : ?>
                                            <?= $wt['kd_kab'] ?> - <?= $wt['nm_kab'] ?>
                                        <?php else: ?>
                                            tidak ada
                                        <?php endif; ?>
                                    </div>
                                    <div class="font-weight-medium">
                                        <?php if (!empty($wt['kd_kec'])) : ?>
                                            <?= $wt['kd_kec'] ?> - <?= $wt['nm_kec'] ?>
                                        <?php else: ?>
                                            tidak ada
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?php if (!empty($wt['kd_des'])) : ?>
                                            <?= $wt['kd_des'] ?> - <?= $wt['nm_des'] ?>
                                        <?php else: ?>
                                            tidak ada
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt mb-1">
                                        <?php if (!empty($wt['kd_sls'])) : ?>
                                            <?= $wt['kd_sls'] . $wt['kd_subsls'] ?>
                                        <?php else: ?>
                                            tidak ada
                                        <?php endif; ?>
                                    </span>
                                    <div class="small"><?= $wt['nm_sls']  ?></div>
                                </td>
                                <td>
                                    <div class="font-weight-medium"><?= $wt['nm_ppl'] ?></div>
                                    <div class="small text-muted"><?= $wt['em_ppl']  ?></div>
                                </td>
                                <td>
                                    <div class="font-weight-medium"><?= $wt['nm_pml'] ?></div>
                                    <div class="small text-muted"><?= $wt['em_pml']  ?></div>
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-ghost-primary btn-edit-wilayah"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditPetugas"
                                        data-id="<?= $wt['id_wt']; ?>"
                                        data-kab="<?= $wt['kd_kab'] ?> - <?= $wt['nm_kab'] ?>"
                                        data-kec="<?= $wt['kd_kec'] ?> - <?= $wt['nm_kec'] ?>"
                                        data-desa="<?= $wt['kd_des'] ?> - <?= $wt['nm_des'] ?>"
                                        data-sls="<?= $wt['kd_sls'] . $wt['kd_subsls'] ?> - <?= $wt['nm_sls'] ?>"
                                        data-ppl="<?= $wt['id_ppl'] ?>"
                                        data-pml="<?= $wt['id_pml'] ?>">
                                        Edit Petugas
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- modal edit -->
<div class="modal modal-blur fade" id="modalEditPetugas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('wilayah/edit') ?>" method="post">
                <input type="hidden" name="id" id="select-id" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Penanggung Jawab Wilayah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label mb-1 text-muted small">Kabupaten</label>
                                <div id="display-kab" class="fw-bold">-</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mb-1 text-muted small">Kabupaten</label>
                                <div id="display-kec" class="fw-bold">-</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mb-1 text-muted small">Desa/Kelurahan</label>
                                <div id="display-desa" class="fw-bold">-</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mb-1 text-muted small">SLS/Non-SLS</label>
                                <div id="display-sls" class="fw-bold text-primary">-</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="form-label">Petugas PPL</label>
                        <select name="sel-ppl" id="select-ppl" class="form-select" placeholder="Cari Email Petugas...">
                            <option value=""></option>
                            <?php foreach ($list_user as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= $m['nama'] ?> (<?= $m['email'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Petugas PML</label>
                        <select name="sel-pml" id="select-pml" class="form-select">
                            <option value=""></option>
                            <?php foreach ($list_user as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= $m['nama'] ?> (<?= $m['email'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('js/scriptWilayahTugas.js'); ?>"></script>
<?= $this->endSection(); ?>