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

    <!-- Alert Notifikasi Flashdata -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter me-1"></i> Filter Wilayah & Level Evaluasi
        </div>
        <div class="card-body">
            <form method="GET" action="<?= current_url(); ?>" class="row g-3">
                <!-- Filter Level Anomali -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Level Anomali</label>
                    <select name="fil-level" class="form-select form-control">
                        <?php foreach ($listLevel as $lvl): ?>
                            <option value="<?= $lvl['id']; ?>" <?= ($filterLevel == $lvl['id']) ? 'selected' : ''; ?>>
                                <?= empty($lvl['id']) ? $lvl['nama'] : 'Level ' . $lvl['id']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Jenis / Kode Anomali -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Jenis Anomali</label>
                    <select name="fil-kategori" class="form-select form-control">
                        <?php foreach ($listSelKdAnom as $anom): ?>
                            <option value="<?= $anom['id']; ?>" <?= ($filterKategori == $anom['id']) ? 'selected' : ''; ?>>
                                <?= empty($anom['id']) ? $anom['nama'] : $anom['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Flag Prioritas -->
                <div class="col-md-2">
                    <label class="form-label fw-bold">Flag Prioritas</label>
                    <select name="fil-flag" class="form-select form-control">
                        <?php foreach ($listSelFlag as $flg): ?>
                            <option value="<?= $flg['value']; ?>" <?= ($filterFlag == $flg['value']) ? 'selected' : ''; ?>>
                                <?= empty($flg['value']) ? $flg['nama'] : 'Flag ' . $flg['value']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Wilayah Kerja -->
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

                <!-- Filter Konfirmasi Sejak Tanggal -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Konfirmasi setelah Tanggal</label>
                    <input type="date" name="fil-tgl-mulai" class="form-control" value="<?= esc($filterTglMulai); ?>">
                </div>

                <!-- Filter Konsidi Lapangan -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Kondisi Lapangan?</label>
                    <select name="fil-lap" class="form-select form-control">
                        <option value="0" <?= ($filterIsLap === '0') ? 'selected' : ''; ?>>
                            Perbaikan Fasih
                        </option>
                        <option value="1" <?= ($filterIsLap === '1') ? 'selected' : ''; ?>>
                            Kondisi Lapangan
                        </option>
                    </select>
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
                            <th width="3%">No</th>
                            <th width="10%">Wilayah</th>
                            <th>Identitas Objek (KRT/ART)</th>
                            <th width="10%">Petugas (PPL / PML)</th>
                            <th width="10%">Kode Anom</th>
                            <th>Deskripsi Aturan / Rules</th>
                            <th width="25%" class="bg-warning text-dark">Konfirmasi Petugas Sebelumnya</th>
                            <th width="10%">Waktu Konfirmasi</th>
                            <th width="6%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listAnom) && count($listAnom) > 0): ?>
                            <?php
                            $page = isset($_GET['page_group_assignment']) ? (int)$_GET['page_group_assignment'] : 1;
                            $no   = 1 + (($page - 1) * 15);
                            foreach ($listAnom as $row):
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center" style="font-family: monospace; font-size: 13px;">
                                        `<?= $row['nm_sls']; ?>
                                        `<?= $row['id_wilayah']; ?>
                                        <?php if (session('isOrganik')): ?>
                                            <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($row['kd_krt']); ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="badge bg-blue-lt text-blue px-1-5 py-0-5 rounded text-decoration-none"
                                                style="font-size: 0.65rem; max-width: 80px; font-family: var(--bs-font-sans-serif);"
                                                title="Buka Fasih untuk wilayah <?= esc($row['kd_krt']); ?>">
                                                ke Fasih-SM
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- <small class="text-muted d-block" style="font-size: 10px;">ID: <?= $row['kd_assigment']; ?></small> -->
                                        <strong><?= $row['nm_krt'] ?? $row['nm_art'] ?? $row['nm_nrt'] ?? '-'; ?></strong>/
                                        <strong><?= $row['nm_art'] ?? $row['nm_art'] ?? $row['nm_art'] ?? '-'; ?></strong>
                                    </td>
                                    <td>
                                        <small class="d-block"><strong>PPL:</strong> <?= esc($row['nm_ppl'] ?? '-'); ?></small>
                                        <small class="d-block text-muted"><strong>PML:</strong> <?= esc($row['nm_pml'] ?? '-'); ?></small>
                                    </td>
                                    <td class="text-center fw-bold text-danger"><?= $row['kode_anomali']; ?></td>
                                    <td><small><?= $row['detil_anomali']; ?></small></td>
                                    <td class="bg-light text-green" style="font-style: italic; font-weight: 500; font-size: 13px;">
                                        <?= esc($row['konfirmasi']); ?>
                                    </td>
                                    <td class="text-center">
                                        <small><?= !empty($row['date_konfirmasi']) ? date('d/m/Y H:i', strtotime($row['date_konfirmasi'])) : '-'; ?></small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-sm <?= ($row['is_lap'] == 1) ? 'btn-outline-blue' : 'btn-outline-success'; ?> btn-toggle-lap"
                                            data-id="<?= $row['id_anomali']; ?>"
                                            title="Klik untuk mengubah status Kondisi Lapangan">
                                            <i class="fas fa-sync-alt me-1"></i>
                                            <?= ($row['is_lap'] == 1) ? 'Perbaikan Fasih' : 'Kondisi Lap'; ?>
                                        </button>
                                    </td>
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
                <div class="d-flex justify-content-center mt-3">
                    <?= $pager; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.btn-toggle-lap');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const idAnomali = this.getAttribute('data-id');
                const btn = this;

                // Efek visual instant: Disable tombol & beri indikator loading
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';

                const formData = new FormData();
                formData.append('id_anomali', idAnomali);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                fetch('<?= base_url('anomali/ubah-is-lap'); ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Refresh halaman instant (pagination & filter tetap terjaga)
                            window.location.reload();
                        } else {
                            alert('Gagal: ' + data.message);
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Coba Lagi';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Coba Lagi';
                    });
            });
        });
    });
</script>
<?= $this->endSection(); ?>