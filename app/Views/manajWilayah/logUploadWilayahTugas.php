<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Log Import Wilayah Tugas</h2>
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
</div>

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

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Nama File</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Progress (Berhasil/Total)</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td class="text-secondary"><?= $log->created_at ?></td>
                                <td><?= $log->nama_file ?></td>
                                <td class="text-primary"><?= $log->email; ?></td>
                                <td>
                                    <?php if ($log->status == 'selesai'): ?>
                                        <span class="badge">Selesai</span>
                                    <?php elseif ($log->status == 'proses'): ?>
                                        <span class="badge">Sedang Diproses</span>
                                    <?php elseif ($log->status == 'pending'): ?>
                                        <span class="badge">Pending</span>
                                    <?php else: ?>
                                        <span class="badge">Gagal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= $log->berhasil ?></strong> / <?= $log->total_baris ?> Baris
                                    <?php if ($log->gagal > 0): ?>
                                        <small class="text-danger">(<?= $log->gagal ?> Error)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($log->error_details) && $log->error_details != '[]'): ?>
                                        <button class="btn btn-outline-danger btn-sm" onclick="showError(<?= $log->id ?>)">
                                            Lihat Detail Error
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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

<div class="modal modal-blur fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Error Baris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="error-content">
                <div class="text-center">Memuat data...</div>
            </div>
        </div>
    </div>
</div>

<script>
    function showError(id) {
        const modal = new bootstrap.Modal(document.getElementById('modal-error'));
        modal.show();

        // Ambil detail error via AJAX ke controller detail
        fetch('<?= base_url('/wilayah/log-detil/') ?>' + id)
            .then(response => response.text())
            .then(html => {
                document.getElementById('error-content').innerHTML = html;
            });
    }
</script>
<?= $this->endSection() ?>