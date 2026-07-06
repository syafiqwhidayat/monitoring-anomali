<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Load CDN Jquery & Chart.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@sgratzl/chartjs-chart-boxplot@4.4.5/build/index.umd.min.js"></script>

<div class="container" jenis-konf="anades">
    <!-- Header Page -->
    <div class="card card-body mb-3 shadow-sm">
        <h1 class="h2 text-primary mb-0"><?= esc($title); ?></h1>
    </div>

    <!-- Filter Form Utama -->
    <div class="card card-body mb-3 shadow-sm">
        <div class="hr-text hr-text-left fs-5 mb-3 fw-bold text-secondary">Parameter Penyaringan Data</div>
        <form action="<?= current_url(); ?>" method="get">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-medium">1. Wilayah Kerja / Kabupaten</label>
                    <select name="selected-wilayah" class="form-select" required>
                        <option value="">-- Pilih Wilayah --</option>
                        <option value="1301" <?= ($selectedWilayah == '1301') ? 'selected' : ''; ?>>1301 - Kab. Kep. Mentawai</option>
                        <option value="1302" <?= ($selectedWilayah == '1302') ? 'selected' : ''; ?>>1302 - Kab. Pesisir Selatan</option>
                        <option value="1303" <?= ($selectedWilayah == '1303') ? 'selected' : ''; ?>>1303 - Kab. Solok</option>
                        <option value="1304" <?= ($selectedWilayah == '1304') ? 'selected' : ''; ?>>1304 - Kab. Sijunjung</option>
                        <option value="1305" <?= ($selectedWilayah == '1305') ? 'selected' : ''; ?>>1305 - Kab. Tanah Datar</option>
                        <option value="1306" <?= ($selectedWilayah == '1306') ? 'selected' : ''; ?>>1306 - Kab. Padang Panjang</option>
                        <option value="1307" <?= ($selectedWilayah == '1307') ? 'selected' : ''; ?>>1307 - Kab. Agam</option>
                        <option value="1308" <?= ($selectedWilayah == '1308') ? 'selected' : ''; ?>>1308 - Kab. Lima Puluh Kota</option>
                        <option value="1309" <?= ($selectedWilayah == '1309') ? 'selected' : ''; ?>>1309 - Kab. Pasaman</option>
                        <option value="1310" <?= ($selectedWilayah == '1310') ? 'selected' : ''; ?>>1310 - Kab. Solok Selatan</option>
                        <option value="1311" <?= ($selectedWilayah == '1311') ? 'selected' : ''; ?>>1311 - Kab. Dharmasraya</option>
                        <option value="1312" <?= ($selectedWilayah == '1312') ? 'selected' : ''; ?>>1312 - Kab. Pasaman Barat</option>
                        <option value="1371" <?= ($selectedWilayah == '1371') ? 'selected' : ''; ?>>1371 - Kota Padang</option>
                        <option value="1372" <?= ($selectedWilayah == '1372') ? 'selected' : ''; ?>>1372 - Kota Solok</option>
                        <option value="1373" <?= ($selectedWilayah == '1373') ? 'selected' : ''; ?>>1373 - Kota Sawahlunto</option>
                        <option value="1374" <?= ($selectedWilayah == '1374') ? 'selected' : ''; ?>>1374 - Kota Padang Panjang</option>
                        <option value="1375" <?= ($selectedWilayah == '1375') ? 'selected' : ''; ?>>1375 - Kota Bukittinggi</option>
                        <option value="1376" <?= ($selectedWilayah == '1376') ? 'selected' : ''; ?>>1376 - Kota Payakumbuh</option>
                        <option value="1377" <?= ($selectedWilayah == '1377') ? 'selected' : ''; ?>>1377 - Kota Pariaman</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-medium">2. Variabel Analisis Deskriptif</label>
                    <select name="selected-variabel" class="form-select" required>
                        <option value="">-- Pilih Variabel --</option>
                        <?php foreach ($listVariabel as $lv): ?>
                            <option value="<?= $lv['id']; ?>" <?= ($selectedVariabel == $lv['id']) ? 'selected' : ''; ?>>
                                [<?= esc($lv['kode_variabel']); ?>] <?= esc($lv['deskripsi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        ⚡ Tampilkan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($activeAnades): ?>
        <!-- MAIN MONITORING DASHBOARD (HANYA MUNCUL JIKA FILTER AKTIF) -->
        <div class="card card-body mb-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <div>
                    <span class="badge bg-blue text-white font-monospace px-2 py-1 fs-6 me-2"><?= esc($activeAnades['kode_variabel']); ?></span>
                    <strong class="text-dark fs-5"><?= esc($activeAnades['deskripsi']); ?></strong>
                </div>
                <button class="btn btn-sm btn-outline-secondary rounded-pill btn-edit-anades"
                    data-id="<?= $activeAnades['id']; ?>"
                    data-kode="<?= esc($activeAnades['kode_variabel']); ?>"
                    data-desc="<?= esc($activeAnades['deskripsi']); ?>"
                    data-anomali="<?= $activeAnades['id_kategori_anomali']; ?>">
                    ⚙️ Ubah Konfigurasi Variabel
                </button>
            </div>

            <div class="row g-3">
                <!-- Panel Kiri: Statistik Ringkasan -->
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light mb-3">
                        <span class="d-block text-uppercase small text-muted fw-bold mb-2">Statistik Deskriptif</span>
                        <table class="table table-sm table-bordered bg-white small align-middle mb-0">
                            <tr>
                                <td>Batas Bawah (Min)</td>
                                <td class="fw-bold text-danger text-end"><?= number_format($activeAnades['n_batas_bawah'], 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Kuartil 1 (Q1)</td>
                                <td class="text-end"><?= number_format($activeAnades['n_q1'], 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Median</td>
                                <td class="text-primary fw-semibold text-end"><?= number_format($activeAnades['median'], 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Rata-rata (Mean)</td>
                                <td class="text-end"><?= number_format($activeAnades['n_rata'], 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Kuartil 3 (Q3)</td>
                                <td class="text-end"><?= number_format($activeAnades['n_q3'], 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Batas Atas (Max)</td>
                                <td class="fw-bold text-danger text-end"><?= number_format($activeAnades['n_batas_atas'], 2, ',', '.'); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="p-3 border rounded bg-light">
                        <span class="d-block text-uppercase small text-muted fw-bold mb-1">Kategori Anomali Tertaut:</span>
                        <?php if ($activeAnades['id_kategori_anomali']): ?>
                            <span class="badge bg-danger mb-1 fs-6">[<?= esc($activeAnades['kode_anomali']); ?>]</span>
                            <p class="small text-dark fw-medium mb-0"><?= esc($activeAnades['detil_anomali']); ?></p>
                        <?php else: ?>
                            <span class="text-muted fst-italic small">Belum dikaitkan ke sistem anomali. Silakan klik tombol 'Ubah Konfigurasi' untuk menautkan.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Panel Kanan: Visualisasi Grafik -->
                <div class="col-md-8">
                    <span class="d-block text-uppercase small text-muted fw-bold mb-1">Kurva Distribusi Frekuensi & Outlier Data</span>
                    <div style="position: relative; height:180px; width:100%" class="border p-2 rounded bg-white mb-2">
                        <canvas id="main_line_chart"></canvas>
                    </div>

                    <span class="d-block text-uppercase small text-muted fw-bold mb-1">Grafik Sebaran Ringkasan Statistik (Boxplot)</span>
                    <div style="position: relative; height:110px; width:100%" class="border p-2 rounded bg-white">
                        <canvas id="main_boxplot_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DATA ANOMALI YANG TERTAUT -->
        <div class="card card-body shadow-sm">
            <div class="hr-text hr-text-left fs-5 mb-3 fw-bold text-danger">Daftar Sampel Record Temuan Anomali</div>

            <?php if ($activeAnades['id_kategori_anomali']): ?>
                <div class="alert alert-info py-2 mb-3">
                    <span class="badge bg-red text-white font-monospace">[<?= esc($activeAnades['kode_anomali']); ?>]</span> - <?= esc($activeAnades['detil_anomali']); ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered align-middle small">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 10%">ID Wilayah</th>
                                <th style="width: 20%">Nama KRT</th>
                                <th style="width: 20%">Nama ART</th>
                                <th style="width: 20%">Isian Fasih</th>
                                <th style="width: 15%">Konfirmasi</th>
                                <th style="width: 10%">Ke Fasih SM</th>
                                <th style="width: 10%">Status Lap</th>
                                <th style="width: 5%">Status Insert</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listAnomaliSampel)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted fst-italic py-3">Tidak ditemukan sampel anomali data untuk wilayah ini. Semuanya clean! ✨</td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $page = request()->getGet('page_anomali_group') ?? 1;
                                $no = 1 + (($page - 1) * 10);
                                foreach ($listAnomaliSampel as $row):
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center font-monospace"><?= esc($row['id_wilayah']); ?></td>
                                        <td><?= esc($row['nm_krt']); ?></td>
                                        <td><?= esc($row['nm_art']); ?></td>
                                        <td><?= esc($row['isi_fasih']); ?></td>
                                        <td><?= esc($row['konfirmasi'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($row['kd_krt']); ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="badge bg-blue-lt text-blue px-1-5 py-0-5 rounded text-decoration-none"
                                                style="font-size: 0.65rem; max-width: 80px; font-family: var(--bs-font-sans-serif);"
                                                title="Buka Fasih untuk assigment <?= esc($row['kd_krt']); ?>">
                                                ke Fasih-SM
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $row['is_lap'] === '1' ? 'bg-success text-white' : 'bg-secondary text-white'; ?>">
                                                <?= $row['is_lap'] === '1' ? 'Kond Lap' : 'Perbaikan'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center text-muted font-monospace">
                                            <span class="badge <?= $row['is_insert'] === '1' ? 'bg-warning text-white' : 'bg-success text-white'; ?>">
                                                <?= $row['is_insert'] === '1' ? 'Aktif' : 'Clean'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Blok Pagination -->
                <?php if (!empty($pager)): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?= $pager; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-warning mb-0">
                    ⚠️ Belum ada kategori anomali yang ditautkan ke variabel ini. Data rincian individu tidak dapat dirender.
                </div>
            <?php endif; ?>
        </div>

        <!-- SCRIPT VISUALISASI GRAFIK TUNGGAL -->
        <script>
            (function() {
                // 1. Ambil & Parsing Data Histogram Frekuensi
                <?php
                $histogramData = json_decode($activeAnades['n_histogram'], true) ?? [];
                $labels = array_keys($histogramData);
                $values = array_values($histogramData);

                // Parsing Data JSON Outlier berbentuk asosiatif
                $outlierData = json_decode($activeAnades['n_outlier'] ?? '[]', true) ?? [];
                ?>

                const histLabels = <?= json_encode($labels); ?>;
                const histValues = <?= json_encode($values); ?>;

                // KUNCI UTAMA: Ekstrak hanya nilainya saja (mengabaikan UUID kunci string) dan ubah ke tipe Number
                const rawOutliers = <?= json_encode($outlierData); ?>;
                const cleanOutliers = Object.values(rawOutliers).map(Number);

                const valMin = <?= floatval($activeAnades['n_batas_bawah']); ?>;
                const valMax = <?= floatval($activeAnades['n_batas_atas']); ?>;

                // Hitung batas sumbu X agar mencakup outlier terbesar (gunakan Math.max untuk auto-expand)
                const maxOutlierValue = cleanOutliers.length > 0 ? Math.max(...cleanOutliers) : valMax;
                const minOutlierValue = cleanOutliers.length > 0 ? Math.min(...cleanOutliers) : valMin;

                const dynamicXMin = Math.min(valMin, minOutlierValue) * 0.95;
                const dynamicXMax = Math.max(valMax, maxOutlierValue) * 1.05;

                // --- RENDERING LINE CHART (HISTOGRAM) ---
                // Untuk menampilkan outlier di line chart, kita petakan nilai outlier ke titik koordinat (X, Y) 
                // dengan asumsi frekuensinya berada di puncak atau sumbu dasar, atau mencocokkan label X yang ada.
                const ctxLine = document.getElementById("main_line_chart").getContext('2d');

                // Membuat dataset tambahan khusus untuk titik outlier (Scatter effect di atas Line Chart)
                const outlierPointsForLine = cleanOutliers.map(val => {
                    // Cari index label yang paling mendekati nilai outlier, atau langsung plotting jika label berupa angka
                    return {
                        x: val.toString(),
                        y: 1
                    }; // Diplot di ketinggian frekuensi = 1 sebagai penanda eksistensi pencilan
                });

                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: histLabels,
                        datasets: [{
                                label: 'Frekuensi Sampel',
                                data: histValues,
                                borderColor: '#206bc4',
                                backgroundColor: 'rgba(32, 107, 196, 0.08)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Titik Outlier',
                                data: cleanOutliers.map(val => {
                                    // Mencari kecocokan index label histogram
                                    const idx = histLabels.indexOf(val.toString());
                                    return idx !== -1 ? histValues[idx] : null;
                                }),
                                borderColor: '#e63946',
                                backgroundColor: '#e63946',
                                pointRadius: 5,
                                showLine: false, // Hanya memunculkan dot merah tanpa garis penghubung
                                type: 'line'
                            }
                        ]
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
                                beginAtZero: true,
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

                // --- RENDERING BOXPLOT HORIZONTAL ---
                const ctxBox = document.getElementById("main_boxplot_chart").getContext('2d');
                new Chart(ctxBox, {
                    type: 'boxplot',
                    data: {
                        labels: ['<?= esc($activeAnades['kode_variabel']); ?>'],
                        datasets: [{
                            label: 'Sebaran Nilai',
                            backgroundColor: 'rgba(42, 157, 143, 0.25)',
                            borderColor: '#2a9d8f',
                            borderWidth: 2,
                            itemRadius: 5,
                            outlierRadius: 5,
                            outlierColor: '#e63946',
                            outlierBackgroundColor: '#e63946',
                            data: [{
                                min: valMin,
                                q1: <?= floatval($activeAnades['n_q1']); ?>,
                                median: <?= floatval($activeAnades['median']); ?>,
                                q3: <?= floatval($activeAnades['n_q3']); ?>,
                                max: valMax,
                                mean: <?= floatval($activeAnades['n_rata']); ?>,
                                outliers: cleanOutliers // <--- Sekarang berupa array murni: [500000, 400000, ...]
                            }]
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                min: dynamicXMin, // Sumbu X otomatis melebar dinamis agar nominal 2 Juta tidak terpotong
                                max: dynamicXMax,
                                title: {
                                    display: true,
                                    text: 'Nilai Realisasi (Rupiah)',
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
            })();
        </script>

    <?php else: ?>
        <!-- State Awal saat filter belum di-submit -->
        <div class="card card-body text-center py-5 shadow-sm bg-light text-muted">
            <div class="mb-2" style="font-size: 2.5rem;">📊</div>
            <h5>Silakan tentukan Wilayah dan Variabel terlebih dahulu untuk memuat Analisis Deskriptif & Tabel Detil Kasus Anomali.</h5>
        </div>
    <?php endif; ?>
</div>

<!-- MODAL BOX EDIT PARAMETER VARIABEL -->
<div class="modal fade" id="modalEditAnades" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditAnades" class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">⚙️ Edit Parameter Variabel</h5>
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
                    <textarea class="form-control" id="edit_desc" name="deskripsi" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-danger">Koneksikan dengan Aturan Anomali</label>
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

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <span id="toastIcon" class="me-2"></span>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Bootstrap Toast Instance
        const toastEl = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastEl, {
            delay: 4000
        });

        // Fungsi Helper untuk Menampilkan Notifikasi di Bawah
        function showNotification(status, message) {
            const $toastEl = $('#liveToast');
            const $icon = $('#toastIcon');

            // Reset class warna sebelumnya
            $toastEl.removeClass('bg-success bg-danger text-white');

            if (status === true || status === 'success') {
                $toastEl.addClass('bg-success text-white');
                $icon.html('✅');
            } else {
                $toastEl.addClass('bg-danger text-white');
                $icon.html('❌');
            }

            $('#toastMessage').text(message);
            toast.show();
        }

        // Event Handler Modal Binding (Menggunakan Delegation agar tetap berfungsi meski tabel re-render)
        $(document).on('click', '.btn-edit-anades', function(e) {
            e.stopPropagation();
            $('#edit_id').val($(this).data('id'));
            $('#edit_kode').val($(this).data('kode'));
            $('#edit_desc').val($(this).data('desc'));
            $('#edit_anomali').val($(this).data('anomali'));
            $('#modalEditAnades').modal('show');
        });

        // Ajax form submit handling
        $('#formEditAnades').on('submit', function(e) {
            e.preventDefault();

            // Setup data parameter + Menyisipkan CSRF Token CodeIgniter 4 secara dinamis
            let formData = $(this).serializeArray();
            formData.push({
                name: '<?= csrf_token() ?>',
                value: '<?= csrf_hash() ?>'
            });

            $.ajax({
                url: '<?= base_url('identifikasi/anades/update'); ?>',
                type: 'POST',
                data: $.param(formData), // Convert kembali ke string query serial
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $('#modalEditAnades').modal('hide');
                        showNotification(true, response.msg || 'Data parameter berhasil diperbarui!');

                        // Berikan jeda sedikit agar user bisa membaca toast sukses sebelum reload halaman
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification(false, response.msg || 'Gagal menyimpan perubahan.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification(false, 'Terjadi kesalahan sistem atau kendala hak akses (CSRF).');
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>