<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="page-header d-print-none mb-2">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Log Import Anomali</h2>
            </div>
            <div class="col-auto ms-auto">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUpload">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <polyline points="7 9 12 4 17 9" />
                        <line x1="12" y1="4" x2="12" y2="16" />
                    </svg>
                    Upload Anomali
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
                            <th>Level Anomali</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Progress (Berhasil/Total)</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td class="text-secondary"><?= $log['created_at'] ?></td>
                                <td><?= $log['nama_file_awal'] ?></td>
                                <td class="text-primary"><?= $log['wilayah']; ?></td>
                                <td class="text-primary"><?= $log['email']; ?></td>
                                <td>
                                    <?php if ($log['status'] == 'selesai'): ?>
                                        <span class="badge">Selesai</span>
                                    <?php elseif ($log['status'] == 'proses'): ?>
                                        <span class="badge">Sedang Diproses</span>
                                    <?php elseif ($log['status'] == 'pending'): ?>
                                        <span class="badge">Antre</span>
                                    <?php else: ?>
                                        <span class="badge">Gagal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= $log['berhasil'] ?></strong> / <?= $log['total_baris'] ?> Baris
                                    <?php if ($log['gagal'] > 0): ?>
                                        <small class="text-danger">(<?= $log['gagal'] ?> Error)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($log['error_details']) && $log['error_details'] != '[]'): ?>
                                        <button class="btn btn-outline-danger btn-sm" onclick="showError(<?= $log['id'] ?>)">
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

<div class="modal modal-blur fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
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

<div class="modal modal-blur fade" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formUploadAnomali" action="<?= base_url('/manajemen-anomali/store/anomali') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Anomali</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Data Anomali</label>
                        <select class="form-select" id="selectJenisAnomali" name="jenis_anomali">
                            <option value="anomali">Anomali Wilayah / Kelompok</option>
                            <option value="anomali_individu">Anomali Individu (Pertahankan Konfirmasi)</option>
                            <option value="anomali_individu_forced">Anomali Individu (Paksa Timpa Konfirmasi)</option>
                        </select>
                    </div>

                    <p class="mb-2">Template Upload Anomali :</p>
                    <a id="btnDownloadTemplate" href="<?= base_url('/manajemen-anomali/template/anomali'); ?>" class="btn btn-outline-primary w-100 mb-3">
                        <i class="bi bi-download"></i> Unduh Template Excel (.xlsx)
                    </a>

                    <div class="hr-text"></div>

                    <div class="mb-3">
                        <label class="form-label">Pilih File (Excel/CSV)</label>
                        <input type="file" name="file_anomali" class="form-control" required>
                        <small class="form-hint mt-2">Pastikan struktur kolom file Excel Anda sesuai dengan template yang dipilih.</small>
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

<script>
    document.getElementById('selectJenisAnomali').addEventListener('change', function() {
        const jenis = this.value;
        const urlTemplate = '<?= base_url('manajemen-anomali/template') ?>/' + jenis;
        const urlStore = '<?= base_url('manajemen-anomali/store') ?>/' + jenis;

        // Ubah action form submit
        document.getElementById('btnDownloadTemplate').setAttribute('href', urlTemplate);
        document.getElementById('formUploadAnomali').setAttribute('action', urlStore);
    });

    function showError(id) {
        const modal = new bootstrap.Modal(document.getElementById('modal-error'));
        modal.show();
        console.log('ini dijalankan');

        // Ambil detail error via AJAX ke controller detail
        fetch('<?= base_url('/manajemen-anomali/log-detil/') ?>' + id)
            .then(response => response.text())
            .then(html => {
                document.getElementById('error-content').innerHTML = html;
            });
    }
</script>
<?= $this->endSection() ?>