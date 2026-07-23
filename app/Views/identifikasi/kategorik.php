<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Load Javascript Dependencies (Chart.js + Ekstensi Matrix Heatmap & Sankey Flow) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.2.0/dist/chartjs-chart-matrix.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-chart-sankey"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-sankey@0.12.0/dist/chartjs-chart-sankey.min.js"></script> -->

<div class="container" jenis-konf="analisis-kategorikal">

    <!-- 1. TITLE HEADER -->
    <div class="card card-body mb-3 shadow-sm border-0 bg-primary text-white">
        <div class="d-flex align-items-center">
            <span class="me-3 fs-2">📊</span>
            <div>
                <h1 class="h3 mb-1 text-white fw-bold">Analisis Statistik Kategorikal</h1>
                <p class="mb-0 small opacity-75">Validasi sebaran frekuensi, matrix hubungan, dan konsistensi isian variabel bertipe kategori.</p>
            </div>
        </div>
    </div>

    <!-- 2. PARAMETER FILTER SELECTION -->
    <div class="card card-body mb-3 shadow-sm border-light">
        <div class="hr-text hr-text-left fs-5 mb-3 fw-bold text-secondary">Parameter Penyaringan Data</div>
        <form action="<?= current_url(); ?>" method="get">
            <div class="row g-3">
                <!-- Filter Wilayah Kerja -->
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

                <!-- Filter Kode Variabel Kategori -->
                <div class="col-md-5">
                    <label class="form-label fw-medium">2. Variabel Kategori</label>
                    <select name="selected-variabel" class="form-select" required>
                        <option value="">-- Pilih Kode Variabel --</option>
                        <?php foreach ($listVariabel as $lv): ?>
                            <option value="<?= $lv['id']; ?>" <?= ($selectedVariabel == $lv['id']) ? 'selected' : ''; ?>>
                                [<?= esc($lv['kode_variabel']); ?>] <?= esc($lv['deskripsi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Aksi Eksekusi Form -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        🔎 Tampilkan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($activeKategori): ?>

        <!-- HEADER VARIABEL INFORMASI -->
        <div class="card card-body mb-3 border-light shadow-sm bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div>
                        <span class="badge bg-blue text-white font-monospace px-2 py-1 fs-6 me-2"><?= esc($activeKategori['kode_variabel']); ?></span>
                        <span class="badge ms-2 text-uppercase"><?= esc($activeKategori['jenis']); ?>-variabel</span>
                    </div>
                    <strong class="text-dark fs-5">Deskripsi Variabel: </strong>
                    <strong class="text-dark fs-5"><?= esc($activeKategori['deskripsi']); ?></strong>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary rounded-pill btn-edit-kategori"
                        data-id="<?= $activeKategori['id']; ?>"
                        data-kode="<?= esc($activeKategori['kode_variabel']); ?>"
                        data-desc="<?= esc($activeKategori['deskripsi']); ?>"
                        data-anomali="<?= $activeKategori['id_kategori_anomali']; ?>">
                        ⚙️ Tautkan Aturan Anomali
                    </button>
                </div>
            </div>

            <!-- 3. VISUALISASI DYNAMIC CHART -->
            <div class="card card-body mb-3 shadow-sm border-light">
                <div class="hr-text hr-text-left fs-5 mb-3 fw-bold text-primary">Visualisasi Distribusi Struktur Kategori</div>
                <div class="p-2 border rounded bg-white">
                    <div style="position: relative; min-height: 50vh; width: 100%;">
                        <canvas id="kategorical_distribution_chart"></canvas>
                    </div>
                </div>
                <div class="text-muted small mt-2">
                    ℹ️ <em>Tipe grafik otomatis menyesuaikan struktur data:
                        <strong><?= $activeKategori['jenis'] == 'mono' ? 'Horizontal Bar Chart (1D)' : ($activeKategori['jenis'] == 'dwi' ? 'Matrix Heatmap (2D)' : 'Sankey Flow Diagram (Multi)'); ?></strong>.</em>
                </div>
            </div>

            <!-- 4. AUTOMATED AI INSIGHTS KESIMPULAN -->
            <?php if ($activeKategori['id_kategori_anomali']): ?>
                <div class="card card-body mb-3 border-left-purple shadow-sm bg-gradient-light">
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2 fs-4">🤖</span>
                        <h5 class="mb-0 fw-bold text-purple">Kesimpulan Konfirmasi Anomali AI</h5>
                    </div>
                    <div class="bg-purple-minimal p-3 rounded border border-purple-subtle" style="background-color: #fdfcff;">
                        <?php if (!empty($kesimpulan['hasil_kesimpulan'])): ?>
                            <div class="text-dark markdown-ai-output" style="line-height: 1.7; white-space: pre-line;">
                                <?php
                                // Jika teks AI digabung dalam satu baris panjang dengan pemisah ' * ', pecah manual agar menjadi enter asli
                                $cleanText = str_replace(' * ', "\n* ", $kesimpulan['hasil_kesimpulan']);
                                echo nl2br(esc($cleanText));
                                ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-circle text-warning mb-2" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                    <path d="M12 9v4"></path>
                                    <path d="M12 16v.01"></path>
                                </svg>
                                <p class="m-0 font-weight-medium text-secondary">
                                    Tidak ada kesimpulan untuk kategori anomali dan wilayah ini.
                                </p>
                                <?php if (isset($kesimpulan['is_request']) && $kesimpulan['is_request'] == 1): ?>
                                    <small class="text-purple d-block mt-1">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                        Antrean sistem aktif: Model AI akan menghasilkan kesimpulan peda besok...
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 5. TABEL TEMUAN KASUS ANOMALI LAPANGAN -->
            <div class="card card-body shadow-sm border-light">
                <div class="hr-text hr-text-left fs-5 mb-3 fw-bold text-danger">Daftar Temuan Kasus Anomali Lapangan</div>

                <?php if ($activeKategori['id_kategori_anomali']): ?>
                    <!-- Panel Indikator Aturan Anomali yang Mengikat -->
                    <div class="alert alert-danger-lt border-danger-subtle mb-3 d-flex align-items-start">
                        <span class="me-2 fs-4">⚠️</span>
                        <div>
                            <span class="fw-bold d-block text-danger mb-1">Terikat Batasan Rule: [<?= esc($activeKategori['kode_anomali']); ?>] <?= esc($activeKategori['definisi_anomali']); ?></span>
                            <span class="small text-muted"><?= esc($activeKategori['detil_anomali']); ?></span>
                        </div>
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

                    <!-- Kontrol Pagination links -->
                    <?php if (!empty($pager)): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <?= $pager; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="alert alert-warning mb-0 text-center py-3">
                        ⚠️ Parameter Variabel ini belum ditautkan dengan <strong>ID Kategori Anomali</strong> sistem. Sila klik tombol hubungkan konfigurasi untuk memetakan penanganan.
                    </div>
                <?php endif; ?>
            </div>

            <!-- ENGINE SCRIPT INLINE: DYNAMIC CHART RENDERING -->
            <script>
                (function() {
                    const dataPack = <?= $activeKategori['data']; ?>;
                    const tipeHubungan = "<?= $activeKategori['jenis']; ?>";
                    const canvasCtx = document.getElementById("kategorical_distribution_chart").getContext('2d');
                    // console.log(dataPack.map(d => ({
                    //     x: d.x,
                    //     y: d.y,
                    //     v: d.value
                    // })));
                    // console.log(dataPack);

                    if (tipeHubungan === 'mono') {
                        new Chart(canvasCtx, {
                            type: 'bar',
                            data: {
                                labels: dataPack.map(d => d.x),
                                datasets: [{
                                    label: 'Frekuensi Record',
                                    data: dataPack.map(d => d.value),
                                    backgroundColor: '#206bc4',
                                    borderRadius: 4
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
                                }
                            }
                        });

                    } else if (tipeHubungan === 'dwi') {
                        const labelsX = [...new Set(dataPack.map(d => d.x))];
                        const labelsY = [...new Set(dataPack.map(d => d.y))];
                        new Chart(canvasCtx, {
                            type: 'matrix',
                            data: {
                                datasets: [{
                                    label: 'Matrix Hubungan',
                                    data: dataPack.map(d => ({
                                        x: d.x,
                                        y: d.y,
                                        v: d.value
                                    })),
                                    backgroundColor(context) {
                                        const valueObj = context.dataset.data[context.dataIndex];
                                        if (!valueObj) return 'rgba(0,0,0,0.03)';
                                        const intensity = Math.min(valueObj.v / 150, 1);
                                        return `rgba(148, 193, 31, ${intensity})`;
                                    },
                                    width({
                                        chart
                                    }) {
                                        if (!chart.chartArea) return 0;
                                        return (chart.chartArea.right - chart.chartArea.left) / labelsX.length - 1;
                                    },
                                    height({
                                        chart
                                    }) {
                                        if (!chart.chartArea) return 0;
                                        return (chart.chartArea.bottom - chart.chartArea.top) / labelsY.length - 1;
                                    }
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: false,
                                    tooltip: {
                                        callbacks: {
                                            label(context) {
                                                const point = context.raw; // Mengambil objek {x, y, v}
                                                return `x: ${point.x}, y: ${point.y}, nilai: ${point.v}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        type: 'category',
                                        labels: labelsX,
                                        offset: true,
                                        grid: {
                                            display: true,
                                            drawBorder: false
                                        }
                                    },
                                    y: {
                                        type: 'category',
                                        labels: labelsY,
                                        offset: true,
                                        grid: {
                                            display: true,
                                            drawBorder: false
                                        }
                                    }
                                }
                            }
                        });

                    } else if (tipeHubungan === 'multi') {
                        new Chart(canvasCtx, {
                            type: 'sankey',
                            data: {
                                datasets: [{
                                    label: 'Aliran Logika Kategori',
                                    data: dataPack.map(d => ({
                                        from: d.x,
                                        to: d.y,
                                        flow: d.value
                                    })),
                                    colorFrom: '#4299e1',
                                    colorTo: '#48bb78',
                                    colorMode: 'gradient',
                                    size: 'flow'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    }
                })();
            </script>

        <?php else: ?>
            <!-- EMPTY BLANK BLOK STATE -->
            <div class="card card-body text-center py-5 shadow-sm border-light bg-light text-muted">
                <div class="mb-3 fs-1">📌</div>
                <h5 class="fw-bold text-dark">Data Belum Dimuat</h5>
                <p class="small mb-0 mx-auto" style="max-width: 480px;">Silakan tentukan target Wilayah Kerja dan Kode Variabel Kategori pada dropdown filter di atas untuk menganalisis inkonsistensi struktur rekaman sektoral.</p>
            </div>
        <?php endif; ?>
        </div>

        <!-- CONTAINER MODAL BOX INTERAKTIF -->
        <div class="modal fade" id="modalConfigKategori" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form id="formActionKategori" class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-dark">⚙️ Hubungkan Variabel ke Aturan Anomali</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modal_id" name="id">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kode Variabel</label>
                            <input type="text" class="form-control bg-light font-monospace" id="modal_kode" name="kode_variabel" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Deskripsi Analisis</label>
                            <textarea class="form-control" id="modal_desc" name="deskripsi" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-danger">Pilih Aturan Kategori Anomali</label>
                            <select class="form-select" id="modal_anomali" name="id_kategori_anomali">
                                <option value="">-- Kosongkan / Lepas Hubungan --</option>
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

        <!-- SYSTEM TOAST ALERT -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1200;">
            <div id="toastSystemKategori" class="toast align-items-center border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center font-medium">
                        <span id="toastIconElement" class="me-2"></span>
                        <span id="toastMessageElement"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- CLIENT JAVASCRIPT LOGIC OPERATIONS -->
        <script>
            $(document).ready(function() {
                const toastHtmlNode = document.getElementById('toastSystemKategori');
                const systemBootstrapToast = new bootstrap.Toast(toastHtmlNode, {
                    delay: 2500
                });

                function renderToastMessage(success, statusMessage) {
                    const $toast = $('#toastSystemKategori');
                    $toast.removeClass('bg-success bg-danger text-white');
                    if (success) {
                        $toast.addClass('bg-success text-white');
                        $('#toastIconElement').html('✅');
                    } else {
                        $toast.addClass('bg-danger text-white');
                        $('#toastIconElement').html('❌');
                    }
                    $('#toastMessageElement').text(statusMessage);
                    systemBootstrapToast.show();
                }

                // Pemicu Modal Pengaturan Tautan
                $(document).on('click', '.btn-edit-kategori', function() {
                    $('#modal_id').val($(this).data('id'));
                    $('#modal_kode').val($(this).data('kode'));
                    $('#modal_desc').val($(this).data('desc'));
                    $('#modal_anomali').val($(this).data('anomali'));
                    $('#modalConfigKategori').modal('show');
                });

                // Submit pembaruan tautan via AJAX aman
                $('#formActionKategori').on('submit', function(event) {
                    event.preventDefault();
                    let serializeData = $(this).serializeArray();
                    serializeData.push({
                        name: '<?= csrf_token() ?>',
                        value: '<?= csrf_hash() ?>'
                    });

                    $.ajax({
                        url: '<?= base_url('kategori/update_konfigurasi'); ?>',
                        type: 'POST',
                        data: $.param(serializeData),
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.status) {
                                $('#modalConfigKategori').modal('hide');
                                renderToastMessage(true, response.msg);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                renderToastMessage(false, 'Gagal memproses penyesuaian parameter.');
                            }
                        },
                        error: function() {
                            renderToastMessage(false, 'Terjadi kegagalan komunikasi HTTP.');
                        }
                    });
                });
            });
        </script>

        <!-- PREFERENCE DESAIN STYLE INTERNAL -->
        <style>
            .text-purple {
                color: #6f42c1;
            }

            .border-left-purple {
                border-left: 5px solid #6f42c1 !important;
            }

            .bg-gradient-light {
                background: linear-gradient(145deg, #fdfbfb 0%, #f4f4f4 100%);
            }

            .bg-warning-lt {
                background-color: rgba(245, 159, 0, 0.12);
            }

            .alert-danger-lt {
                background-color: rgba(214, 51, 108, 0.08);
                color: #d6336c;
                border: 1px solid rgba(214, 51, 108, 0.15);
            }

            .shadow-inner {
                box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            }
        </style>

        <?= $this->endSection(); ?>