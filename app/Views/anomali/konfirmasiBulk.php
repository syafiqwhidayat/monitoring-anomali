<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container" jenis-konf="0">
    <div class="card card-body">
        <h1>Konfirmasi Bulk</h1>
        <p>Akan memberikan jawaban semua anomali yang konfirmasinya masih kosong. Tidak akan mempengaruhi Konfirmasi yang sudah terisi.</p>
    </div>
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/anomali/konfirmasiBulk') ?>" method="get">
        <input type="hidden" name="fil-anomali-old" value="<?= $filterAnomali; ?>">
        <div class="card card-body mb-5 ">
            <div class="hr-text hr-text-left fs-5 mb-3">Filter Broadcast</div>
            <div class="mb-3">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Pilih Kode Anomali</label>
                        <select name="fil-anomali" class="form-select" id="filter-anomali">
                            <?php foreach ($listKodeAnom as $l): ?>
                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterAnomali) ? 'selected' : ''; ?>><?= $l['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="tombolFilterEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-4 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                            </svg>
                            Pilih Anomali
                        </button>
                    </div>
                </div>
            </div>
            <?php if (!empty($data)) : ?>
                <div>
                    <div class="hr-text hr-text-left fs-5 mb-3">Filter Wilayah</div>
                    <div class="mb-3">
                        <div class="row g-3">
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

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" id="tombolFilterEdit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-4 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                                    </svg>
                                    Pilih Wilayah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <!-- card isian -->
    <?php if (!empty($data)) : ?>
        <div class="row row-cards card">
            <div class="col-12">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit-circle me-2 text-primary"></i> Detail & Konfirmasi Bulk Anomali
                    </h3>
                    <div class="card-actions">
                        <span class="badge bg-blue-lt">
                            <i class="ti ti-database-edit me-1"></i> <?= $jumlah_anomali; ?> data terdampak
                        </span>
                    </div>
                </div>
                <div class="card-body">

                    <!-- SEKSI INFO: Grid Layout -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="form-label text-muted">Kode Anomali</div>
                            <div class="h3 text-primary mb-0">
                                <i class="ti ti-hash me-1"></i> <?= $data['kode_anomali']; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label text-muted">Status Tampilan</div>
                            <div>
                                <?php if ($data['is_show']): ?>
                                    <span class="status status-success">
                                        <span class="status-dot status-dot-animated"></span>
                                        <i class="me-1 bi bi-eye-fill text-success"></i> Visible
                                    </span>
                                <?php else: ?>
                                    <span class="status status-warning">
                                        <i class="me-1 bi-eye-slash-fill text-warning"></i> Hidden
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label text-muted">Flag System</div>
                            <div class="text-danger fw-bold">
                                <i class="fas fa-flag"></i> <?= $data['flag']; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label text-muted">Wilayah Terpilih</div>
                            <div class="text-primer fw-bold">
                                <?= $sel_prov . $sel_kab . $sel_kec . $sel_des; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="bg-light p-3 rounded border-start border-primary border-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted fw-bold uppercase">Definisi</small>
                                        <p class="mb-0 text-secondary"><?= $data['definisi_anomali'] ?: 'Tidak ada deskripsi.'; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted fw-bold uppercase">Detail Teknis</small>
                                        <p class="mb-0 text-secondary"><?= $data['detil_anomali'] ?: '-'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- 2. BAGIAN FORM (INPUT) -->
                    <div class="form-text mt-2 italic text-danger">Bulk anomali hanya akan melakukan update kepada anomali yg belum di konfirmasi</div>
                    <form
                        action="<?= base_url('/anomali/updateKonfirmasi'); ?>"
                        method="post">
                        <input type="hidden" name="bulk" value=1>
                        <input type="hidden" name="id-kat" value="<?= $data['id']; ?>">
                        <input type="hidden" name="kd-prov" value="<?= $sel_prov; ?>">
                        <input type="hidden" name="kd-kab" value="<?= $sel_kab; ?>">
                        <input type="hidden" name="kd-kec" value="<?= $sel_kec; ?>">
                        <input type="hidden" name="kd-des" value="<?= $sel_des; ?>">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="konfirmasi" class="form-label fw-bold">Konfirmasi Anomali</label>
                                    <textarea class="form-control" id="konfirmasi" name="konfirmasi" rows="3" placeholder="Masukkan alasan atau penjelasan konfirmasi..." required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kondisi Lapangan?</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="kondisi_lapangan" id="kondisiLap" value="1">
                                        <label class="form-check-label" for="kondisiLap">Sesuai Kondisi Lapangan</label>
                                    </div>
                                    <div class="form-text mt-2 italic text-danger">*Ceklis jika anomali dianggap wajar dilapangan.</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Update Semua Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>