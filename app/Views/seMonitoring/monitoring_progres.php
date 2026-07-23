<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- TAMBAHKAN BARIS CDN CHART.JS INI -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- HEADER & FILTER -->
<div class="page-header d-print-none my-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-sm-auto text-center text-sm-start">
                <h2 class="page-title justify-content-center justify-content-sm-start">Monitoring Progres Kegiatan SE2026</h2>
                <div class="text-muted small mt-1">Data Posisi: <strong class="text-primary"><?= $targetTanggal ?></strong></div>
            </div>

            <div class="col-12 col-sm-auto ms-sm-auto w-100 w-sm-auto">
                <form method="GET" action="" id="filterForm">
                    <select name="kabupaten" class="form-select w-100"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="SUMBAR" <?= $selectedKab == 'SUMBAR' ? 'selected' : '' ?>>-- SELURUH PROVINSI --</option>
                        <option value="1301" <?= $selectedKab == '1301' ? 'selected' : '' ?>>[1301] Kepulauan Mentawai</option>
                        <option value="1302" <?= $selectedKab == '1302' ? 'selected' : '' ?>>[1302] Pesisir Selatan</option>
                        <option value="1303" <?= $selectedKab == '1303' ? 'selected' : '' ?>>[1303] Solok</option>
                        <option value="1304" <?= $selectedKab == '1304' ? 'selected' : '' ?>>[1304] Sijunjung</option>
                        <option value="1305" <?= $selectedKab == '1305' ? 'selected' : '' ?>>[1305] Tanah Datar</option>
                        <option value="1306" <?= $selectedKab == '1306' ? 'selected' : '' ?>>[1306] Padang Pariaman</option>
                        <option value="1307" <?= $selectedKab == '1307' ? 'selected' : '' ?>>[1307] Agam</option>
                        <option value="1308" <?= $selectedKab == '1308' ? 'selected' : '' ?>>[1308] Lima Puluh Kota</option>
                        <option value="1309" <?= $selectedKab == '1309' ? 'selected' : '' ?>>[1309] Pasaman</option>
                        <option value="1310" <?= $selectedKab == '1310' ? 'selected' : '' ?>>[1310] Solok Selatan</option>
                        <option value="1311" <?= $selectedKab == '1311' ? 'selected' : '' ?>>[1311] Dharmasraya</option>
                        <option value="1312" <?= $selectedKab == '1312' ? 'selected' : '' ?>>[1312] Pasaman Barat</option>
                        <option value="1371" <?= $selectedKab == '1371' ? 'selected' : '' ?>>[1371] Kota Padang</option>
                        <option value="1372" <?= $selectedKab == '1372' ? 'selected' : '' ?>>[1372] Kota Solok</option>
                        <option value="1373" <?= $selectedKab == '1373' ? 'selected' : '' ?>>[1373] Kota Sawahlunto</option>
                        <option value="1374" <?= $selectedKab == '1374' ? 'selected' : '' ?>>[1374] Kota Padang Panjang</option>
                        <option value="1375" <?= $selectedKab == '1375' ? 'selected' : '' ?>>[1375] Kota Bukittinggi</option>
                        <option value="1376" <?= $selectedKab == '1376' ? 'selected' : '' ?>>[1376] Kota Payakumbuh</option>
                        <option value="1377" <?= $selectedKab == '1377' ? 'selected' : '' ?>>[1377] Kota Pariaman</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT BODY -->
<div class="page-body">
    <div class="container-xl">

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

        <!-- GRAFIK HISTORIS CHART.JS & PROGRESS BAR -->
        <div class="row row-cards">
            <!-- HISTORIS LINE CHART (CHART.JS) -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="card-title">Tren Historis Progres Harian</h3>
                            <div class="btn-group">
                                <a href="?kabupaten=<?= $selectedKab ?>&offset_hari=<?= $offsetHari + 1 ?>"
                                    class="btn btn-sm btn-outline-secondary <?= ($offsetHari * 7) + 7 >= $totalHari ? 'disabled' : '' ?>">
                                    &larr; Sebelumnya
                                </a>
                                <a href="?kabupaten=<?= $selectedKab ?>&offset_hari=<?= $offsetHari - 1 ?>"
                                    class="btn btn-sm btn-outline-secondary <?= $offsetHari <= 0 ? 'disabled' : '' ?>">
                                    Sesudahnya &rarr;
                                </a>
                            </div>
                        </div>
                        <!-- Wadah Canvas Chart.js -->
                        <div style="height: 320px; position: relative;">
                            <canvas id="chart-historis-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Progres Komposisi Status per Kode <?= $selectedKab === 'SUMBAR' ? 'Kabupaten' : 'Kecamatan' ?></h3>
                    </div>
                    <div class="card-body" style="max-height: 290px; overflow-y: auto;">
                        <?php if (empty($progressRows)): ?>
                            <div class="text-muted text-center py-4">Tidak ada data progres.</div>
                        <?php else: ?>
                            <?php foreach ($progressRows as $row):
                                $tot = $row['total'] > 0 ? $row['total'] : 1;

                                $pctOpen      = round(($row['open'] / $tot) * 100, 1);
                                $pctDraft     = round(($row['draft'] / $tot) * 100, 1);
                                $pctSubmitted = round(($row['submitted'] / $tot) * 100, 1);
                                $pctApproved  = round(($row['approved'] / $tot) * 100, 1);

                                // Menyusun teks ringkasan multisub-status dengan baris baru (HTML)
                                $tooltipContent = "🟢 Approved: " . number_format($row['approved']) . " ({$pctApproved}%)<br>" .
                                    "🔵 Submitted: " . number_format($row['submitted']) . " ({$pctSubmitted}%)<br>" .
                                    "🟡 Draft: " . number_format($row['draft']) . " ({$pctDraft}%)<br>" .
                                    "⚪ Open: " . number_format($row['open']) . " ({$pctOpen}%)";
                            ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Kode: <strong><?= $row['kode_wilayah'] ?></strong></span>
                                        <span class="text-muted small">Target: <strong><?= number_format($row['total']) ?></strong> Assignment</span>
                                    </div>

                                    <div class="progress progress-sm cursor-pointer"
                                        data-bs-toggle="tooltip"
                                        data-bs-html="true"
                                        data-bs-placement="top"
                                        title="<?= $tooltipContent ?>">

                                        <?php if ($row['approved'] > 0): ?>
                                            <div class="progress-bar bg-success" style="width: <?= $pctApproved ?>%"></div>
                                        <?php endif; ?>

                                        <?php if ($row['submitted'] > 0): ?>
                                            <div class="progress-bar bg-blue" style="width: <?= $pctSubmitted ?>%"></div>
                                        <?php endif; ?>

                                        <?php if ($row['draft'] > 0): ?>
                                            <div class="progress-bar bg-warning" style="width: <?= $pctDraft ?>%"></div>
                                        <?php endif; ?>

                                        <?php if ($row['open'] > 0): ?>
                                            <div class="progress-bar bg-secondary" style="width: <?= $pctOpen ?>%"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                    </div>

                    <div class="d-flex card-footer justify-content-between text-muted small mt-3 pt-2 border-top">
                        <div><span class="badge bg-success me-1"></span> Appv</div>
                        <div><span class="badge bg-blue me-1"></span> Subm</div>
                        <div><span class="badge bg-warning me-1"></span> Drft</div>
                        <div><span class="badge bg-secondary me-1"></span> Open</div>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row row-cards mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Grafik Kinerja Harian (Selesai/Approved Baru vs Kemarin)</h3>
                        <div id="chart-performance-container" style="height: 250px;">
                            <canvas id="chart-performance-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-sm bg-primary-lt text-primary d-flex flex-column justify-content-between" style="min-height: 290px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <div class="text-uppercase font-weight-bold tracking-wider mb-2" style="font-size: 0.75rem;">
                            Proyeksi Selesai Pendataan
                        </div>
                        <div class="h1 mb-3 font-weight-bolder" id="proyeksi-tanggal">Menghitung...</div>
                        <p class="text-muted small px-3" id="proyeksi-detail">
                            Memproses rata-rata performa 7 hari terakhir...
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <span class="badge bg-primary text-white" id="proyeksi-speed">0 dokumen/hari</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards mt-2">
            <div class="col-md-6">
                <div class="card card-md h-100">
                    <div class="card-status-top bg-success"></div>
                    <div class="card-header py-3">
                        <h3 class="card-title">🏆 Top 10 Petugas Lapangan (PPL)</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($topPetugas as $index => $p):
                            $medal = match ($index) {
                                0 => '🥇',
                                1 => '🥈',
                                2 => '🥉',
                                default => '⭐'
                            };
                            $bgRank = match ($index) {
                                0 => 'bg-emerald-lt',
                                1 => 'bg-azure-lt',
                                2 => 'bg-orange-lt',
                                default => ''
                            };
                        ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between py-2 px-3 <?= $bgRank ?>">
                                <div class="d-flex align-items-center me-2" style="min-width: 0;">
                                    <span class="fs-2 me-2" style="line-height: 1; flex-shrink: 0;"><?= $medal ?></span>
                                    <div style="min-width: 0;">
                                        <div class="text-reset fw-bold text-truncate" title="<?= esc($p['nama_petugas']) ?>">
                                            <?= esc($p['nama_petugas']) ?>
                                        </div>
                                        <div class="text-muted small text-truncate">
                                            📍 Wilayah: <span class="badge bg-light text-dark font-monospace px-1 py-0"><?= esc($p['wilayah'] ?? '-') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column flex-sm-row gap-1 align-items-end align-items-sm-center" style="flex-shrink: 0;">
                                    <span class="badge bg-green text-green-fg px-2 py-1" style="font-size: 0.75rem; min-width: 75px;">
                                        Appv: <?= number_format($p['approved']) ?>
                                    </span>
                                    <span class="badge bg-blue-lt px-2 py-1" style="font-size: 0.75rem; min-width: 75px;">
                                        Subm: <?= number_format($p['submitted']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-md h-100">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-header py-3">
                        <h3 class="card-title">⚠️ 10 Terbawah Progres Petugas</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($bottomPetugas as $index => $p): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between py-2 px-3">
                                <div class="d-flex align-items-center me-2" style="min-width: 0;">
                                    <span class="text-danger me-3 fw-bold fs-3 text-center" style="width: 25px; flex-shrink: 0;">#<?= $index + 1 ?></span>
                                    <div style="min-width: 0;">
                                        <div class="text-reset fw-semibold text-truncate" title="<?= esc($p['nama_petugas']) ?>">
                                            <?= esc($p['nama_petugas']) ?>
                                        </div>
                                        <div class="text-muted small text-truncate">
                                            📍 Wilayah: <span class="badge bg-light text-dark font-monospace px-1 py-0"><?= esc($p['wilayah'] ?? '-') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end d-flex flex-column flex-sm-row gap-1 align-items-end align-items-sm-center" style="flex-shrink: 0;">
                                    <span class="badge bg-secondary text-secondary-fg px-2 py-1" style="font-size: 0.75rem; min-width: 75px;">
                                        Appv: <?= number_format($p['approved']) ?>
                                    </span>
                                    <span class="badge bg-orange-lt px-2 py-1" style="font-size: 0.75rem; min-width: 75px;">
                                        Subm: <?= number_format($p['submitted']) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row Tambahan: Top 5 & Bottom 5 Progres Harian -->
        <div class="row row-cards mt-3">
            <!-- Top 5 Progres Harian -->
            <div class="col-md-6">
                <div class="card card-md h-100">
                    <div class="card-status-top bg-teal"></div>
                    <div class="card-header py-3">
                        <h3 class="card-title">⚡ Top 5 Progres Harian Petugas</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($topProgresPetugas)): ?>
                            <div class="list-group-item text-muted text-center py-3">Belum ada data progres harian</div>
                        <?php else: ?>
                            <?php foreach ($topProgresPetugas as $index => $p): ?>
                                <div class="list-group-item d-flex align-items-center justify-content-between py-2 px-3">
                                    <div class="d-flex align-items-center me-2" style="min-width: 0;">
                                        <span class="text-teal me-3 fw-bold fs-3 text-center" style="width: 25px; flex-shrink: 0;">#<?= $index + 1 ?></span>
                                        <div style="min-width: 0;">
                                            <div class="text-reset fw-bold text-truncate" title="<?= esc($p['nama_petugas']) ?>">
                                                <?= esc($p['nama_petugas']) ?>
                                            </div>
                                            <div class="text-muted small text-truncate">
                                                📍 Wilayah: <span class="badge bg-light text-dark font-monospace px-1 py-0"><?= esc($p['wilayah'] ?? '-') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end d-flex flex-column flex-sm-row gap-1 align-items-end align-items-sm-center" style="flex-shrink: 0;">
                                        <span class="badge bg-teal-lt px-2 py-1" style="font-size: 0.75rem; min-width: 85px;">
                                            Δ Appv: <?= number_format($p['approved_delta']) ?>
                                        </span>
                                        <span class="badge bg-purple-lt px-2 py-1" style="font-size: 0.75rem; min-width: 85px;">
                                            Δ Subm: <?= number_format($p['submitted_delta']) ?>
                                        </span>
                                        <span class="badge bg-purple-lt px-2 py-1" style="font-size: 0.75rem; min-width: 85px;">
                                            Δ Rjct: <?= number_format($p['rejected_delta']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Bottom 5 Progres Harian -->
            <div class="col-md-6">
                <div class="card card-md h-100">
                    <div class="card-status-top bg-warning"></div>
                    <div class="card-header py-3">
                        <h3 class="card-title">💤 5 Terbawah Progres Harian (Stagnan)</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if (empty($bottomProgresPetugas)): ?>
                            <div class="list-group-item text-muted text-center py-3">Belum ada data progres harian</div>
                        <?php else: ?>
                            <?php foreach ($bottomProgresPetugas as $index => $p): ?>
                                <div class="list-group-item d-flex align-items-center justify-content-between py-2 px-3">
                                    <div class="d-flex align-items-center me-2" style="min-width: 0;">
                                        <span class="text-warning me-3 fw-bold fs-3 text-center" style="width: 25px; flex-shrink: 0;">#<?= $index + 1 ?></span>
                                        <div style="min-width: 0;">
                                            <div class="text-reset fw-semibold text-truncate" title="<?= esc($p['nama_petugas']) ?>">
                                                <?= esc($p['nama_petugas']) ?>
                                            </div>
                                            <div class="text-muted small text-truncate">
                                                📍 Wilayah: <span class="badge bg-light text-dark font-monospace px-1 py-0"><?= esc($p['wilayah'] ?? '-') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end d-flex flex-column flex-sm-row gap-1 align-items-end align-items-sm-center" style="flex-shrink: 0;">
                                        <span class="badge bg-light text-secondary px-2 py-1" style="font-size: 0.75rem; min-width: 85px;">
                                            Δ Appv: <?= number_format($p['approved_delta']) ?>
                                        </span>
                                        <span class="badge bg-light text-secondary px-2 py-1" style="font-size: 0.75rem; min-width: 85px;">
                                            Δ Subm: <?= number_format($p['submitted_delta']) ?>
                                        </span>
                                        <span class="badge bg-light text-secondary px-2 py-1" style="font-size: 0.75rem; min-width: 85px;">
                                            Δ Rjct: <?= number_format($p['rejected_delta']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📊 Pohon Kinerja Struktural (Koseka &rarr; PML &rarr; PPL)</h3>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="accordion-koseka-root">
                            <?php
                            // Fungsi pembantu kecil untuk memformat warna delta di blade/view PHP
                            $renderPhpDelta = function ($val) {
                                $num = (int)$val;
                                if ($num > 0) return '<span class="text-success ms-1" style="font-size: 10px; font-weight: 600;">(+' . number_format($num) . ')</span>';
                                if ($num < 0) return '<span class="text-danger ms-1" style="font-size: 10px; font-weight: 600;">(' . number_format($num) . ')</span>';
                                return '<span class="text-muted ms-1" style="font-size: 10px; opacity: 0.5;">(0)</span>';
                            };
                            ?>
                            <?php if (!empty($kosekaData)): ?>
                                <?php foreach ($kosekaData as $k): ?>
                                    <div class="accordion-item border mb-2 rounded shadow-sm">
                                        <h2 class="accordion-header" id="heading-kos-<?= $k['id_koseka'] ?>">
                                            <button class="accordion-button collapsed bg-white text-dark py-2 px-3"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-kos-<?= $k['id_koseka'] ?>"
                                                onclick="loadPmlData('<?= $k['id_koseka'] ?>')"
                                                style="box-shadow: none;">

                                                <!-- Wrapper Utama Konten Header Koseka -->
                                                <div class="row align-items-center w-100 g-2 pe-2" style="font-size: 12px; margin: 0;">

                                                    <!-- Sisi Kiri: Nama Koseka -->
                                                    <div class="col-12 col-md-4 p-0 text-truncate font-weight-medium">
                                                        👔 Koseka: <strong class="text-dark"><?= esc($k['nama_koseka']) ?></strong>
                                                    </div>

                                                    <!-- Sisi Kanan: Baris Status Fleksibel Berjejer -->
                                                    <div class="col-12 col-md-8 p-0 d-flex flex-wrap align-items-center justify-content-md-end text-muted gap-2 gap-md-3">

                                                        <!-- Target Standalone -->
                                                        <div class="me-1">
                                                            <span class="text-muted-mini small" style="font-size: 10px;">Tgt:</span>
                                                            <strong class="text-dark"><?= number_format($k['total']) ?></strong>
                                                        </div>

                                                        <!-- Open Status -->
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Open:</span>
                                                            <span class="font-weight-medium text-secondary"><?= number_format($k['open']) ?></span>
                                                            <?= $renderPhpDelta($k['open_delta']) ?>
                                                        </div>

                                                        <!-- Draft Status -->
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Draft:</span>
                                                            <span class="font-weight-medium text-warning"><?= number_format($k['draft']) ?></span>
                                                            <?= $renderPhpDelta($k['draft_delta']) ?>
                                                        </div>

                                                        <!-- Submitted Status -->
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Subm:</span>
                                                            <span class="font-weight-medium text-blue"><?= number_format($k['submitted']) ?></span>
                                                            <?= $renderPhpDelta($k['submitted_delta']) ?>
                                                        </div>

                                                        <!-- Approved Status -->
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Appv:</span>
                                                            <span class="font-weight-medium text-success"><?= number_format($k['approved']) ?></span>
                                                            <?= $renderPhpDelta($k['approved_delta']) ?>
                                                        </div>

                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse-kos-<?= $k['id_koseka'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordion-koseka-root">
                                            <div class="accordion-body bg-light-lt p-2 ps-4" id="body-koseka-<?= $k['id_koseka'] ?>">
                                                <div class="text-muted text-center py-2 small"><span class="spinner-border spinner-border-sm me-1"></span> Menarik data PML...</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-important alert-warning text-center py-3" role="alert">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24 24H0z" fill="none" />
                                            <path d="M12 9v4" />
                                            <path d="M12 17h.01" />
                                            <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                                        </svg>
                                        <span class="fw-semibold">Tidak Ada Data</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DETAIL SUB-SLS (AJAX PAGINATION) -->
        <div class="row row-cards mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Rincian Data Progres Per Sub-SLS (<span id="total-sls-count">0</span> Data)</h3>
                        <a href="<?= base_url('se/monitoring-progres/downloadExcel?kabupaten=' . $selectedKab) ?>" class="btn btn-emerald btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                            Unduh Seluruh Data Excel (.csv)
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Sub-SLS</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Open</th>
                                    <th class="text-center">Draft</th>
                                    <th class="text-center">Submitted</th>
                                    <th class="text-center">Approved</th>
                                    <th class="text-center">Rejected</th>
                                    <th class="text-center">Revoked</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-ajax">
                                <tr>
                                    <td colspan="8" class="text-center py-4">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- NAVIGASI PAGINATION JS -->
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <p class="m-0 text-muted small" id="pagination-info">Menampilkan halaman 0 dari 0</p>
                        <ul class="pagination m-0 ms-auto" id="pagination-nav-js"></ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<!-- BLOCK JAVASCRIPT ENGINE -->
<script>
    const rawChartData = <?= $chartData ?>;
    let currentPage = 1;
    const selectedKab = "<?= $selectedKab ?>";
    const formatNum = (num) => new Intl.NumberFormat('id-ID').format(num);

    document.addEventListener("DOMContentLoaded", function() {
        const displayLabels = rawChartData.categories.slice(1);

        const deltaApproved = [];
        const deltaSubmitted = [];
        const deltaOpen = [];

        // 1. Inisialisasi Chart.js untuk Line Chart Historis harian
        if (rawChartData && rawChartData.categories) {

            for (let i = 1; i < rawChartData.categories.length; i++) {
                // Pengurangan: Hari ini (i) - Kemarin (i-1)
                deltaApproved.push(rawChartData.approved[i] - rawChartData.approved[i - 1]);
                deltaSubmitted.push(rawChartData.submitted[i] - rawChartData.submitted[i - 1]);
                deltaOpen.push(rawChartData.open[i] - rawChartData.open[i - 1]);
            }

            const ctx = document.getElementById('chart-historis-canvas').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: rawChartData.categories,
                    datasets: [{
                            label: 'Open',
                            data: rawChartData.open,
                            borderColor: '#6c757d',
                            backgroundColor: '#6c757d',
                            tension: 0.2,
                            fill: false
                        },
                        {
                            label: 'Draft',
                            data: rawChartData.draft,
                            borderColor: '#f59f00',
                            backgroundColor: '#f59f00',
                            tension: 0.2,
                            fill: false
                        },
                        {
                            label: 'Submitted',
                            data: rawChartData.submitted,
                            borderColor: '#206bc4',
                            backgroundColor: '#206bc4',
                            tension: 0.2,
                            fill: false
                        },
                        {
                            label: 'Approved',
                            data: rawChartData.approved,
                            borderColor: '#2fb344',
                            backgroundColor: '#2fb344',
                            tension: 0.2,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            // grid: {
                            //     color: 'rgba(0, 0, 0, 0.05)',
                            //     borderDash: [4, 4]
                            // }
                            grid: {
                                color: (context) => {
                                    // Optimasi Visual: Berikan garis tebal abu-abu gelap khusus untuk angka 0 (baseline)
                                    if (context.tick.value === 0) {
                                        return 'rgba(0, 0, 0, 0.3)';
                                    }
                                    return 'rgba(0, 0, 0, 0.05)';
                                },
                                borderDash: (context) => {
                                    if (context.tick.value === 0) return [0]; // Garis lurus tanpa putus di angka 0
                                    return [4, 4]; // Garis putus-putus untuk grid lainnya
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Render Grafik Kinerja menggunakan LINE CHART
        // console.log(deltaSubmitted);
        const ctxPerf = document.getElementById('chart-performance-canvas').getContext('2d');
        new Chart(ctxPerf, {
            type: 'line',
            data: {
                labels: displayLabels,
                datasets: [{
                        label: 'Δ Open (Baru)',
                        data: deltaOpen,
                        borderColor: '#6c757d', // Abu
                        backgroundColor: '#6c757d',
                        tension: 0.2,
                        fill: false,
                        // borderWidth: 3,
                        // pointRadius: 4,
                        // pointHoverRadius: 6
                    },
                    {
                        label: 'Δ Submitted (Baru)',
                        data: deltaSubmitted,
                        borderColor: '#206bc4', // Biru
                        backgroundColor: '#206bc4',
                        tension: 0.2,
                        fill: false,
                        // borderWidth: 3,
                        // pointRadius: 4,
                        // pointHoverRadius: 6
                    },
                    {
                        label: 'Δ Approved (Baru)',
                        data: deltaApproved,
                        borderColor: '#2fb344', // Hijau sukses
                        backgroundColor: '#2fb344', // Transparan area fill (opsional)
                        tension: 0.2, // Membuat garis agak melengkung halus
                        fill: false, // Ubah jadi true jika ingin ada bayangan di bawah garis
                        // borderWidth: 3,
                        // pointRadius: 4,
                        // pointHoverRadius: 6
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        // Mengizinkan batas bawah otomatis menyesuaikan jika ada nilai negatif
                        beginAtZero: false,
                        grid: {
                            color: (context) => {
                                // Optimasi Visual: Berikan garis tebal abu-abu gelap khusus untuk angka 0 (baseline)
                                if (context.tick.value === 0) {
                                    return 'rgba(0, 0, 0, 0.3)';
                                }
                                return 'rgba(0, 0, 0, 0.05)';
                            },
                            borderDash: (context) => {
                                if (context.tick.value === 0) return [0]; // Garis lurus tanpa putus di angka 0
                                return [4, 4]; // Garis putus-putus untuk grid lainnya
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // --- LOGIKA PROYEKSI ESTIMASI SELESAI ---
        const sisaOpenTerakhir = rawChartData.open[rawChartData.open.length - 1];
        const sisaOpen2Terakhir = rawChartData.open[rawChartData.open.length - 2];
        const totalPenurunanOpen = sisaOpen2Terakhir - sisaOpenTerakhir;
        const tanggalTerakhir = new Date(rawChartData.categories[rawChartData.categories.length - 1] + " 2026");
        const tanggal2Terakhir = new Date(rawChartData.categories[rawChartData.categories.length - 2] + " 2026");
        const selisihMilidetik = tanggalTerakhir - tanggal2Terakhir
        const jumlahHariPenurunan = Math.round(selisihMilidetik / (1000 * 60 * 60 * 24))
        const rataRataPenurunanPerHari = Math.floor(totalPenurunanOpen / jumlahHariPenurunan);
        // console.log(jumlahHariPenurunan);

        const proyeksiTglEl = document.getElementById('proyeksi-tanggal');
        const proyeksiDetailEl = document.getElementById('proyeksi-detail');
        const proyeksiSpeedEl = document.getElementById('proyeksi-speed');

        if (proyeksiTglEl && proyeksiDetailEl && proyeksiSpeedEl) {
            if (sisaOpenTerakhir === 0) {
                proyeksiTglEl.innerText = "SELESAI";
                proyeksiDetailEl.innerText = "Seluruh dokumen target telah selesai diproses.";
                proyeksiSpeedEl.innerText = "Progress 100%";
            } else if (rataRataPenurunanPerHari <= 0) {
                proyeksiTglEl.innerText = "Stagnan / Melambat";
                proyeksiTglEl.className = "h2 mb-3 font-weight-bolder text-danger";
                proyeksiDetailEl.innerText = "Tidak ada penurunan jumlah status 'Open' dalam 7 hari terakhir.";
                proyeksiSpeedEl.innerText = `Sisa Open: ${formatNum(sisaOpenTerakhir)} dokumen`;
            } else {
                const sisaHari = Math.ceil(sisaOpenTerakhir / rataRataPenurunanPerHari);
                const tglTerakhirStr = rawChartData.categories[rawChartData.categories.length - 1];
                const tglProyeksi = new Date(tglTerakhirStr + " 2026");
                // const tglProyeksi = new Date(tglTerakhirStr);
                tglProyeksi.setDate(tglProyeksi.getDate() + sisaHari);

                const tglFormatId = tglProyeksi.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                proyeksiTglEl.innerText = tglFormatId;
                proyeksiDetailEl.innerHTML = `Berdasarkan sisa <strong>${formatNum(sisaOpenTerakhir)}</strong> dokumen bertstatus 'Open', pendataan diperkirakan selesai dalam <strong>${sisaHari} hari</strong> lagi.`;
                proyeksiSpeedEl.innerText = `Speed: ↓ ${formatNum(Math.round(rataRataPenurunanPerHari))} Open / hari`;
            }
        }

        // 2. Jalankan Pemanggilan Data Tabel Awal
        loadTablePage(1);

        // Aktifkan semua komponen tooltip di halaman web
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    // 3. Engine AJAX Pagination Data Sub-SLS
    async function loadTablePage(page) {
        currentPage = page;
        const tbody = document.getElementById('table-body-ajax');
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div> Memuat data...</td></tr>';

        try {
            const response = await fetch(`<?= base_url('se/monitoring-progres/getTableData') ?>?kabupaten=${selectedKab}&page=${page}`);
            const result = await response.json();

            document.getElementById('total-sls-count').innerText = formatNum(result.total_rows);

            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-3">Tidak ada detail data SLS.</td></tr>';
                return;
            }

            let htmlRows = '';
            result.data.forEach(row => {
                htmlRows += `
                    <tr>
                        <td><span class="text-monospace font-weight-bold">${row.id_subsls}</span></td>
                        <td class="text-center">${formatNum(row.total)}</td>
                        <td class="text-center"><span class="badge bg-secondary-lt">${formatNum(row.open)}</span></td>
                        <td class="text-center"><span class="badge bg-warning-lt">${formatNum(row.draft)}</span></td>
                        <td class="text-center"><span class="badge bg-blue-lt">${formatNum(row.submitted)}</span></td>
                        <td class="text-center"><span class="badge bg-success-lt">${formatNum(row.approved)}</span></td>
                        <td class="text-center"><span class="badge bg-danger-lt">${formatNum(row.rejected_by_pengawas)}</span></td>
                        <td class="text-center"><span class="badge bg-purple-lt">${formatNum(row.revoked_by_pengawas)}</span></td>
                    </tr>
                `;
            });
            tbody.innerHTML = htmlRows;

            document.getElementById('pagination-info').innerText = `Halaman ${result.current_page} dari ${result.total_pages}`;
            renderPaginationControls(result.current_page, result.total_pages);

        } catch (error) {
            console.error("Gagal memuat tabel:", error);
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger py-3">Terjadi kesalahan saat memuat data.</td></tr>';
        }
    }

    function renderPaginationControls(current, total) {
        const navContainer = document.getElementById('pagination-nav-js');
        navContainer.innerHTML = '';

        if (total <= 1) return;

        // Button Prev
        navContainer.innerHTML += `
            <li class="page-item ${current === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); if(${current} > 1) loadTablePage(${current - 1})">&larr;</a>
            </li>
        `;

        let startPage = Math.max(1, current - 2);
        let endPage = Math.min(total, current + 2);

        if (startPage > 1) {
            navContainer.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadTablePage(1)">1</a></li>`;
            if (startPage > 2) navContainer.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            navContainer.innerHTML += `
                <li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); loadTablePage(${i})">${i}</a>
                </li>
            `;
        }

        if (endPage < total) {
            if (endPage < total - 1) navContainer.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            navContainer.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadTablePage(${total})">${total}</a></li>`;
        }

        // Button Next
        navContainer.innerHTML += `
            <li class="page-item ${current === total ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); if(${current} < ${total}) loadTablePage(${current + 1})">&rarr;</a>
            </li>
        `;
    }

    // Map untuk melacak apakah data wilayah sub-level sudah pernah ditarik/di-cache sebelumnya
    const loadedKoseka = new Set();
    const loadedPml = new Set();

    async function loadPmlData(idKoseka) {
        if (loadedKoseka.has(idKoseka)) return; // Cegah re-fetch berkali-kali jika akordion ditutup-buka

        const container = document.getElementById(`body-koseka-${idKoseka}`);

        try {
            const response = await fetch(`<?= base_url('se/monitoring-progres/getPmlByKoseka') ?>?id_koseka=${idKoseka}&kd_kab=${selectedKab}`);
            const data = await response.json();

            if (data.length === 0) {
                container.innerHTML = '<div class="text-muted p-2">Tidak ditemukan data PML di bawah Koseka ini.</div>';
                return;
            }

            // Fungsi pembantu pembentuk badge delta inline untuk header
            const inlineDelta = (val) => {
                const num = parseInt(val) || 0;
                if (num > 0) return `<span class="text-success ms-1" style="font-size: 10px; font-weight: 600;">(+${formatNum(num)})</span>`;
                if (num < 0) return `<span class="text-danger ms-1" style="font-size: 10px; font-weight: 600;">(${formatNum(num)})</span>`;
                return `<span class="text-muted ms-1" style="font-size: 10px; opacity: 0.5;">(0)</span>`;
            };

            let html = `<div class="accordion" id="accordion-pml-root-${idKoseka}">`;
            data.forEach(pml => {
                html += `
                    <div class="accordion-item border my-1 rounded shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-white text-dark py-2 px-3" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse-pml-${pml.id_pml}" 
                                    onclick="loadPplData('${pml.id_pml}', '${idKoseka}')"
                                    style="box-shadow: none;">
                                
                                <!-- Wrapper Utama Konten Header -->
                                <div class="row align-items-center w-100 g-2 pe-2" style="font-size: 12px; margin: 0;">
                                    
                                    <!-- Sisi Kiri: Nama PML -->
                                    <div class="col-12 col-md-4 p-0 text-truncate font-weight-medium">
                                        🔍 PML: <strong class="text-dark">${pml.nama_pml}</strong>
                                    </div>
                                    
                                    <!-- Sisi Kanan: Baris Status Fleksibel -->
                                    <div class="col-12 col-md-8 p-0 d-flex flex-wrap align-items-center justify-content-md-end text-muted gap-2 gap-md-3">
                                        
                                        <!-- Target Standalone -->
                                        <div class="me-1">
                                            <span class="text-muted-mini small" style="font-size: 10px;">Tgt:</span>
                                            <strong class="text-dark">${formatNum(pml.total)}</strong>
                                        </div>
                                        
                                        <!-- Open Status -->
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Open:</span>
                                            <span class="font-weight-medium text-secondary">${formatNum(pml.open)}</span>
                                            ${inlineDelta(pml.open_delta)}
                                        </div>
                                        
                                        <!-- Draft Status -->
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Draft:</span>
                                            <span class="font-weight-medium text-warning">${formatNum(pml.draft)}</span>
                                            ${inlineDelta(pml.draft_delta)}
                                        </div>
                                        
                                        <!-- Submitted Status -->
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Subm:</span>
                                            <span class="font-weight-medium text-blue">${formatNum(pml.submitted)}</span>
                                            ${inlineDelta(pml.submitted_delta)}
                                        </div>
                                        
                                        <!-- Approved Status -->
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted-mini small me-1" style="font-size: 10px;">Appv:</span>
                                            <span class="font-weight-medium text-success">${formatNum(pml.approved)}</span>
                                            ${inlineDelta(pml.approved_delta)}
                                        </div>

                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse-pml-${pml.id_pml}" class="accordion-collapse collapse" data-bs-parent="#accordion-pml-root-${idKoseka}">
                            <div class="accordion-body bg-light-lt p-1" id="body-pml-${pml.id_pml}">
                                <div class="text-muted text-center py-2 small"><span class="spinner-border spinner-border-sm me-1"></span> Menarik data PPL...</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
            container.innerHTML = html;
            loadedKoseka.add(idKoseka);

        } catch (error) {
            console.error("Gagal memuat data PML:", error);
            container.innerHTML = '<div class="text-danger p-2">Gagal mengambil data dari server.</div>';
        }
    }

    async function loadPplData(idPml, idKoseka) {
        if (loadedPml.has(idPml)) return;

        const container = document.getElementById(`body-pml-${idPml}`);

        try {
            const response = await fetch(`<?= base_url('se/monitoring-progres/getPplByPml') ?>?id_pml=${idPml}&kd_kab=${selectedKab}`);
            const data = await response.json();

            if (data.length === 0) {
                container.innerHTML = '<div class="text-muted p-2 small">Tidak ada PPL di bawah PML ini.</div>';
                return;
            }

            const fmtDelta = (val) => {
                const num = parseInt(val) || 0;
                if (num > 0) return `<div class="text-success" style="font-size: 10px; font-weight: 600; margin-top: -2px;">+${formatNum(num)}</div>`;
                if (num < 0) return `<div class="text-danger" style="font-size: 10px; font-weight: 600; margin-top: -2px;">${formatNum(num)}</div>`;
                return `<div class="text-muted-mini text-dark" style="font-size: 10px; opacity: 0.5; margin-top: -2px;">0</div>`;
            };

            let html = `
            <div class="p-2 bg-base">
                <div class="table-responsive" style="margin: 0; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-sm table-borderless table-vcenter m-0" style="font-size: 11px; min-width: 320px;">
                        <thead>
                            <tr class="text-muted" style="font-size: 10px; text-transform: uppercase; border-bottom: 1px solid rgba(101,109,119,0.16);">
                                <th class="p-1" style="width: 35%;">🏃 PPL</th>
                                <th class="p-1 text-center" style="width: 13%;">Target</th>
                                <th class="p-1 text-center" style="width: 13%;">Open</th>
                                <th class="p-1 text-center" style="width: 13%;">Draft</th>
                                <th class="p-1 text-center" style="width: 13%;">Subm</th>
                                <th class="p-1 text-center" style="width: 13%;">Appv</th>
                            </tr>
                        </thead>
                        <tbody>;
            `;
            data.forEach(ppl => {
                html += `
                    <tr style="border-bottom: 1px dashed rgba(101,109,119,0.1); vertical-align: top;">
                        <!-- Nama Petugas -->
                        <td class="p-1 font-weight-medium text-dark text-truncate" style="max-width: 110px;">
                            ${ppl.nama_ppl}
                        </td>
                        <!-- Target (Sendirian & Tegas) -->
                        <td class="p-1 text-center font-weight-bold text-azure" style="font-size: 11.5px; padding-top: 3px !important;">
                            ${formatNum(ppl.total)}
                        </td>
                        <!-- Open + Delta di bawahnya -->
                        <td class="p-1 text-center">
                            <span class="font-weight-medium text-dark">${formatNum(ppl.open)}</span>
                            ${fmtDelta(ppl.open_delta)}
                        </td>
                        <!-- Draft + Delta di bawahnya -->
                        <td class="p-1 text-center">
                            <span class="font-weight-medium text-warning">${formatNum(ppl.draft)}</span>
                            ${fmtDelta(ppl.draft_delta)}
                        </td>
                        <!-- Submitted + Delta di bawahnya -->
                        <td class="p-1 text-center">
                            <span class="font-weight-medium text-blue">${formatNum(ppl.submitted)}</span>
                            ${fmtDelta(ppl.submitted_delta)}
                        </td>
                        <!-- Approved + Delta di bawahnya -->
                        <td class="p-1 text-center">
                            <span class="font-weight-medium text-success">${formatNum(ppl.approved)}</span>
                            ${fmtDelta(ppl.approved_delta)}
                        </td>
                    </tr>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
            loadedPml.add(idPml);

        } catch (error) {
            console.error("Gagal memuat data PPL:", error);
            container.innerHTML = '<div class="text-danger p-2 small">Gagal mengambil data PPL.</div>';
        }
    }

    //document.addEventListener("DOMContentLoaded", function() {
    // Aktifkan semua komponen tooltip di halaman web
    //    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    //    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    //        return new bootstrap.Tooltip(tooltipTriggerEl)
    //    });
    //});
</script>
<?= $this->endSection(); ?>