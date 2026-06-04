<!-- v_laporan_list.php -->
<!-- Panggil layout dashboard utama aplikasi Anda di sini, misalnya: -->
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="fas fa-ticket text-primary me-2"></i> Layanan Aduan Kendala FASIH</h3>
        <span class="text-muted small"><?= date('d F Y'); ?></span>
    </div>

    <!-- TAMPILAN JIKA ADA NOTIFIKASI SUKSES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- 1. FORMULIR TAMBAH LAPORAN (HANYA MUNCUL JIKA USER LOGIN SEBAGAI MITRA / PPL) -->
        <?php if (session()->get('aktif_role') === 'mitra'): ?>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="fas fa-paper-plane me-1"></i> Buat Laporan Kendala</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= base_url('fasihhelpdesk/storeLaporan'); ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field(); ?>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Judul Kendala / Eror</label>
                                <input type="text" name="judul_kendala" class="form-control"
                                    placeholder="Contoh: Gagal Kirim SLS 002B, Error 500" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi Kronologi Masalah</label>
                                <textarea name="deskripsi" class="form-control" rows="5"
                                    placeholder="Ceritakan detail kronologi, tipe HP, atau kode error yang muncul di lapangan..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Upload Tangkapan Layar / Screenshot <span class="text-muted">(Opsional)</span></label>
                                <input type="file" name="foto_kendala" class="form-control" accept="image/*">
                                <div class="form-text text-muted" style="font-size: 11px;">Gunakan format gambar (.jpg, .jpeg, .png) maksimal 2MB.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold mt-2 text-white">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Aduan Ke Admin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 2. TABEL DAFTAR TIKET ADUAN (LEBAR ADAPTIF) -->
        <div class="<?= (session()->get('aktif_role') === 'mitra') ? 'col-lg-8' : 'col-12'; ?>">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-list-ul me-1"></i>
                        <?= (session()->get('aktif_role') === 'mitra') ? 'Riwayat Aduan Saya' : 'Antrean Tiket Kendala Masuk (Semua Petugas)'; ?>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" style="font-size: 14px;">
                            <thead class="table-light text-uppercase font-weight-bold" style="font-size: 12px;">
                                <tr>
                                    <th class="px-3" style="width: 5%;">No</th>
                                    <th style="width: 15%;">Pelapor</th>
                                    <th style="width: 35%;">Judul Kendala</th>
                                    <th style="width: 12%;">Wilayah</th>
                                    <th style="width: 13%;">Tanggal Kirim</th>
                                    <th class="text-center" style="width: 10%;">Status</th>
                                    <th class="text-center" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($laporan)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Belum ada rekaman aduan kendala yang terdata.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php $no = 1;
                                foreach ($laporan as $row): ?>
                                    <tr>
                                        <td class="px-3"><?= $no++; ?></td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= esc($row['nama_pelapor'] ?? 'Petugas'); ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary mb-0"><?= esc($row['judul_kendala']); ?></div>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;">
                                                <?= esc($row['deskripsi']); ?>
                                            </small>
                                        </td>
                                        <td><span class="badge bg-secondary text-white"><?= esc($row['id_wilayah']); ?></span></td>
                                        <td><?= date('d/m/Y H:i', strtotime($row['date_created'])); ?></td>
                                        <td class="text-center">
                                            <?php if ($row['status'] === 'open'): ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-white px-2 py-1 text-uppercase" style="font-size: 11px;">Open</span>
                                            <?php else: ?>
                                                <span class="badge bg-success bg-opacity-10 text-white px-2 py-1 text-uppercase" style="font-size: 11px;">Closed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('fasihhelpdesk/detailLaporan/' . $row['id']); ?>" class="btn btn-outline-primary btn-sm px-3 fw-bold" style="font-size: 12px;">
                                                <i class="fas fa-comments me-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>