<?= $this->extend('layout/template'); ?>
<?= $this->section('content') ?>

<div class="container-xl">
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <div class="mb-1">
                    <a href="<?= base_url('ngibarmonitoring') ?>" class="text-secondary small fw-bold">← Kembali</a>
                </div>
                <h2 class="page-title text-dark">
                    Ruang Flagging Duplikasi Data Wilayah <span class="text-danger ms-1"><?= $idWilayah ?></span>
                </h2>

                <div class="text-muted small mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon d-inline text-azure" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v4" />
                        <path d="M12 16v.01" />
                        <path d="M5 12a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                    </svg>
                    Data hasil sinkronisasi *Python Engine Script*.
                    Diupload terakhir pada:
                    <span class="text-dark fw-bold">
                        <?= $waktuUploadTerakhir ? date('d M Y - H:i', strtotime($waktuUploadTerakhir)) . ' WIB' : 'Belum ada riwayat upload' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($groupedAssignments)): ?>
        <div class="card border-0 shadow-sm py-5 text-center">
            <div class="card-body">
                <div class="fw-bold text-success fs-2">Sistem Bersih</div>
                <p class="text-muted small mb-0">Tidak ada tumpukan data duplikat terdeteksi.</p>
            </div>
        </div>
    <?php else: ?>

        <?php $no = 1;
        foreach ($groupedAssignments as $idMirip => $rows): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
                    <div>
                        <span class="badge bg-purple-lt fw-bold">Kasus Kelompok #<?= $no++ ?></span>
                    </div>
                    <div>
                        <span class="text-secondary small">Grup ID Mirip: </span>
                        <strong class="text-dark font-monospace bg-white border px-2 py-0.5 rounded"><?= $idMirip ?></strong>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-hover mb-0">
                        <thead class="bg-light text-muted small uppercase">
                            <tr>
                                <th width="30%">Nama Usaha & Kode Identitas</th>
                                <th width="30%">Alamat Lokasi</th>
                                <th width="15%">Terakhir Update Lapangan</th>
                                <th width="13%">Status Dokumen</th>
                                <th width="12%" class="text-center">Keputusan Flag</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $assignment): ?>
                                <tr class="<?= $assignment['flag_status'] === 'Dipilih' ? 'bg-success-lt' : ($assignment['flag_status'] === 'Dieliminasi' ? 'text-muted bg-light' : '') ?>">
                                    <td>
                                        <div class="fw-bold text-dark mb-1"><?= esc($assignment['nama_usaha']) ?></div>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-red-lt font-monospace text-danger px-2 py-0.5 fw-bold" style="font-size: 12px;">
                                                <?= esc($assignment['kode_identitas']) ?>
                                            </span>
                                            <button class="btn btn-sm p-1 text-primary bg-transparent border-0 btn-copy-trigger"
                                                type="button"
                                                data-clipboard-text="<?= esc($assignment['kode_identitas']) ?>"
                                                title="Salin Kode Identitas">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                    <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-secondary d-block lh-sm"><?= esc($assignment['alamat_usaha']) ?></small>
                                        <span class="extra-small text-muted font-monospace" style="font-size: 11px;"><?= esc($assignment['email'] ?? 'no-email') ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-indigo mb-0">
                                            <?= date('d M Y', strtotime($assignment['date_updated'])) ?>
                                        </div>
                                        <div class="text-muted small"><?= date('H:i', strtotime($assignment['date_updated'])) ?> WIB</div>
                                    </td>
                                    <td>
                                        <?php if ($assignment['status'] === 'Submited by Responden'): ?>
                                            <span class="badge bg-success-lt fw-bold">Submitted</span>
                                        <?php elseif ($assignment['status'] === 'Draft'): ?>
                                            <span class="badge bg-warning-lt fw-bold">Draft</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-lt fw-bold">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($assignment['flag_status'] === 'Dipilih'): ?>
                                            <span class="badge bg-success text-white px-2 py-1 d-block fw-bold shadow-none">✓ Terpilih</span>
                                            <div class="extra-small text-muted mt-1" style="font-size:10px;">oleh: <?= esc($assignment['updated_by']) ?></div>
                                        <?php elseif ($assignment['flag_status'] === 'Dieliminasi'): ?>
                                            <span class="badge bg-secondary-lt text-secondary px-2 py-1 d-block">Dilewati</span>
                                        <?php else: ?>
                                            <a href="<?= base_url('se/flag-duplikat/' . $assignment['id'] . '/' . $assignment['id_mirip']) ?>"
                                                class="btn btn-sm btn-pill btn-primary py-1 px-2 font-weight-bold shadow-none">
                                                Gunakan Data Ini
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-center mt-4 mb-5">
            <?= $pagerLinks ?>
        </div>

    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menangkap aksi klik seluruh tombol copy secara independen
        const copyButtons = document.querySelectorAll('.btn-copy-trigger');

        copyButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const textToCopy = this.getAttribute('data-clipboard-text');
                const originalIcon = window.safari ? this.innerHTML : this.innerHTML; // Simpan markup ikon lama

                if (navigator.clipboard && window.isSecureContext) {
                    // Metode modern untuk protokol HTTPS / Localhost aman
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showSuccessFeedback(this, originalIcon);
                    });
                } else {
                    // Fallback otomatis untuk peramban lama atau koneksi HTTP non-secure
                    const textArea = document.createElement("textarea");
                    textArea.value = textToCopy;
                    textArea.style.position = "fixed";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showSuccessFeedback(this, originalIcon);
                    } catch (err) {
                        console.error('Gagal menyalin text: ', err);
                    }
                    document.body.removeChild(textArea);
                }
            });
        });

        function showSuccessFeedback(element, oldIcon) {
            // Ganti ikon menjadi centang hijau saat sukses
            element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon text-success m-0" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>`;
            setTimeout(() => {
                element.innerHTML = oldIcon;
            }, 1200);
        }
    });
</script>

<?= $this->endSection() ?>