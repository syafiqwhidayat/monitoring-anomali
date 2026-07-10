<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="page-body">
    <div class="container-xl">

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Filter Data Monitoring</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Level Anomali</label>
                            <select name="level_anomali" class="form-select">
                                <?php foreach ($listLevel as $l): ?>
                                    <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filters['level']) ? 'selected' : ''; ?>>Anomali <?= ($l['id'] == null) ? "Semua" : $l['id']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Wilayah (Kabupaten/Kota)</label>
                            <select name="kd_kab" class="form-select">
                                <option value="">-- Seluruh Wilayah --</option>
                                <?php foreach ($listKabkot as $kab): ?>
                                    <option value="<?= $kab['kd_kab'] ?>" <?= $filters['kab'] == $kab['kd_kab'] ? 'selected' : '' ?>><?= "[" . $kab['kd_kab'] . "] " . $kab['nm_kab'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status Anomali</label>
                            <select name="status_anomali" class="form-select">
                                <option value="">-- Semua Status --</option>
                                <option value="1" <?= $filters['status'] === '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $filters['status'] === '0' ? 'selected' : '' ?>>Clean</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori Anomali</label>
                            <select name="id_kategori_anomali" class="form-select">
                                <option value="">-- Semua Kategori Kegiatan --</option>
                                <?php foreach ($listKategori as $kat): ?>
                                    <option value="<?= $kat['id'] ?>" <?= $filters['kategori'] == $kat['id'] ? 'selected' : '' ?>><?= $kat['kode_anomali'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label">Cari Nama Petugas</label>
                            <input type="text" name="search_petugas" class="form-control" placeholder="Ketik nama Koseka / PML / PPL..." value="<?= esc($filters['search']) ?>">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7a1 1 0 0 1 -1.414 .914l-2 -2a1 1 0 0 1 -.586 -.914v-5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row row-cards mb-3">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-status-top bg-danger"></div>
                    <div class="card-body">
                        <h4 class="card-title text-danger">Top 5 Anomali Koseka</h4>
                        <ol class="list-unstyled list-separated">
                            <?php if (empty($topKoseka)): ?><li><small class="text-muted">Tidak ada data</small></li><?php endif; ?>
                            <?php foreach ($topKoseka as $i => $k): ?>
                                <li class="py-2 d-flex justify-content-between align-items-center">
                                    <span class="text-truncate" style="max-width: 75%;"><?= ($i + 1) . ". " . esc($k['nama_petugas']) ?></span>
                                    <span class="badge bg-red-lt font-weight-bold"><?= $k['total'] ?> Anomali</span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-status-top bg-warning"></div>
                    <div class="card-body">
                        <h4 class="card-title text-warning">Top 5 Anomali PML</h4>
                        <ol class="list-unstyled list-separated">
                            <?php if (empty($topPml)): ?><li><small class="text-muted">Tidak ada data</small></li><?php endif; ?>
                            <?php foreach ($topPml as $i => $p): ?>
                                <li class="py-2 d-flex justify-content-between align-items-center">
                                    <span class="text-truncate" style="max-width: 75%;"><?= ($i + 1) . ". " . esc($p['nama_petugas']) ?></span>
                                    <span class="badge bg-warning-lt font-weight-bold"><?= $p['total'] ?> Anomali</span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-status-top bg-info"></div>
                    <div class="card-body">
                        <h4 class="card-title text-info">Top 5 Anomali PPL</h4>
                        <ol class="list-unstyled list-separated">
                            <?php if (empty($topPpl)): ?><li><small class="text-muted">Tidak ada data</small></li><?php endif; ?>
                            <?php foreach ($topPpl as $i => $pp): ?>
                                <li class="py-2 d-flex justify-content-between align-items-center">
                                    <span class="text-truncate" style="max-width: 75%;"><?= ($i + 1) . ". " . esc($pp['nama_petugas']) ?></span>
                                    <span class="badge bg-info-lt font-weight-bold"><?= $pp['total'] ?> Anomali</span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Matriks Progres Anomali Hierarki Petugas</h3>
                <span class="text-muted small">Menampilkan <?= count($hierarchy) ?> Koseka Record</span>
            </div>
            <div class="card-body p-0">
                <div class="d-flex justify-content-center text-muted small p-3">
                    <div class="me-2"><span class="badge bg-success me-1"></span> Konfrimasi dengan Kondisi Lapangan </div>
                    <div class="me-2"><span class="badge bg-blue me-1"></span> Konfirmasi bukan Kondisi Lapangan </div>
                    <div class="me-2"><span class="badge bg-secondary me-1"></span> Belum Konfirmasi </div>
                </div>
                <div class="accordion accordion-flush" id="accordion-petugas">

                    <?php if (empty($hierarchy)): ?>
                        <div class="text-center p-4 text-muted">Data berdasarkan filter tidak ditemukan.</div>
                    <?php endif; ?>

                    <?php $pIndex = 0;
                    foreach ($hierarchy as $pk => $parent): $pIndex++; ?>
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="heading-p-<?= $pIndex ?>">
                                <button class="accordion-button collapsed px-3 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-p-<?= $pIndex ?>">
                                    <div class="row w-100 align-items-center g-2">
                                        <div class="col-md-4 text-truncate">
                                            <strong class="<?= $parent['is_koseka'] ? 'text-blue' : 'text-purple' ?>">
                                                <?= $parent['is_koseka'] ? '🏠 Koseka: ' : '👔 PML-Root: ' ?><?= esc($parent['nama']) ?>
                                            </strong>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span class="badge bg-dark-lt"><?= $parent['total'] ?> Total</span>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            $pct_lap = $parent['total'] > 0 ? round(($parent['lap'] / $parent['total']) * 100, 1) : 0;
                                            $pct_non = $parent['total'] > 0 ? round(($parent['non_lap'] / $parent['total']) * 100, 1) : 0;
                                            $pct_bel = $parent['total'] > 0 ? round(($parent['belum'] / $parent['total']) * 100, 1) : 0;
                                            $tooltipContent = "Kondisi Lapangan: {$parent['lap']} ({$pct_lap}%)<br>Non-Lapangan: {$parent['non_lap']} ({$pct_non}%)<br>Belum Konfirmasi: {$parent['belum']} ({$pct_bel}%)";
                                            ?>
                                            <div class="progress progress-sm cursor-pointer" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="<?= $tooltipContent ?>">
                                                <div class="progress-bar bg-success" style="width: <?= $pct_lap ?>%"></div>
                                                <div class="progress-bar bg-blue" style="width: <?= $pct_non ?>%"></div>
                                                <div class="progress-bar bg-secondary" style="width: <?= $pct_bel ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-p-<?= $pIndex ?>" class="accordion-collapse collapse" data-bs-parent="#accordion-petugas">
                                <div class="accordion-body bg-light-lt p-0 ps-3">

                                    <div class="accordion accordion-flush" id="sub-accordion-p-<?= $pIndex ?>">
                                        <?php $subIdx = 0;
                                        foreach ($parent['pml'] as $pmlId => $pml): $subIdx++; ?>
                                            <div class="accordion-item border-bottom bg-transparent">
                                                <h3 class="accordion-header" id="heading-sub-<?= $pIndex ?>-<?= $subIdx ?>">
                                                    <button class="accordion-button collapsed py-2 px-3 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-sub-<?= $pIndex ?>-<?= $subIdx ?>">
                                                        <div class="row w-100 align-items-center g-2">
                                                            <div class="col-md-4 text-truncate">
                                                                <span class="text-orange font-weight-medium">👔 PML: <?= esc($pml['nama']) ?></span>
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                                <small class="text-muted"><?= $pml['total'] ?> Anomali</small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <?php
                                                                $pml_lap = $pml['total'] > 0 ? round(($pml['lap'] / $pml['total']) * 100, 1) : 0;
                                                                $pml_non = $pml['total'] > 0 ? round(($pml['non_lap'] / $pml['total']) * 100, 1) : 0;
                                                                $pml_bel = $pml['total'] > 0 ? round(($pml['belum'] / $pml['total']) * 100, 1) : 0;
                                                                $tooltipPml = "Kondisi Lapangan: {$pml['lap']} ({$pml_lap}%)<br>Non-Lapangan: {$pml['non_lap']} ({$pml_non}%)<br>Belum Konfirmasi: {$pml['belum']} ({$pml_bel}%)";
                                                                ?>
                                                                <div class="progress progress-sm cursor-pointer" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="<?= $tooltipPml ?>">
                                                                    <div class="progress-bar bg-success " style="width: <?= $pml_lap ?>%"></div>
                                                                    <div class="progress-bar bg-blue " style="width: <?= $pml_non ?>%"></div>
                                                                    <div class="progress-bar bg-secondary" style="width: <?= $pml_bel ?>%"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h3>
                                                <div id="collapse-sub-<?= $pIndex ?>-<?= $subIdx ?>" class="accordion-collapse collapse" data-bs-parent="#sub-accordion-p-<?= $pIndex ?>">
                                                    <div class="accordion-body bg-white p-2">

                                                        <div class="table-responsive">
                                                            <table class="table table-vcenter card-table table-striped table-sm text-muted">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nama PPL</th>
                                                                        <th class="text-center" style="width: 15%;">Total Anomali</th>
                                                                        <th style="width: 50%;">Progres Anomali</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($pml['ppl'] as $pplId => $ppl): ?>
                                                                        <tr>
                                                                            <td class="text-truncate text-dark font-weight-medium">🏃 PPL: <?= esc($ppl['nama']) ?></td>
                                                                            <td class="text-center font-weight-bold text-dark"><?= $ppl['total'] ?></td>
                                                                            <td>
                                                                                <?php
                                                                                $ppl_lap = $ppl['total'] > 0 ? round(($ppl['lap'] / $ppl['total']) * 100, 1) : 0;
                                                                                $ppl_non = $ppl['total'] > 0 ? round(($ppl['non_lap'] / $ppl['total']) * 100, 1) : 0;
                                                                                $ppl_bel = $ppl['total'] > 0 ? round(($ppl['belum'] / $ppl['total']) * 100, 1) : 0;
                                                                                $tooltipPpl = "Kondisi Lapangan: {$ppl['lap']} ({$ppl_lap}%)<br>Non-Lapangan: {$ppl['non_lap']} ({$ppl_non}%)<br>Belum Konfirmasi: {$ppl['belum']} ({$ppl_bel}%)";
                                                                                ?>
                                                                                <div class="progress progress-sm cursor-pointer" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="<?= $tooltipPpl ?>">
                                                                                    <div class="progress-bar bg-success " style="width: <?= $ppl_lap ?>%"></div>
                                                                                    <div class="progress-bar bg-blue " style="width: <?= $ppl_non ?>%"></div>
                                                                                    <div class="progress-bar bg-secondary" style="width: <?= $ppl_bel ?>%"></div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

            <?php if ($pager['total'] > 1): ?>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <p class="m-0 text-muted">Menampilkan halaman <span><?= $pager['current'] ?></span> dari <span><?= $pager['total'] ?></span> total halaman parent</p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item <?= $pager['current'] <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pager['current'] - 1])) ?>">Sebelumnya</a>
                        </li>
                        <?php for ($x = 1; $x <= $pager['total']; $x++): ?>
                            <li class="page-item <?= $pager['current'] == $x ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $x])) ?>"><?= $x ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $pager['current'] >= $pager['total'] ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pager['current'] + 1])) ?>">Selanjutnya</a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>