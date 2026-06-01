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

    <div class="row row-cards mb-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h3 class="card-title text-center text-secondary mb-3">Realisasi Global Status</h3>
                    <div style="max-height: 220px;" class="d-flex justify-content-center">
                        <canvas id="pieStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
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
            <table class="table table-vcenter table-striped card-table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>SLS ID</th>
                        <th>Nama Usaha</th>
                        <th>Alamat & Email</th>
                        <th>Status Fasih</th>
                        <th>Tim PJ (Text Input)</th>
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
                                <td class="font-monospace text-secondary small"><?= esc($row['id_sls']) ?></td>
                                <td class="fw-bold text-dark"><?= esc($row['nama_usaha']) ?></td>
                                <td>
                                    <div class="small text-muted"><?= esc($row['alamat_usaha']) ?></div>
                                    <div class="extra-small font-monospace text-azure"><?= esc($row['email_usaha'] ?? '-') ?></div>
                                </td>
                                <td>
                                    <?php
                                    $badge = 'bg-secondary-lt';
                                    if ($row['status'] == 'DRAFT') $badge = 'bg-blue-lt';
                                    if ($row['status'] == 'SUBMITED') $badge = 'bg-success-lt';
                                    if ($row['status'] == 'REJECTED') $badge = 'bg-warning-lt';
                                    ?>
                                    <span class="badge <?= $badge ?> fw-bold w-100 py-1"><?= esc($row['status']) ?></span>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                        value="<?= esc($row['tim_pj']) ?>"
                                        placeholder="Ketik nama PJ..."
                                        onblur="updatePj('<?= $row['sample_id'] ?>', this.value)">
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
<script>
    // --- Ambil Variabel JSON Injeksi Dari Controller CI4 ---
    const pieRawData = <?= $pie_json ?>;
    const barRawData = <?= $bar_json ?>;
    const lineRawData = <?= $line_json ?>;
    console.log(pieRawData);

    const globalColors = ['#EE8911', '#94C11F', '#0369A1', '#B3B3B3'];

    // 1. Rendering Pie Chart
    new Chart(document.getElementById('pieStatus'), {
        type: 'pie',
        data: {
            labels: ['DRAFT', 'SUBMITED', 'REJECTED', 'OPEN'],
            datasets: [{
                data: pieRawData,
                backgroundColor: ['#0369A1', '#94C11F', '#EE8911', '#B3B3B3']
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
                    label: 'REJECTED',
                    data: lineRawData.rejected,
                    borderColor: '#EE8911',
                    backgroundColor: '#EE8911',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'SUBMITED',
                    data: lineRawData.submited,
                    borderColor: '#94C11F',
                    backgroundColor: '#94C11F',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'DRAFT',
                    data: lineRawData.draft,
                    borderColor: '#0369A1',
                    backgroundColor: '#0369A1',
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
</script>

<?= $this->endSection() ?>