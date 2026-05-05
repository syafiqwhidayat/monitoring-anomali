<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-xl">
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Manajemen Wilayah Tugas</h2>
                <div class="text-muted mt-1">Pemetaan wilayah SLS terhadap Mitra dan Petugas Organik</div>
            </div>
            <div class="col-auto ms-auto">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUpload">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <polyline points="7 9 12 4 17 9" />
                        <line x1="12" y1="4" x2="12" y2="16" />
                    </svg>
                    Upload Master Wilayah
                </button>
            </div>
        </div>
    </div>

    <div class="card mb-3 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <form action="" method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kabupaten</label>
                    <select name="kec" class="form-select">
                        <option value="">Semua Kecamatan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kecamatan</label>
                    <select name="kec" class="form-select">
                        <option value="">Semua Kecamatan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Desa</label>
                    <select name="desa" class="form-select">
                        <option value="">Semua Desa</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari Nama Mitra/SLS</label>
                    <input type="text" name="keyword" class="form-control" placeholder="Ketik nama...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

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
                                <div class="small text-muted"><?= $wt['kd_kab'] ?> - <?= $wt['nm_kab'] ?></div>
                                <div class="font-weight-medium"><?= $wt['kd_kec'] ?> - <?= $wt['nm_kec'] ?></div>
                            </td>
                            <td>
                                <div><?= $wt['kd_des'] ?> - <?= $wt['nm_des'] ?></div>
                            </td>
                            <td>
                                <span class="badge bg-blue-lt mb-1"><?= $wt['kd_sls'] . $wt['kd_subsls'] ?></span>
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
                                    data-kab="<?= $wt['kd_kab'] ?> - <?= $wt['nm_kab'] ?>"
                                    data-kec="<?= $wt['kd_kec'] ?> - <?= $wt['nm_kec'] ?>"
                                    data-desa="<?= $wt['kd_des'] ?> - <?= $wt['nm_des'] ?>"
                                    data-sls="<?= $wt['kd_sls'] . $wt['kd_subsls'] ?> - <?= $wt['nm_sls'] ?>"
                                    data-ppl="<?= $wt['nm_ppl'] ?>"
                                    data-pml="<?= $wt['nm_pml'] ?>">
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

<div class="modal modal-blur fade" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('/wilayah/upload') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Wilayah Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Template Upload Wiayah Tugas:</p>
                    <a href="<?= base_url('/wilayah/downloadTemplate'); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Unduh Template Excel (.xlsx)
                    </a>
                    <div class="hr-text"></div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File (Excel/CSV)</label>
                        <input type="file" name="file_wilayah" class="form-control" required>
                        <small class="form-hint mt-2">Bisa gunakan file upload wilayah tugas Fasih. Gunakan format kolom: Kab, Kec, Desa, SLS, Email PML, Email PPL.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success ms-auto">Mulai Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modalEditPetugas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('manajemen-wilayah/update-petugas') ?>" method="post">
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
                        <select name="ppl_email" id="select-ppl" class="form-select">
                            <option value="">-- Pilih Petugas --</option>
                            <?php foreach ($list_mitra as $m): ?>
                                <option value="<?= $m['email'] ?>"><?= $m['nama'] ?> (<?= $m['email'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Petugas PML</label>
                        <select name="pml_email" id="select-pml" class="form-select">
                            <option value="">-- Pilih Petugas --</option>
                            <?php foreach ($list_organik as $o): ?>
                                <option value="<?= $o['nama'] ?>"><?= $o['nama'] ?></option>
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