<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-12 mb-3">
        <a href="<?= base_url('fasihhelpdesk/listLaporan'); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Tiket</a>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-dark text-white fw-bold">Detail Pengirim Tiket</div>
            <div class="card-body">
                <h4><strong>Judul Kasus:</strong> <?= esc($laporan['judul_kendala']); ?></h4>
                <p class="text-muted small mb-1">Pelapor: <strong><?= esc($laporan['pelapor']) ?? 'Pelapor'; ?></strong></p>
                <p class="text-muted small mb-1">Kode Wilayah Kerja: <span class="badge bg-secondary text-white"><?= esc($laporan['id_wilayah']); ?></span></p>
                <p class="text-muted small mb-2">Tanggal Masuk: <strong><?= date('d-m-Y H:i', strtotime($laporan['date_created'])); ?></strong></p>
                <hr>
                <label class="fw-bold small text-dark d-block">Kronologi Kasus:</label>
                <div class="p-2 bg-light rounded small"><?= esc($laporan['deskripsi']); ?></div>

                <?php if (!empty($laporan['foto'])): ?>
                    <div class="mt-2 text-center">
                        <a href="<?= base_url('uploads/helpdesk/diskusi/' . $laporan['foto']); ?>" target="_blank">
                            <img src="<?= base_url('uploads/helpdesk/diskusi/' . $laporan['foto']); ?>" class="img-thumbnail" style="max-height: 150px;" alt="Screnshot">
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($laporan['status'] === 'open' && session()->get('aktif_role') !== 'mitra'): ?>
                <div class="card-footer bg-light">
                    <div class="card-footer bg-light p-2">
                        <div class="btn-group w-100">
                            <a href="<?= base_url('fasihhelpdesk/closeLaporanBiasa/' . $laporan['id']); ?>"
                                class="btn btn-primary fw-bold w-75"
                                onclick="return confirm('Selesaikan laporan ini tanpa menjadikannya FAQ?');">
                                <i class="fas fa-check me-1"></i> Selesaikan Tiket
                            </a>

                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split border-y-0 border-white" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item text-primary fw-bold" href="<?= base_url('fasihhelpdesk/closeDanJadikanKnowledge/' . $laporan['id']); ?>"
                                        onclick="return confirm('Kasus ini dianggap baru/penting? Sistem akan menutup tiket sekaligus menerbitkannya ke Master FAQ.');">
                                        <i class="fas fa-lightbulb me-2"></i> Selesai & Jadikan FAQ Master
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-comments"></i> Riwayat Diskusi</span>
                <span class="badge bg-secondary text-white text-uppercase"><?= $laporan['status']; ?></span>
            </div>
            <div class="card-body bg-light" style="max-height: 400px; overflow-y: auto;">

                <?php foreach ($diskusi as $chat): ?>
                    <?php $isMe = ($chat['username'] == auth()->user()->username); ?>
                    <div class="mb-3 d-flex flex-column <?= $isMe ? 'align-items-end' : 'align-items-start'; ?>">
                        <div class="p-3 rounded-3 shadow-sm <?= $isMe ? 'bg-info text-white' : 'bg-white text-dark'; ?>" style="max-width: 80%; min-width: 150px;">
                            <div class="d-flex justify-content-between small mb-1 text-muted" style="font-size: 11px;">
                                <strong class="<?= $isMe ? 'text-primary' : 'text-primary'; ?>"><?= esc($chat['username']) ?? 'Pelapor'; ?></strong>
                                <span class="text-dark ms-3"><?= date('H:i', strtotime($chat['date_created'])); ?></span>
                            </div>

                            <?php if (strpos($chat['pesan'], '💡 **SOLUSI REFERENSI UTAMA [FAQ]:**') !== false): ?>
                                <div class="p-2 my-1 rounded bg-primary bg-opacity-10 border-start border-4 border-warning text-white small" style="font-style: italic;">
                                    <?= nl2br($chat['pesan']); ?>
                                </div>
                            <?php else: ?>
                                <p class="mb-0 small" style="white-space: pre-line;"><?= nl2br(esc($chat['pesan'])); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($chat['foto'])): ?>
                                <div class="mt-2 text-center">
                                    <a href="<?= base_url('uploads/helpdesk/diskusi/' . $chat['foto']); ?>" target="_blank">
                                        <img src="<?= base_url('uploads/helpdesk/diskusi/' . $chat['foto']); ?>" class="img-fluid rounded border" style="max-height: 150px;" alt="Lampiran">
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="card-footer bg-white">
                <?php if ($laporan['status'] === 'open'): ?>
                    <form action="<?= base_url('fasihhelpdesk/balasDiskusi/' . $laporan['id']); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row g-2">
                            <div class="col-12">
                                <textarea name="pesan" class="form-control form-control-sm" rows="2" placeholder="Tulis tanggapan balasan diskusi atau instruksi..."></textarea>
                            </div>

                            <?php if (session()->get('role') !== 'mitra'): ?>
                                <div class="col-md-6">
                                    <select name="id_knowledge_terkait" class="form-select form-select-sm">
                                        <option value="">-- Gunakan Rujukan Cepat FAQ Master --</option>
                                        <?php foreach ($masterKnowledge as $mk): ?>
                                            <option value="<?= $mk['id']; ?>"><?= esc($mk['judul']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-4">
                                <input type="file" name="foto_balasan" class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100 text-white fw-bold"><i class="fas fa-paper-plane"></i> Kirim</button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-secondary text-center mb-0 p-2 small font-italic fw-bold"><i class="fas fa-lock"></i> Sesi Obrolan Terkunci. Kasus Telah Dinyatakan Selesai.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>