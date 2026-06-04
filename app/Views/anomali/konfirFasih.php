<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4 mt-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item text-orange fw-bold">Daftar Anomali yang sudah dijawab dan bukan kondisi lapangan namun masih muncul sebagai anomali. Kemungkinan belum diperbaiki di FASIH.</li>
    </ol>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= $message; ?></div>
    <?php endif; ?>

    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter me-1"></i> Filter Wilayah & Level Evaluasi
        </div>
        <div class="card-body">
            <form method="GET" action="<?= current_url(); ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Level Anomali</label>
                    <select name="fil-level" class="form-select form-control">
                        <?php foreach ($listLevel as $lvl): ?>
                            <option value="<?= $lvl['id']; ?>" <?= ($filterLevel == $lvl['id']) ? 'selected' : ''; ?>>
                                <?= empty($lvl['id']) ? $lvl['nama'] : 'Level ' . $lvl['id']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Wilayah Kerja</label>
                    <select name="fil-wilayah" class="form-select form-control" <?= $isKunciWilayah ? 'disabled' : ''; ?>>
                        <option value="">-- Semua Wilayah Kerja --</option>
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

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-50">Filter Data</button>
                    <a href="<?= current_url(); ?>" class="btn btn-secondary w-50">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <div>
                <i class="fas fa-exclamation-triangle text-warning me-1"></i> Total Anomali Memerlukan Perhatian Fasih
            </div>
            <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['export' => 'excel'])); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-file-excel me-1"></i> Download ke Excel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="4%">No</th>
                            <th width="10%">Wilayah</th>
                            <th width="10%">Kode Anom</th>
                            <th>Deskripsi Aturan / Rules</th>
                            <th>Identitas Objek (KRT/ART)</th>
                            <th width="25%" class="bg-warning text-dark">Konfirmasi Petugas Sebelumnya</th>
                            <th width="10%">Waktu Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listAnom) && count($listAnom) > 0): ?>
                            <?php
                            $page = isset($_GET['page_group_catatan']) ? (int)$_GET['page_group_catatan'] : 1;
                            $no = 1 + (($page - 1) * 15);
                            foreach ($listAnom as $row):
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center" style="font-family: monospace; font-size: 13px;">`<?= $row['id_wilayah']; ?></td>
                                    <td class="text-center fw-bold text-danger"><?= $row['kode_anomali']; ?></td>
                                    <td><small><?= $row['detil_anomali']; ?></small></td>
                                    <td>
                                        <small class="text-muted d-block" style="font-size: 10px;">ID: <?= $row['kd_assigment']; ?></small>
                                        <strong><?= $row['nm_krt'] ?? $row['nm_art'] ?? $row['nm_nrt'] ?? '-'; ?></strong>
                                    </td>
                                    <td class="bg-light text-danger" style="font-style: italic; font-weight: 500; font-size: 13px;">
                                        <?= esc($row['konfirmasi']); ?>
                                    </td>
                                    <td class="text-center"><small><?= date('d/m H:i', strtotime($row['date_updated'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                    Hebat! Tidak ditemukan anomali yang belum diperbaiki di fasih.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($pager)): ?>
                <div class="d-flex justify-content-end mt-3">
                    <?= $pager->links('group_catatan', 'default_full') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>