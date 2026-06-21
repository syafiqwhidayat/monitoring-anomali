<?= $this->extend('layout/template'); ?>
<?= $this->section('content') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container-xl">

    <div class="row g-3 align-items-center mb-4">
        <div class="col-md-6">
            <form action="<?= base_url('se/monitoring-ub') ?>" method="get" id="filterForm" class="d-flex gap-2">
                <select name="wilayah" class="form-select w-50" onchange="document.getElementById('filterForm').submit()">
                    <option value="1300" <?= $selected_wilayah == '1300' ? 'selected' : '' ?>>-- Seluruh Sumatera Barat --</option>
                    <?php foreach ($kab_kota as $kode => $nama): ?>
                        <option value="<?= $kode ?>" <?= $selected_wilayah == $kode ? 'selected' : '' ?>><?= $kode ?> - <?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="date" name="tanggal" value="<?= esc($filter_tanggal) ?>" class="form-control w-40" onchange="document.getElementById('filterForm').submit()">
            </form>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-upload">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                    <path d="M7 9l5 -5l5 5" />
                    <path d="M12 4l0 12" />
                </svg>
                Upload Progres Fasih
            </button>
        </div>
    </div>

    <!-- KARTU INDIKATOR UTAMA -->
    <?php
    $total = $cards['total'] ?? 0;
    $getPercent = function ($val, $tot) {
        return $tot > 0 ? round(($val / $tot) * 100, 2) : 0;
    };
    ?>
    <div class="row row-cards mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Open</span>
                        <span class="cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Jumlah Assigment / Prelist yang belum didata sama sekali oleh petugas lapangan.">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0"><?= number_format($cards['open'] ?? 0) ?></div>
                    <div class="text-muted small"><?= $getPercent($cards['open'] ?? 0, $total) ?>% dari total</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Draft</span>
                        <span class="cursor-pointer text-warning" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Jumlah Assigment disimpan sementara oleh PPL atau responden, Assigment Rejected oleh PML">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0 text-yellow"><?= number_format($cards['draft'] ?? 0) ?></div>
                    <div class="text-muted small"><?= $getPercent($cards['draft'] ?? 0, $total) ?>% dari total</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Submitted</span>
                        <span class="cursor-pointer text-blue" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Jumlah Assigment Submitted oleh PPL, Assigment Submitted oleh Responden, Revoked oleh pengawas, Rejected oleh Admin">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0 text-blue"><?= number_format($cards['submitted'] ?? 0) ?></div>
                    <div class="text-muted small"><?= $getPercent($cards['submitted'] ?? 0, $total) ?>% dari total</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Approved Pengawas</span>
                        <span class="cursor-pointer text-success" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Jumlah Assigment Approved oleh PML">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0 text-green"><?= number_format($cards['approved'] ?? 0) ?></div>
                    <div class="text-muted small"><strong><?= $getPercent($cards['approved'] ?? 0, $total) ?>%</strong> dari total</div>
                </div>
            </div>
        </div>
    </div>
    <!-- KARTU KEBERADAAN UTAMA -->
    <?php
    $total = $cards_iden['total'] ?? 0;
    $getPercent = function ($val, $tot) {
        return $tot > 0 ? round(($val / $tot) * 100, 2) : 0;
    };
    ?>
    <div class="row row-cards mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Ditemukan</span>
                        <span class="cursor-pointer text-green" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Usaha ditemukan oleh petugas UB">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0 text-green"><?= number_format($cards_iden['ditemuukan'] ?? 0) ?></div>
                    <div class="text-muted small"><?= $getPercent($cards_iden['ditemuukan'] ?? 0, $total) ?>% dari total</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Tidak Ditemukan</span>
                        <span class="cursor-pointer text-warning" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Usaha tidak ditemukan diwilayah kerja petugas UB">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0 text-yellow"><?= number_format($cards_iden['tak_ditemukan'] ?? 0) ?></div>
                    <div class="text-muted small"><?= $getPercent($cards_iden['tak_ditemukan'] ?? 0, $total) ?>% dari total</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Ganda</span>
                        <span class="cursor-pointer text-blue" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Usaha ditemukan ganda di wilayah kerja petugas UB">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0 text-blue"><?= number_format($cards_iden['ganda'] ?? 0) ?></div>
                    <div class="text-muted small"><?= $getPercent($cards_iden['ganda'] ?? 0, $total) ?>% dari total</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
                        <span>Tutup</span>
                        <span class="cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Usaha terkonfirmasi sudah tutup oleh petugas UB">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                    </div>
                    <div class="h1 mb-0"><?= number_format($cards_iden['tutup'] ?? 0) ?></div>
                    <div class="text-muted small"><strong><?= $getPercent($cards_iden['tutup'] ?? 0, $total) ?>%</strong> dari total</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards mb-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h3 class="card-title text-center text-secondary mb-3">Progres UB Berdasarkan Status</h3>
                    <div style="max-height: 220px;" class="d-flex justify-content-center">
                        <canvas id="pieStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h3 class="card-title text-center text-secondary mb-3">Progres UB Berdasarkan Keberadaan</h3>
                    <div style="max-height: 220px;" class="d-flex justify-content-center">
                        <canvas id="pieIdenStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title text-secondary">Tren Progres Lapangan Kumulatif (Historis Harian)</h3>
                    <div style="height: 220px;">
                        <canvas id="lineHistoris"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-secondary">
                        <?= $selected_wilayah === '1300' ? 'Beban Tugas Progres Kontribusi Wilayah Kabupaten/Kota' : 'Rincian Beban Kerja Menurut Tim Penanggung Jawab Lapangan' ?>
                    </h3>
                    <div style="height: 550px; min-height: 500px;">
                        <canvas id="barWilayah"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Rincian Progress Kerja Usaha Besar</h3>
            <span class="badge bg-blue-lt">Total: <?= $pager_info['total_rows'] ?> Usaha</span>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter table-striped card-table text-nowrap mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>SLS ID</th>
                        <th>Nama Usaha</th>
                        <th>Alamat & Email</th>
                        <th>Status Fasih</th>
                        <th>Tim PJ (Text Input)</th>
                        <th>Keberadaan</th>
                        <th>Last Modified Fasih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ditemukan records data lapangan.</td>
                        </tr>
                        <?php else:
                        // Hitung Nomor Urut Berdasarkan Halaman Saat Ini
                        $no = (($pager_info['current_page'] - 1) * $pager_info['per_page']) + 1;
                        foreach ($assignments as $row):
                        ?>
                            <tr>
                                <td class="text-muted small"><?= $no++ ?></td>
                                <td class="font-monospace text-secondary small">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span><?= esc($row['id_sls']) ?></span>

                                        <?php if (!empty($row['sample_id']) && !empty($row['id_sls'])): ?>
                                            <a href="https://fasih-sm.bps.go.id/app/assignment/<?= esc($row['sample_id']) ?>/<?= esc($row['id_sls']) ?>"
                                                target="_blank"
                                                class="btn btn-icon btn-sm btn-ghost-primary"
                                                title="Buka Data di Fasih SM"
                                                data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
                                                    <path d="M11 13l9 -9"></path>
                                                    <path d="M15 4h5v5"></path>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="fw-bold text-dark"><?= esc($row['nama_usaha']) ?></td>
                                <td>
                                    <div class="small text-muted"><?= esc($row['alamat_usaha']) ?></div>
                                    <div class="extra-small font-monospace text-azure"><?= esc($row['email_usaha'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <?php
                                    $rawStatus = strtolower(trim($row['status']));
                                    $displayStatus = strtoupper($rawStatus); // Default teks yang ditampilkan sesuai database
                                    $badge = 'bg-secondary-lt'; // Default jika tidak masuk kategori manapun

                                    // 1. Logika Pengelompokan Berdasarkan Aturan Baru Anda
                                    if ($rawStatus === 'open') {
                                        $badge = 'border text-muted'; // Atau kosong '' sesuai desain lama Anda
                                        $displayStatus = 'OPEN';
                                    }
                                    // Khusus rejected_by_pengawas silakan posisikan prioritasnya (di sini saya set ke SUBMITTED / DRAFT)
                                    elseif ($rawStatus === 'rejected_by_pengawas') {
                                        $badge = 'bg-orange-lt text-orange'; // Warna oranye transparan Tabler untuk pembeda riwayat reject
                                        $displayStatus = 'REJECTED BY PENGAWAS';
                                    }
                                    // Cek kata depan atau kecocokan grup DRAFT
                                    elseif ($rawStatus === 'draft') {
                                        $badge = 'bg-blue-lt';
                                        $displayStatus = 'DRAFT';
                                    }
                                    // Cek kata depan untuk rumpun SUBMITTED
                                    elseif (str_starts_with($rawStatus, 'submitted_')) {
                                        $badge = 'bg-success-lt';
                                        $displayStatus = str_replace('_', ' ', $displayStatus); // Mengubah SUBMITTED_BY_PENCACAH -> SUBMITTED BY PENCACAH
                                    }
                                    // Cek kata depan untuk rumpun APPROVED, REVOKED, atau REJECTED KAB
                                    elseif (
                                        str_starts_with($rawStatus, 'approved_') ||
                                        str_starts_with($rawStatus, 'revoked_') ||
                                        str_starts_with($rawStatus, 'rejected_by_admin')
                                    ) {
                                        $badge = 'bg-warning-lt';
                                        $displayStatus = str_replace('_', ' ', $displayStatus);
                                    }
                                    ?>
                                    <span class="badge <?= $badge ?> fw-bold w-100 py-1" style="font-size: 0.7rem; white-space: normal;">
                                        <?= esc($displayStatus) ?>
                                    </span>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                        value="<?= esc($row['tim_pj']) ?>"
                                        placeholder="Ketik nama PJ..."
                                        onblur="updatePj('<?= $row['sample_id'] ?>', this.value)">
                                </td>
                                <td>
                                    <?php
                                    $btnClass = 'btn-outline-secondary';
                                    $textKeberadaan = '- Belum Teridentifikasi -';

                                    if ($row['keberadaan'] === 'ditemukan') {
                                        $btnClass = 'btn-success';
                                        $textKeberadaan = 'Ditemukan';
                                    } elseif ($row['keberadaan'] === 'tidak ditemukan') {
                                        $btnClass = 'btn-danger';
                                        $textKeberadaan = 'Tidak Ditemukan';
                                    } elseif ($row['keberadaan'] === 'tutup') {
                                        $btnClass = 'btn-dark';
                                        $textKeberadaan = 'Tutup';
                                    } elseif ($row['keberadaan'] === 'ganda') {
                                        $btnClass = 'btn-warning text-dark';
                                        $textKeberadaan = 'Ganda';
                                    }
                                    ?>

                                    <button type="button"
                                        id="btn-keberadaan-<?= $row['sample_id'] ?>"
                                        class="btn btn-sm <?= $btnClass ?> w-100 fw-bold d-flex align-items-center justify-content-center gap-1"
                                        onclick="openModalKeberadaan('<?= $row['sample_id'] ?>', '<?= esc($row['nama_usaha']) ?>', '<?= $row['keberadaan'] ?? '' ?>')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z"></path>
                                            <path d="M16 5l3 3"></path>
                                            <path d="M9 7.07a7 7 0 0 0 -1 13.93a7 7 0 0 0 6.929 -6"></path>
                                        </svg>
                                        <span><?= $textKeberadaan ?></span>
                                    </button>
                                </td>
                                <td class="small text-muted">
                                    <?= !empty($row['fasih_modified_at']) ? date('d-M-Y H:i', strtotime($row['fasih_modified_at'])) . ' WIB' : '-' ?>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager_info['total_pages'] > 1): ?>
            <div class="card-footer d-flex align-items-center justify-content-between bg-white py-3">
                <p class="m-0 text-muted">
                    Menampilkan <span><?= (($pager_info['current_page'] - 1) * $pager_info['per_page']) + 1 ?></span>
                    hingga <span><?= min($pager_info['current_page'] * $pager_info['per_page'], $pager_info['total_rows']) ?></span>
                    dari <span><?= $pager_info['total_rows'] ?></span> entri data
                </p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item <?= $pager_info['current_page'] <= 1 ? 'disabled' : '' ?>">
                        <a class="page-line page-link" href="<?= base_url('se/monitoring-ub?wilayah=' . $selected_wilayah . '&tanggal=' . $filter_tanggal . '&page=' . ($pager_info['current_page'] - 1)) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M15 6l-6 6l6 6" />
                            </svg> prev
                        </a>
                    </li>

                    <?php
                    $startPage = max(1, $pager_info['current_page'] - 2);
                    $endPage   = min($pager_info['total_pages'], $pager_info['current_page'] + 2);
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <li class="page-item <?= $i === $pager_info['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="<?= base_url('se/monitoring-ub?wilayah=' . $selected_wilayah . '&tanggal=' . $filter_tanggal . '&page=' . $i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $pager_info['current_page'] >= $pager_info['total_pages'] ? 'disabled' : '' ?>">
                        <a class="page-line page-link" href="<?= base_url('se/monitoring-ub?wilayah=' . $selected_wilayah . '&tanggal=' . $filter_tanggal . '&page=' . ($pager_info['current_page'] + 1)) ?>">
                            next <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M9 6l6 6l-6 6" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="<?= base_url('se/monitoring-ub/upload') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Unggah Master Data Progres Fasih</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pilih File Hasil Ekspor (*.csv)</label>
                    <input type="file" name="file_fasih" class="form-control" accept=".csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Mulai Proses Sinkronisasi</button>
            </div>
        </form>
    </div>
</div>

<div class="modal modal-blur fade" id="modalKeberadaan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light py-2">
                <h5 class="modal-title fw-bold text-dark">Update Keberadaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="mb-3">
                    <label class="form-label small text-muted mb-1">Nama Usaha:</label>
                    <div id="modalNamaUsaha" class="fw-bold text-dark text-wrap">-</div>
                </div>

                <input type="hidden" id="modalSampleId">

                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="radio-keberadaan" value="" class="form-selectgroup-input">
                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                            <span class="me-2">⚪</span>
                            <span>Belum Teridentifikasi</span>
                        </span>
                    </label>
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="radio-keberadaan" value="ditemukan" class="form-selectgroup-input">
                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                            <span class="me-2">🟢</span>
                            <span class="text-success fw-bold">Ditemukan</span>
                        </span>
                    </label>
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="radio-keberadaan" value="tidak ditemukan" class="form-selectgroup-input">
                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                            <span class="me-2">🔴</span>
                            <span class="text-danger fw-bold">Tidak Ditemukan</span>
                        </span>
                    </label>
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="radio-keberadaan" value="tutup" class="form-selectgroup-input">
                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                            <span class="me-2">⚫</span>
                            <span class="text-dark fw-bold">Tutup</span>
                        </span>
                    </label>
                    <label class="form-selectgroup-item flex-fill">
                        <input type="radio" name="radio-keberadaan" value="ganda" class="form-selectgroup-input">
                        <span class="form-selectgroup-label d-flex align-items-center p-2">
                            <span class="me-2">🟡</span>
                            <span class="text-warning fw-bold">Ganda</span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanKeberadaan" class="btn btn-primary fw-bold" onclick="submitUpdateKeberadaan()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1060;">
    <div id="toastKeberadaan" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fw-bold" id="toastMessage">
                Status berhasil diperbarui!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    // --- Ambil Variabel JSON Injeksi Dari Controller CI4 ---
    const pieRawData = <?= $pie_json ?>;
    const pieIdenRawData = <?= $pieIden_json ?>;
    const barRawData = <?= $bar_json ?>;
    const lineRawData = <?= $line_json ?>;
    console.log(barRawData);

    const globalColors = ['#EE8911', '#94C11F', '#0369A1', '#B3B3B3'];

    // 1. Rendering Pie Chart
    new Chart(document.getElementById('pieStatus'), {
        type: 'pie',
        data: {
            labels: ['APPROVED', 'SUBMITED', 'DRAFT', 'OPEN'],
            datasets: [{
                data: pieRawData,
                backgroundColor: ['#94C11F', '#0369A1', '#EE8911', '#B3B3B3']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 4. Rendering PieIden Chart
    new Chart(document.getElementById('pieIdenStatus'), {
        type: 'pie',
        data: {
            labels: ['DITEMUKAN', 'TAK DITEMUKAN', 'GANDA', 'TUTUP', 'BELUM'],
            datasets: [{
                data: pieIdenRawData,
                backgroundColor: ['#94C11F', '#EE8911', '#0369A1', '#272727', '#B3B3B3']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 2. Rendering Bar Chart Stacked / Grouped
    new Chart(document.getElementById('barWilayah'), {
        type: 'bar',
        data: barRawData,
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true, // Mengaktifkan fitur tumpukan akumulasi (Stack)
                    beginAtZero: true
                },
                y: {
                    stacked: true, // Mengaktifkan fitur tumpukan akumulasi (Stack)
                    ticks: {
                        autoSkip: false,
                        style: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // 3. Rendering Line Chart Historis Snapshot
    new Chart(document.getElementById('lineHistoris'), {
        type: 'line',
        data: {
            labels: lineRawData.labels,
            datasets: [{
                    label: 'APPROVED',
                    data: lineRawData.approved,
                    borderColor: '#94C11F',
                    backgroundColor: '#94C11F',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'SUBMITED',
                    data: lineRawData.submited,
                    borderColor: '#0369A1',
                    backgroundColor: '#0369A1',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'DRAFT',
                    data: lineRawData.draft,
                    borderColor: '#EE8911',
                    backgroundColor: '#EE8911',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'OPEN',
                    data: lineRawData.open,
                    borderColor: '#B3B3B3',
                    backgroundColor: '#B3B3B3',
                    tension: 0.1,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 4. Fungsi Ajax Update Tim PJ Lapangan Tanpa Reload Halaman
    function updatePj(sampleId, value) {
        const formData = new FormData();
        formData.append('sample_id', sampleId);
        formData.append('tim_pj', value);

        fetch('<?= base_url("se/monitoring-ub/updateTimPj") ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') alert('Gagal menyimpan nama Tim PJ');
            })
            .catch(error => console.error('Error:', error));
    }

    // 5. fungsi untuk update keberadaan
    let bootstrapModalKeberadaan = null;
    let toastElement = null;
    let bootstrapToast = null;

    document.addEventListener("DOMContentLoaded", function() {
        bootstrapModalKeberadaan = new bootstrap.Modal(document.getElementById('modalKeberadaan'));
        toastElement = document.getElementById('toastKeberadaan');
        bootstrapToast = new bootstrap.Toast(toastElement, {
            delay: 2000
        }); // Autohide otomatis dalam 2 detik
    });

    function openModalKeberadaan(sampleId, namaUsaha, currentStatus) {
        document.getElementById('modalSampleId').value = sampleId;
        document.getElementById('modalNamaUsaha').innerText = namaUsaha;

        let radios = document.getElementsByName('radio-keberadaan');
        radios.forEach(radio => {
            radio.checked = (radio.value === currentStatus);
        });

        bootstrapModalKeberadaan.show();
    }

    function submitUpdateKeberadaan() {
        let sampleId = document.getElementById('modalSampleId').value;
        let selectedRadio = document.querySelector('input[name="radio-keberadaan"]:checked');
        let valueKeberadaan = selectedRadio ? selectedRadio.value : "";

        // 1. Ambil elemen tombol simpan & simpan teks aslinya
        let btnSimpan = document.getElementById('btnSimpanKeberadaan');
        let originalBtnText = btnSimpan.innerHTML; // Menyimpan teks "Simpan"

        // 2. Ubah tombol menjadi mode Loading (Disabled + Spinner khas Tabler/Bootstrap)
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...`;

        let formData = new FormData();
        formData.append('sample_id', sampleId);
        formData.append('keberadaan', valueKeberadaan);

        fetch('<?= base_url("se/monitoring-ub/update-keberadaan") ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // 3. Kembalikan kondisi tombol setelah mendapat respons
                btnSimpan.disabled = false;
                btnSimpan.innerHTML = originalBtnText;

                if (data.status === 200) {
                    bootstrapModalKeberadaan.hide();

                    // PROSES UPDATE STATUS TOMBOL DI TABEL SECARA REAL-TIME
                    let targetButton = document.getElementById('btn-keberadaan-' + sampleId);
                    if (targetButton) {
                        targetButton.className = "btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 ";
                        let textSpan = targetButton.querySelector('span');

                        if (valueKeberadaan === 'ditemukan') {
                            targetButton.classList.add('btn-success');
                            textSpan.innerText = 'Ditemukan';
                        } else if (valueKeberadaan === 'tidak ditemukan') {
                            targetButton.classList.add('btn-danger');
                            textSpan.innerText = 'Tidak Ditemukan';
                        } else if (valueKeberadaan === 'tutup') {
                            targetButton.classList.add('btn-dark');
                            textSpan.innerText = 'Tutup';
                        } else if (valueKeberadaan === 'ganda') {
                            targetButton.classList.add('btn-warning', 'text-dark');
                            textSpan.innerText = 'Ganda';
                        } else {
                            targetButton.classList.add('btn-outline-secondary');
                            textSpan.innerText = '- Belum Teridentifikasi -';
                        }

                        targetButton.setAttribute('onclick', `openModalKeberadaan('${sampleId}', '${document.getElementById('modalNamaUsaha').innerText.replace(/'/g, "\\'")}', '${valueKeberadaan}')`);
                    }

                    showToast('bg-success', '🎉 ' + data.message);

                } else {
                    showToast('bg-danger', '⚠️ Gagal: ' + data.message);
                }
            })
            .catch(error => {
                // 4. Jika terjadi error jaringan, pastikan tombol juga kembali normal
                btnSimpan.disabled = false;
                btnSimpan.innerHTML = originalBtnText;

                console.error('Error:', error);
                showToast('bg-danger', '❌ Terjadi gangguan jaringan.');
            });
    }

    // Fungsi bantu untuk memicu Toast warna-warni dinamis
    function showToast(bgClass, message) {
        document.getElementById('toastMessage').innerText = message;

        // Bersihkan sisa class background sebelumnya jika ada
        toastElement.classList.remove('bg-success', 'bg-danger', 'bg-warning');
        toastElement.classList.add(bgClass);

        bootstrapToast.show();
    }
</script>

<?= $this->endSection() ?>