<!-- v_knowledge_list.php -->
<!-- Panggil layout dashboard utama aplikasi Anda di sini, misalnya: -->
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>


<div class="container-fluid mt-3">
    <!-- TOP BAR & TOMBOL TAMBAH ADMIN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-book-reader text-primary me-2"></i> (FAQ) Pusat Pengetahuan Fasih </h3>
            <p class="text-muted small mb-0">Referensi solusi kedala Fasih dilapangan</p>
        </div>

        <!-- Tombol Tambah FAQ Manual Hanya Muncul Jika User Bukan Mitra (Admin/Organik) -->
        <?php if (session()->get('role') !== 'mitra'): ?>
            <button class="btn btn-primary text-white fw-bold mt-2 mt-md-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKB">
                <i class="fas fa-plus shadow-sm me-1"></i> Tambah FAQ Master Baru
            </button>
        <?php endif; ?>
    </div>

    <!-- FILTER PENCARIAN INTERNAL -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
        <div class="card-body p-3">
            <form method="GET" action="<?= base_url('fasihhelpdesk'); ?>">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="cari" class="form-control border-start-0 ps-0"
                        placeholder="Ketik kata kunci kendala aplikasi... (Contoh: error 403, gagal kirim, koordinat merah)"
                        value="<?= esc($filterCari); ?>">
                    <button class="btn btn-primary text-white fw-bold px-4" type="submit">Cari Solusi</button>
                    <?php if (!empty($filterCari)): ?>
                        <a href="<?= base_url('fasihhelpdesk'); ?>" class="btn btn-secondary d-flex align-items-center">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- DAFTAR GRID KNOWLEDGE BASE -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <?php if (empty($knowledges)): ?>
            <div class="col-12 w-100">
                <div class="alert alert-warning text-center p-4 rounded-3 border-0 shadow-sm">
                    <i class="fas fa-search-minus fa-2x text-warning mb-2"></i>
                    <h5 class="fw-bold">Solusi Tidak Ditemukan</h5>
                    <p class="text-muted small mb-0">Kata kunci tidak cocok dengan dokumen FAQ manapun di sistem.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($knowledges as $kb): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-3 hover-shadow">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary text-white" style="font-size: 11px;"><?= esc($kb['kategori']); ?></span>
                            <small class="text-orange" style="font-size: 11px;"><i class="far fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($kb['date_updated'])); ?></small>
                        </div>

                        <h5 class="card-title fw-bold text-dark text-line-clamp mb-2" style="font-size: 16px;"><?= esc($kb['judul']); ?></h5>

                        <p class="text-muted small mb-3 flex-grow-1" style="font-size: 13px; line-height: 1.5;">
                            <strong>Gejala:</strong> <?= substr(strip_tags($kb['masalah']), 0, 95) . '...'; ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <button class="btn btn-primary btn-sm text-white fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalBacaKB<?= $kb['id']; ?>">
                                Lihat Solusi <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i>
                            </button>
                            <!-- TOMBOL SALIN TAUTAN TANPA EMBEL-EMBEL WA -->
                            <button class="btn-action-share " onclick="copyLink('<?= base_url('faq/baca/' . $kb['id']); ?>')">
                                <i class="fas fa-link me-1"></i>
                            </button>

                            <!-- Aksi Edit Khusus Admin -->
                            <?php if (session()->get('aktif_role') === 'admin' || session()->get('aktif_role') === 'superadmin'): ?>
                                <button class="btn btn-outline-secondary btn-sm p-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEditKB<?= $kb['id']; ?>" title="Edit FAQ">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =============================================================
                 MODAL POPUP 1: BACA DETAIL SOLUSI BERSTRUKTUR 
                 ============================================================= -->
            <div class="modal fade" id="modalBacaKB<?= $kb['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-primary text-white py-3">
                            <h5 class="modal-title fw-bold"><i class="fas fa-info-circle me-1"></i> Detail Knowledge Base</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <h4 class="text-primary fw-bold mb-1"><?= esc($kb['judul']); ?></h4>
                            <span class="badge bg-secondary text-white mb-4">Kategori: <?= esc($kb['kategori']); ?></span>

                            <!-- KOTAK GEJALA MASALAH -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-warning text-white fw-bold py-2" style="font-size: 13.5px;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Kendala Yang Muncul:
                                </div>
                                <div class="card-body bg-white py-2 text-muted" style="font-size: 14px;">
                                    <?= esc($kb['masalah']); ?>
                                </div>
                            </div>

                            <!-- KOTAK SOLUSI PENANGANAN -->
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-primary text-white fw-bold py-2" style="font-size: 13.5px;">
                                    <i class="fas fa-check-circle me-1"></i> Langkah Penanganan Masalah:
                                </div>
                                <div class="card-body bg-white py-3 text-dark" style="white-space: pre-line; font-size: 14.5px; line-height: 1.6;">
                                    <?= esc($kb['solusi']); ?>
                                </div>
                            </div>

                            <!-- MEDIA GAMBAR PANDUAN -->
                            <?php if (!empty($kb['foto'])): ?>
                                <div class="text-center mt-3 p-2 bg-light rounded border">
                                    <label class="d-block text-muted small fw-bold mb-2"><i class="fas fa-image"></i> Gambar / Screenshot Panduan:</label>
                                    <a href="<?= base_url('uploads/helpdesk/knowledge/' . $kb['foto']); ?>" target="_blank">
                                        <img src="<?= base_url('uploads/helpdesk/knowledge/' . $kb['foto']); ?>" class="img-fluid rounded border shadow-sm" style="max-height: 300px; object-fit: contain;">
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer bg-light py-2">
                            <small class="text-muted me-auto">Diterbitkan oleh: <strong><?= esc($kb['pembuat'] ?? 'Admin IT'); ?></strong></small>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Tutup Dokumen</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =============================================================
                 MODAL POPUP 2: EDIT FAQ MASTER (KHUSUS ADMIN)
                 ============================================================= -->
            <?php if (session()->get('aktif_role') !== 'mitra'): ?>
                <div class="modal fade" id="modalEditKB<?= $kb['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-dark text-white py-3">
                                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-1"></i> Edit Dokumen FAQ Master</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <!-- 1. PASTIKAN ACTION SEPERTI INI (MELEMPARKAN ID DI URL) -->
                            <form action="<?= base_url('fasihhelpdesk/updateKnowledge/' . $kb['id']); ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field(); ?>

                                <!-- 2. HAPUS ATAU BIARKAN INPUT HIDDEN ID INI, KARENA ID SUDAH ADA DI URL ACTION -->
                                <input type="hidden" name="id" value="<?= $kb['id']; ?>">

                                <div class="modal-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold">Judul Master FAQ</label>
                                            <input type="text" name="judul" class="form-control" value="<?= esc($kb['judul']); ?>" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Kategori</label>
                                            <input type="text" name="kategori" class="form-control" value="<?= esc($kb['kategori']); ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Definisi Masalah (Gejala)</label>
                                            <textarea name="masalah" class="form-control" rows="3" required><?= esc($kb['masalah']); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Prosedur Langkah Solusi</label>
                                            <textarea name="solusi" class="form-control" rows="5" required><?= esc($kb['solusi']); ?></textarea>
                                        </div>

                                        <!-- === PERUBAHAN DI SINI: PREVIEW GAMBAR LAMA MURNI PHP === -->
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Ganti / Tambah Gambar Panduan</label>

                                            <!-- Jika di database ada foto, langsung render preview-nya lewat kondisi PHP -->
                                            <?php if (!empty($kb['foto'])): ?>
                                                <div class="mb-2">
                                                    <span class="d-block small text-muted mb-1">Gambar aktif saat ini:</span>
                                                    <img src="<?= base_url('uploads/helpdesk/knowledge/' . $kb['foto']); ?>" class="img-thumbnail" style="max-height: 120px;">
                                                </div>
                                            <?php endif; ?>

                                            <input type="file" name="foto_knowledge" class="form-control" accept="image/*">
                                            <div class="form-text text-muted" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengubah atau mengganti dokumen gambar yang sudah ada.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light py-2">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success btn-sm text-white px-3 fw-bold">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</div>

<!-- ====================================================================
     MODAL POPUP MASTER: INPUT FAQ MANUAL BARU (KHUSUS ADMIN)
     ==================================================================== -->
<div class="modal fade" id="modalTambahKB" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus me-1"></i> Tambah Referensi FAQ Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('fasihhelpdesk/storeKnowledge'); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Judul Pokok Bahasan</label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: [Eror 403] Gagal Ambil Alokasi Tugas" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kategori Sistem</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Contoh: Aplikasi FASIH, GPS, Kuesioner" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Uraian Gejala Kendala (Masalah)</label>
                            <textarea name="masalah" class="form-control" rows="3" placeholder="Tuliskan keluhan atau teks indikator eror yang sering dirasakan petugas lapangan..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Dokumentasi Petunjuk Solusi</label>
                            <textarea name="solusi" class="form-control" rows="5" placeholder="Tulis instruksi panduan langkah-demi-langkah penyelesaian kendala teknis tersebut..." required></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Upload Gambar / Skema Panduan <span class="text-muted">(Opsional)</span></label>
                            <input type="file" name="foto_knowledge" class="form-control" accept="image/*">
                            <div class="form-text text-muted" style="font-size: 11px;">Format gambar valid (.jpg, .jpeg, .png) dengan ukuran berkas maksimal 2MB.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm text-white px-4 fw-bold">Terbitkan FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>