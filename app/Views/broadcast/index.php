<?= $this->extend('layout/template'); ?>

<?= $this->section('content') ?>
<div class="page-header d-print-none mb-2">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Broadcast Informasi</h2>
            </div>
            <?php $allowedRoles = ['superadmin', 'admin'];
            if (in_array(session('aktif_role'), $allowedRoles)): ?>
                <div class="col-auto ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tambah-broadcast">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Buat Pengumuman
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- allert message -->
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

<!-- allert message -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-error alert-dismissible fade show" role="alert">
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

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- filter -->
                <?php if (auth()->user()->wilayah_kerja === "1300" && session('aktif_role') !== 'mitra'): ?>
                    <div class="card card-body mb-5">
                        <div class="hr-text hr-text-left fs-5 mb-3">Filter Broadcast</div>
                        <div class="mb-3">
                            <form action="<?= base_url('/broadcast') ?>" method="get">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Wilayah Broadcast</label>
                                        <select name="fil-wilayah" class="form-select" id="filter-wilayah">
                                            <?php foreach ($listWilayah as $l): ?>
                                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterWilayah) ? 'selected' : ''; ?>><?= $l['nama']; ?></option>
                                            <?php endforeach; ?>
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
                <?php endif; ?>

                <ul class="timeline">
                    <?php foreach ($broadcasts as $b) : ?>
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-<?= $b['warna'] ?>-lt text-<?= $b['warna'] ?>">
                                <?php if ($b['level'] == 'pusat'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-fortress">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 21h1a1 1 0 0 0 1 -1v-1a3 3 0 0 1 6 0m3 2h1a1 1 0 0 0 1 -1v-15l-3 -2l-3 2v6h-4v-6l-3 -2l-3 2v15a1 1 0 0 0 1 1h2m8 -2v1a1 1 0 0 0 1 1h2" />
                                        <path d="M7 7v.01" />
                                        <path d="M7 10v.01" />
                                        <path d="M7 13v.01" />
                                        <path d="M17 7v.01" />
                                        <path d="M17 10v.01" />
                                        <path d="M17 13v.01" />
                                    </svg>
                                <?php elseif ($b['level'] == 'provinsi'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-pavilion">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 21h7v-3a2 2 0 0 1 4 0v3h7" />
                                        <path d="M6 21l0 -9" />
                                        <path d="M18 21l0 -9" />
                                        <path d="M6 12h12a3 3 0 0 0 3 -3a9 8 0 0 1 -9 -6a9 8 0 0 1 -9 6a3 3 0 0 0 3 3" />
                                    </svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-community">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 9l5 5v7h-5v-4m0 4h-5v-7l5 -5m1 1v-6a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v17h-8" />
                                        <path d="M13 7l0 .01" />
                                        <path d="M17 7l0 .01" />
                                        <path d="M17 11l0 .01" />
                                        <path d="M17 15l0 .01" />
                                    </svg>
                                <?php endif; ?>
                            </div>

                            <div class="card timeline-event-card shadow-sm">
                                <div class="ribbon ribbon-bottom bg-<?= $b['warna'] ?>"><?= $b['kategori'] ?></div>

                                <div class="card-body">
                                    <div class="text-secondary float-end small"><?= $b['created_at'] ?></div>
                                    <h4 class="mb-1"><?= $b['judul'] ?></h4>
                                    <h6 class="mb-1 text-primary"> Admin <?= $b['level']; ?></h6>
                                    <p class="text-secondary mb-3"><?= $b['isi'] ?></p>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill btn-edit-broadcast"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-tambah-broadcast"
                                                data-id="<?= $b['id'] ?>"
                                                data-judul="<?= $b['judul'] ?>"
                                                data-isi="<?= $b['isi'] ?>"
                                                data-kategori="<?= $b['kategori'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                    <path d="M13.5 6.5l4 4" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill btn-hapus-broadcast"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-hapus-broadcast"
                                                data-id="<?= $b['id'] ?>"
                                                data-judul="<?= $b['judul'] ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                Hapus
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M5 12l5 5l10 -10" />
                                                </svg>
                                                Paham
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="editBroadcast(<?= $b['id'] ?>)" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ban">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                    <path d="M5.7 5.7l12.6 12.6" />
                                                </svg>
                                                Kurang Paham
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="editBroadcast(<?= $b['id'] ?>)" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                                Lihat Respon
                                            </button>
                                        </div>
                                        <div class="avatar-list avatar-list-stacked">
                                            <span class="avatar avatar-xs rounded-circle bg-light text-dark fw-bold" title="Akses Wilayah">W</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <li class="timeline-event">
                        <div class="timeline-event-icon bg-blue-lt text-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-fortress">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 21h1a1 1 0 0 0 1 -1v-1a3 3 0 0 1 6 0m3 2h1a1 1 0 0 0 1 -1v-15l-3 -2l-3 2v6h-4v-6l-3 -2l-3 2v15a1 1 0 0 0 1 1h2m8 -2v1a1 1 0 0 0 1 1h2" />
                                <path d="M7 7v.01" />
                                <path d="M7 10v.01" />
                                <path d="M7 13v.01" />
                                <path d="M17 7v.01" />
                                <path d="M17 10v.01" />
                                <path d="M17 13v.01" />
                            </svg>
                        </div>

                        <div class="card timeline-event-card shadow-sm">
                            <div class="ribbon ribbon-bottom bg-blue">SOP</div>
                            <div class="card-body">
                                <h4 class="mb-1">Kegiatan Dilaksanakan</h4>
                                <h6 class="mb-1 text-primary"> Admin Pusat</h6>
                                <p class="text-secondary mb-3">Kegiatan Pendataan lapangan sudah dilaksanakan.</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah dan Edit -->
<div class="modal modal-blur fade" id="modal-tambah-broadcast" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('/broadcast/simpan') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="judul-broadcast">Buat Broadcast Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="br-id" id="br-id">
                    <div class="mb-3">
                        <label class="form-label">Judul Broadcast</label>
                        <input type="text" name="br-judul" id="br-judul" class="form-control" required>
                        <small class="form-hint mt-2">Masukkan Judul Broadcast. Panjang maksimal 40 karakter.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Broadbast</label>
                        <textarea type="text" name="br-isi" id="br-isi" class="form-control" required></textarea>
                        <small class="form-hint mt-2">Masukkan Isi Broadcast. Panjang maksimal 300 karakter.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Broadcast</label>
                        <small class="form-hint mt-2">Pilih Kategori</small>
                        <select class="form-control" name="br-kategori" id="br-kategori" required>
                            <option selected>--Pilih Kategori--</option>
                            <option value="sop">SOP</option>
                            <option value="kondef">Konsep Definisi</option>
                            <option value="kbli">KBLI</option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success ms-auto">Kirim Broadcast</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal modal-blur fade" id="modal-hapus-broadcast" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('/broadcast/hapus') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasih Hapus Broadcast</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>Apakah Anda Yakin Menghapus Broadcast: </span>
                    <span id="display-judul" class="fw-bold">-</span>
                    <input type="hidden" name="id" id="hp-id" value="">
                    <div class="d-flex justify-content-around mt-3">
                        <button type=" button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-success ms-auto">Ya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?= base_url('js/broadcast.js'); ?>"></script>
<?= $this->endSection() ?>