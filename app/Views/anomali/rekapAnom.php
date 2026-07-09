<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4 mt-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item fw-bold" style="color: #fd7e14;">Rekap Seluruh Jawaban Anomali Berdasarkan Assigment.</li>
    </ol>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= $message; ?></div>
    <?php endif; ?>

    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter me-1"></i> Panel Filter Monitoring
        </div>
        <div class="card-body">
            <form method="GET" action="<?= current_url(); ?>" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-bold">Level Anomali</label>
                    <select name="fil-level" class="form-select form-control">
                        <?php foreach ($listLevel as $lvl): ?>
                            <option value="<?= $lvl['id']; ?>" <?= ($filterLevel == $lvl['id']) ? 'selected' : ''; ?>>
                                <?= empty($lvl['id']) ? $lvl['nama'] : 'Level ' . $lvl['id']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Jenis Kode Anomali</label>
                    <select name="fil-kategori" class="form-select form-control">
                        <?php foreach ($listSelKdAnom as $kd): ?>
                            <option value="<?= $kd['id']; ?>" <?= ($filterKategori == $kd['id']) ? 'selected' : ''; ?>>
                                <?= $kd['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Status Konfirmasi</label>
                    <select name="fil-konfirmasi" class="form-select form-control">
                        <option value="">-- Semua Jawaban --</option>
                        <option value="terisi" <?= ($filterKonfirmasi === 'terisi') ? 'selected' : ''; ?>>Hanya yang Sudah Dijawab</option>
                        <option value="kosong" <?= ($filterKonfirmasi === 'kosong') ? 'selected' : ''; ?>>Belum Ada Jawaban</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Status Anomali</label>
                    <select name="fil-status" class="form-select form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif" <?= ($filterStatus === 'aktif') ? 'selected' : ''; ?>>Aktif (Masih Anomali)</option>
                        <option value="clean" <?= ($filterStatus === 'clean') ? 'selected' : ''; ?>>Clean (Selesai/Lolos)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Wilayah Kerja</label>
                    <select name="fil-wilayah" class="form-select form-control" <?= $isKunciWilayah ? 'disabled' : ''; ?>>
                        <option value="">-- Semua Wilayah --</option>
                        <?php foreach ($listWilayah as $wil): ?>
                            <option value="<?= $wil['id']; ?>" <?= ($filterWilayah == $wil['id']) ? 'selected' : ''; ?>>
                                <?= $wil['id']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($isKunciWilayah): ?>
                        <input type="hidden" name="fil-wilayah" value="<?= $filterWilayah; ?>">
                    <?php endif; ?>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-1">
                    <button type="submit" class="btn btn-primary w-50">Filter</button>
                    <a href="<?= current_url(); ?>" class="btn btn-secondary w-50">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <div>
                <i class="fas fa-list-ol me-1"></i> Matriks Jawaban Terkelompok per Assignment
            </div>
            <div>
                <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['export' => 'excel'])); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Download ke Excel
                </a>
                <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['export' => 'template'])); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Download template Konfirmasi
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" width="100%" cellspacing="0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="10%">Kode/Assigment/Roster</th>
                            <th width="10%">Petugas Lapangan</th>
                            <th width="10%">Wilayah Kerja</th>
                            <th width="10%">Kode Anom</th>
                            <th width="10%">Detil Anom</th>
                            <th width="10%">Konfirmasi / Jawaban Lapangan</th>
                            <th width="10%">Status Lap</th>
                            <th width="10%">Status Insert</th>
                            <th width="10%">Tanggal Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listAnom) && count($listAnom) > 0): ?>
                            <?php
                            // Hitung frekuensi kemunculan id_assignment_obj dalam halaman ini untuk hitung ROWSPAN
                            $rowspans = array_count_values(array_column($listAnom, 'id_assignment_obj'));
                            $displayed_assignment = [];

                            foreach ($listAnom as $row):
                                $current_id = $row['id_assignment_obj'];
                                $is_first_row = !in_array($current_id, $displayed_assignment);
                            ?>
                                <tr>
                                    <?php if ($is_first_row):
                                        $displayed_assignment[] = $current_id;
                                    ?>
                                        <td rowspan="<?= $rowspans[$current_id]; ?>" class="bg-light fw-bold text-center" style="word-break: break-word; min-width: 140px;">
                                            <?php
                                            $kd_full = $row['kd_assigment'];
                                            // Jika panjang karakter lebih dari 15, kita potong dan beri tanda titik-titik (...)
                                            $kd_tampil = (strlen($kd_full) > 15) ? substr($kd_full, 0, 15) . '...' : $kd_full;
                                            ?>
                                            <span class="badge bg-secondary text-white mb-1" title="<?= esc($kd_full); ?>" style="cursor: help;">
                                                ID: <?= esc($kd_tampil); ?>
                                            </span>
                                            <br>
                                            <span style="font-size: 13px;">
                                                <?php if (!empty($row['nm_art'])): ?>
                                                    <?= esc($row['nm_krt'] ?? '-'); ?> / <span class="text-primary fw-normal"><?= esc($row['nm_art']); ?></span>
                                                <?php else: ?>
                                                    <?= esc($row['nm_krt'] ?? $row['nm_nrt'] ?? '-'); ?>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td rowspan="<?= $rowspans[$current_id]; ?>" class="bg-light" style="font-size: 12px;">
                                            <div class="mb-1"><strong>PPL:</strong> <span class="text-muted"><?= $row['nama_ppl'] ?? '-'; ?></span></div>
                                            <div><strong>PML:</strong> <span class="text-muted"><?= $row['nama_pml'] ?? '-'; ?></span></div>
                                        </td>
                                        <td rowspan="<?= $rowspans[$current_id]; ?>" class="text-center" style="font-family: monospace;">
                                            <div class="d-flex flex-column align-items-center gap-1">

                                                <span>`<?= esc($row['id_wilayah']); ?></span>

                                                <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($row['kd_krt']); ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="badge bg-blue-lt text-blue px-1-5 py-0-5 rounded text-decoration-none"
                                                    style="font-size: 0.65rem; max-width: 80px; font-family: var(--bs-font-sans-serif);"
                                                    title="Buka Fasih untuk wilayah <?= esc($row['kd_krt']); ?>">
                                                    ke Fasih-SM
                                                </a>

                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <td class="text-center"><span class="badge bg-primary text-white"><?= $row['kode_anomali']; ?></span></td>
                                    <td><small><?= $row['detil_anomali']; ?></small></td>
                                    <td class="<?= empty($row['konfirmasi']) ? 'text-muted bg-light text-center' : 'text-primary'; ?>" style="font-style: italic; font-size: 13px;">
                                        <?= !empty($row['konfirmasi']) ? esc($row['konfirmasi']) : '<small>[ Belum Ada Jawaban ]</small>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $row['is_lap'] === '1' ? 'bg-success text-white' : 'bg-secondary text-white'; ?>">
                                            <?= $row['is_lap'] === '1' ? 'Kond Lap' : 'Perbaikan'; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $row['is_insert'] === '1' ? 'bg-warning text-white' : 'bg-success text-white'; ?>">
                                            <?= $row['is_insert'] === '1' ? 'Aktif' : 'Clean'; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?= $row['date_updated']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">Data anomali berdasarkan kriteria filter tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($pager)): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?= $pager; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>