<?= $this->extend('layout/template'); ?>
<?= $this->section('content') ?>

<div class="container-xl">
    <div class="page-header d-print-none mb-4">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-dark fs-2">
                    Monitoring Detail Assignment Wilayah: <span class="text-primary ms-1"><?= $idWilayah ?></span>
                </h2>
                <div class="text-muted small mt-1">Diurutkan berdasarkan waktu pemutakhiran aplikasi eksternal terbaru</div>
            </div>
        </div>
    </div>

    <!-- filter -->
    <div class="card mb-3 mt-3 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <form action="<?php base_url('se/ngibar') ?>" method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kabupaten</label>
                    <select name="sel_wilayah" class="form-select">
                        <option value="">-</option>
                        <?php foreach ($list_wilayah ?? [] as $wil): ?>
                            <option value="<?= $wil['id_wilayah']; ?>" <?= ($idWilayah == $wil['id_wilayah']) ? 'selected' : ''; ?>><?= $wil['id_wilayah']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Dokumen</label>
                    <select name="sel_status" class="form-select">
                        <option value="">-</option>
                        <?php foreach ($list_status ??  [] as $id => $nama): ?>
                            <option value="<?= $id; ?>" <?= ($sel_status == $id) ? 'selected' : ''; ?>><?= ucwords($nama); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="text-muted small fw-bold">Filter Status Dokumen:</div>
                    <div class="nav- Pilgrim bg-light p-1 rounded">
                        <a href="<?= base_url('se/ngibar') ?>" class="btn btn-sm <?= empty($sel_status) ? 'btn-primary' : 'btn-link text-dark' ?>">Semua</a>
                        <a href="<?= base_url('se/ngibar?status=Submited by Responden') ?>" class="btn btn-sm <?= $sel_status === 'Submited by Responden' ? 'btn-success text-white' : 'btn-link text-success' ?>">Submitted</a>
                        <a href="<?= base_url('se/ngibar?status=Draft') ?>" class="btn btn-sm <?= $sel_status === 'Draft' ? 'btn-orange text-white' : 'btn-link text-orange' ?>">Draft</a>
                        <a href="<?= base_url('se/ngibar?status=Rejected by Admin') ?>" class="btn btn-sm <?= $sel_status === 'Rejected by Admin' ? 'btn-danger text-white' : 'btn-link text-danger' ?>">Rejected</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-hover">
                <thead class="bg-light text-muted uppercase small">
                    <tr>
                        <th width="25%">Nama Usaha / Perusahaan</th>
                        <th width="30%">Alamat Lokasi Usaha</th>
                        <th width="20%">Kode Identitas</th>
                        <th width="10%">Status</th>
                        <th width="15%">Terakhir Update App</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-database-off mb-2 text-yellow" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12.983 8.978c3.955 -.182 7.017 -1.446 7.017 -2.978c0 -1.657 -3.582 -3 -8 -3c-1.661 0 -3.204 .19 -4.452 .515m-2.548 1.485c-.643 .434 -1.002 .919 -1.002 1pull-0c0 1.657 3.582 3 8 3" />
                                    <path d="M4 6v6c0 1.58 3.302 2.871 7.405 2.985m4.595 -.485c2.405 -.456 4 -1.442 4 -2.5v-6" />
                                    <path d="M4 12v6c0 1.657 3.582 3 8 3c1.925 0 3.7 -.251 5.107 -.686m2.893 -1.314v-4" />
                                    <path d="M3 3l18 18" />
                                </svg>
                                <div class="fw-bold">Tidak ada rekaman assignment ditemukan</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assignments as $row): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark mb-1"><?= esc($row['nama_usaha']) ?></div>
                                    <div class="text-muted small d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                            <path d="M3 7l9 6l9 -6" />
                                        </svg>
                                        <?= !empty($row['email']) ? esc($row['email']) : '<span class="text-lowercase text-danger font-italic small">tidak ada email</span>' ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary small line-clamp-2"><?= esc($row['alamat_usaha']) ?></span>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm rounded shadow-none" style="max-width: 190px;">
                                        <input type="text" class="form-control bg-light font-monospace text-dark border-0 small py-1" value="<?= esc($row['kode_identitas']) ?>" readonly id="code-<?= $row['id_assignment'] ?>">
                                        <button class="btn btn-white border-0 text-primary px-2" type="button" onclick="copyToClipboard('<?= $row['id_assignment'] ?>', this)" title="Salin Kode">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                                <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    // Mengubah string status menjadi huruf kecil semua agar pengecekan tidak sensitif huruf besar/kecil (case-insensitive)
                                    $currentStatus = strtolower($row['status'] ?? '');
                                    ?>

                                    <?php if (str_contains($currentStatus, 'submitted')): ?>
                                        <span class="badge bg-success-lt fw-bold py-1 px-2">Submitted</span>

                                    <?php elseif (str_contains($currentStatus, 'rejected')): ?>
                                        <span class="badge bg-danger-lt fw-bold py-1 px-2">Rejected</span>

                                    <?php else: ?>
                                        <span class="badge bg-warning-lt fw-bold py-1 px-2">Draft</span>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small fw-bold text-secondary"><?= date('d M Y', strtotime($row['date_updated'])) ?></div>
                                    <div class="text-muted small style-italic"><?= date('H:i', strtotime($row['date_updated'])) ?> WIB</div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    /**
     * Fungsi Pintasan menyalin teks ke clipboard tanpa merusak fokus elemen
     */
    function copyToClipboard(id, btnElement) {
        const inputEl = document.getElementById('code-' + id);
        inputEl.select();
        inputEl.setSelectionRange(0, 99999); // Kompatibilitas Mobile

        navigator.clipboard.writeText(inputEl.value).then(() => {
            // Efek Feedback Berhasil disalin
            const iconOriginal = btnElement.innerHTML;
            btnElement.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check text-success" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>`;
            btnElement.classList.add('bg-success-lt');

            setTimeout(() => {
                btnElement.innerHTML = iconOriginal;
                btnElement.classList.remove('bg-success-lt');
            }, 1200);
        }).catch(err => {
            console.error('Gagal menyalin kode identitas: ', err);
        });
    }
</script>
<?= $this->endSection() ?>