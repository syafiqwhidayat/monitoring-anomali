<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Load CDN Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Gunakan Chart.js v3 dan Boxplot v3 (Auto-Register murni) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@sgratzl/chartjs-chart-boxplot@4.4.5/build/index.umd.min.js"></script>

<script>
    console.log("Menggunakan Chart.js versi 3 dengan auto-register Boxplot terpasang aman.");
</script>

<!-- Skrip Fallback Registrasi Aman -->
<script>
    // Memastikan jika auto-register UMD gagal, kita daftarkan manual dari objek window
    try {
        if (typeof Chart !== 'undefined' && window['chartjs-chart-boxplot']) {
            Chart.register(window['chartjs-chart-boxplot'].BoxPlotController, window['chartjs-chart-boxplot'].BoxAndWhiskers);
            console.log("Boxplot berhasil didaftarkan secara manual melalui objek window.");
        } else {
            console.log("Chart.js v3 auto-register terpantau aktif.");
        }
    } catch (e) {
        console.warn("Peringatan registrasi boxplot:", e);
    }
</script>

<div class="container" jenis-konf="anades">
    <!-- Jumbotron/Header Page -->
    <div class="card card-body mb-3">
        <h1><?= esc($title); ?></h1>
    </div>

    <!-- Filter Bagian Atas -->
    <div class="card card-body mb-3">
        <div class="hr-text hr-text-left fs-5 mb-3">Filter Wilayah Kerja</div>
        <div class="mb-3">
            <form action="<?= current_url(); ?>" method="get">
                <div class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label">Wilayah Tugas / Kabupaten</label>
                        <select name="selected-wilayah" class="form-select">
                            <option value="">Semua Wilayah</option>
                            <option value="1311" <?= (request()->getGet('selected-wilayah') == '1311') ? 'selected' : ''; ?>>1311 - Kab. Dharmasraya</option>
                            <option value="1375" <?= (request()->getGet('selected-wilayah') == '1375') ? 'selected' : ''; ?>>1375 - Kota Bukittinggi</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-4 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                            </svg>
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content: List Ringkasan dan Grafik Deskriptif -->
    <div class="card card-body">
        <div class="hr-text hr-text-left fs-5 mb-3">Daftar Analisis Deskriptif Variabel</div>

        <div class="row">
            <div class="col">
                <?php if (empty($dataAnades)): ?>
                    <div class="alert alert-success" role="alert">
                        Tidak ada analisis deskriptif data untuk kegiatan ini.
                    </div>
                <?php else: ?>

                    <div class="accordion" id="accordionAnadesVariabel">
                        <?php foreach ($dataAnades as $v): ?>
                            <div class="accordion-item mb-2 border rounded">

                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnades<?= $v['id']; ?>" aria-expanded="false" aria-controls="collapseAnades<?= $v['id']; ?>">
                                        <div class="d-flex align-items-center justify-content-between w-100 pe-3">
                                            <div>
                                                <span class="badge bg-blue-lt text-primary font-monospace px-2 py-1 me-2 rounded"><?= esc($v['kode_variabel']); ?></span>
                                                <strong class="text-secondary small fw-bold"><?= esc($v['deskripsi']); ?></strong>
                                            </div>
                                            <span class="badge bg-light text-muted border rounded-pill px-2 py-0-5" style="font-size: 0.7rem;">
                                                Wilayah: <?= esc($v['kode_wilayah']); ?>
                                            </span>
                                        </div>
                                    </button>
                                </h2>

                                <div id="collapseAnades<?= $v['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionAnadesVariabel">
                                    <div class="accordion-body bg-white p-3 border-top">

                                        <!-- Tombol Aksi Edit -->
                                        <div class="d-flex justify-content-end mb-3">
                                            <button class="btn btn-sm btn-outline-secondary px-3 rounded-pill btn-edit-anades"
                                                data-id="<?= $v['id']; ?>"
                                                data-kode="<?= esc($v['kode_variabel']); ?>"
                                                data-desc="<?= esc($v['deskripsi']); ?>"
                                                data-anomali="<?= $v['id_kategori_anomali']; ?>">
                                                ⚙️ Ubah Konfigurasi
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <!-- Panel Statistik Ringkasan (Kiri) -->
                                            <div class="col-md-4">
                                                <div class="p-2 border rounded bg-light mb-3">
                                                    <span class="d-block text-uppercase small text-muted fw-bold mb-2">Statistik Summary</span>
                                                    <table class="table table-sm table-bordered bg-white small align-middle mb-0">
                                                        <tr>
                                                            <td>Batas Bawah (Min)</td>
                                                            <td class="fw-bold text-danger text-end"><?= number_format($v['n_batas_bawah'], 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kuartil 1 (Q1)</td>
                                                            <td class="text-end"><?= number_format($v['n_q1'], 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Median</td>
                                                            <td class="text-primary fw-semibold text-end"><?= number_format($v['median'], 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Rata-rata (Mean)</td>
                                                            <td class="text-end"><?= number_format($v['n_rata'], 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kuartil 3 (Q3)</td>
                                                            <td class="text-end"><?= number_format($v['n_q3'], 2, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Batas Atas (Max)</td>
                                                            <td class="fw-bold text-danger text-end"><?= number_format($v['n_batas_atas'], 2, ',', '.'); ?></td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <div class="p-2 border rounded bg-light">
                                                    <span class="d-block text-uppercase small text-muted fw-bold mb-1">Kategori Anomali Terkait:</span>
                                                    <?php if ($v['id_kategori_anomali']): ?>
                                                        <span class="badge bg-danger mb-1">[<?= esc($v['kode_anomali']); ?>]</span>
                                                        <p class="small text-dark fw-medium mb-0"><?= esc($v['detil_anomali']); ?></p>
                                                    <?php else: ?>
                                                        <span class="text-muted fst-italic small">Belum dikaitkan ke sistem anomali.</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Panel Canvas Chart.js (Kanan) -->
                                            <div class="col-md-8">
                                                <!-- Chart 1: Line Chart (Kurva Pola Distribusi Frekuensi) -->
                                                <span class="d-block text-uppercase small text-muted fw-bold mb-1">Kurva Distribusi Frekuensi (n_histogram)</span>
                                                <div style="position: relative; height:160px; width:100%" class="border p-2 rounded bg-white mb-2">
                                                    <canvas id="chart_line_<?= $v['id']; ?>"></canvas>
                                                </div>

                                                <!-- Chart 2: Boxplot Chart -->
                                                <span class="d-block text-uppercase small text-muted fw-bold mb-1">Grafik Sebaran Ringkasan Statistik (Boxplot)</span>
                                                <div style="position: relative; height:120px; width:100%" class="border p-2 rounded bg-white">
                                                    <canvas id="chart_anades_<?= $v['id']; ?>"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- Script Rendering Grafik per baris di dalam loop -->
                            <script>
                                (function() {
                                    // 1. Ambil & Parsing Data Histogram JSON dari Backend
                                    <?php
                                    $histogramData = json_decode($v['n_histogram'], true);
                                    if (!is_array($histogramData)) {
                                        $histogramData = [];
                                    }
                                    $labels = array_keys($histogramData);
                                    $values = array_values($histogramData);
                                    ?>

                                    const histLabels = <?= json_encode($labels); ?>;
                                    const histValues = <?= json_encode($values); ?>;

                                    // 2. Tentukan Batas Maksimum & Minimum Sumbu Y Bersama
                                    // Agar presisi, sumbu Y dikunci berdasarkan rentang nilai statistik riil data Anda
                                    const valMin = <?= floatval($v['n_batas_bawah']); ?>;
                                    const valMax = <?= floatval($v['n_batas_atas']); ?>;
                                    const padding = (valMax - valMin) * 0.05 || 1;

                                    const sharedYMin = valMin - padding;
                                    const sharedYMax = valMax + padding;

                                    // ==========================================
                                    // LINE CHART (KURVA HISTOGRAM FREKUENSI)
                                    // ==========================================
                                    const lineCanvas = document.getElementById("chart_line_<?= $v['id']; ?>");
                                    if (lineCanvas) {
                                        const oldLine = Chart.getChart(lineCanvas);
                                        if (oldLine) oldLine.destroy();

                                        new Chart(lineCanvas.getContext('2d'), {
                                            type: 'line',
                                            data: {
                                                labels: histLabels, // Sumbu X berisi teks kategori kelas (misal: "500k-1m")
                                                datasets: [{
                                                    label: 'Jumlah Frekuensi Sampel',
                                                    data: histValues, // Tinggi kurva menunjukkan jumlah frekuensi
                                                    borderColor: '#206bc4',
                                                    backgroundColor: 'rgba(32, 107, 196, 0.1)',
                                                    borderWidth: 2,
                                                    tension: 0.4, // Membuat kurva menjadi melengkung halus (smooth curve)
                                                    fill: true
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                plugins: {
                                                    legend: {
                                                        display: false
                                                    }
                                                },
                                                scales: {
                                                    x: {
                                                        grid: {
                                                            display: false
                                                        }
                                                    },
                                                    y: {
                                                        // Frekuensi memiliki skala tersendiri, namun grid disamakan
                                                        beginAtZero: true,
                                                        grid: {
                                                            color: '#f5f5f5'
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Frekuensi',
                                                            font: {
                                                                size: 10
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }

                                    // ==========================================
                                    // BOXPLOT CHART (STATISTIK DESKRIPTIF SUMMARY)
                                    // ==========================================
                                    const boxplotCanvas = document.getElementById("chart_anades_<?= $v['id']; ?>");
                                    if (boxplotCanvas) {
                                        const oldBox = Chart.getChart(boxplotCanvas);
                                        if (oldBox) oldBox.destroy();

                                        new Chart(boxplotCanvas.getContext('2d'), {
                                            type: 'boxplot',
                                            data: {
                                                labels: ['<?= esc($v['kode_variabel']); ?>'],
                                                datasets: [{
                                                    label: 'Rentang Nilai',
                                                    backgroundColor: 'rgba(42, 157, 143, 0.3)',
                                                    borderColor: '#2a9d8f',
                                                    borderWidth: 2,
                                                    itemRadius: 0,
                                                    data: [{
                                                        // Data tetap sama, Chart.js otomatis memetakan ke sumbu X karena indexAxis: 'y'
                                                        min: valMin,
                                                        q1: <?= floatval($v['n_q1']); ?>,
                                                        median: <?= floatval($v['median']); ?>,
                                                        q3: <?= floatval($v['n_q3']); ?>,
                                                        max: valMax,
                                                        mean: <?= floatval($v['n_rata']); ?>
                                                    }]
                                                }]
                                            },
                                            options: {
                                                indexAxis: 'y', // <-- MEMBALIK BOXPLOT JADI HORIZONTAL
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                plugins: {
                                                    legend: {
                                                        display: false
                                                    }
                                                },
                                                scales: {
                                                    x: {
                                                        min: sharedYMin, // Batas Rupiah sekarang dikunci di sumbu X bersama
                                                        max: sharedYMax,
                                                        grid: {
                                                            color: '#f5f5f5'
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Nilai (Rupiah)',
                                                            font: {
                                                                size: 10
                                                            }
                                                        }
                                                    },
                                                    y: {
                                                        grid: {
                                                            display: false
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }
                                })();
                            </script>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BOX EDIT CONFIG VARIABEL -->
<div class="modal fade" id="modalEditAnades" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditAnades" class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Parameter Variabel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id" name="id">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Kode Variabel</label>
                    <input type="text" class="form-control" id="edit_kode" name="kode_variabel" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Deskripsi Variabel</label>
                    <textarea class="form-control" id="edit_desc" name="text" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-danger">Koneksikan dengan Kode Anomali</label>
                    <select class="form-select" id="edit_anomali" name="id_kategori_anomali">
                        <option value="">-- Jangan Hubungkan --</option>
                        <?php foreach ($listAnomali as $anom): ?>
                            <option value="<?= $anom['id']; ?>">[<?= esc($anom['kode_anomali']); ?>] <?= esc($anom['detil_anomali']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Logika Interaksi JQuery Modal -->
<script>
    $(document).ready(function() {
        $('.btn-edit-anades').on('click', function(e) {
            e.stopPropagation();

            $('#edit_id').val($(this).data('id'));
            $('#edit_kode').val($(this).data('kode'));
            $('#edit_desc').val($(this).data('desc'));
            $('#edit_anomali').val($(this).data('anomali'));

            $('#modalEditAnades').modal('show');
        });

        $('#formEditAnades').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('anades/update'); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $('#modalEditAnades').modal('hide');
                        alert(response.msg);
                        location.reload();
                    } else {
                        alert('Gagal: ' + response.msg);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem saat menyimpan data.');
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>