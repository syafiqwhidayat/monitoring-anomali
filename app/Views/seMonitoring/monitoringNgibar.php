<?= $this->extend('layout/template'); ?>

<?= $this->section('content') ?>

<style>
    /* Mengunci rasio grafik lingkaran di atas */
    .chart-donut-wrapper {
        position: relative;
        width: 100%;
        height: 220px;
        margin: 0 auto;
    }

    /* Kunci flexbox absolut untuk grafik batang di bawah */
    .chartBarContainer {
        position: relative;
        flex-grow: 1;
        width: 100%;
        min-height: 380px;
    }

    .chartBarInner {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>

<div class="container-xl">
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-dark fs-1"><?= $title; ?></h2>
                <div class="text-muted mt-1 small">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                    Data Terakhir Diperbarui: <span class="fw-bold text-primary"><?= $dateUpdated; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards mb-3">
        <div class="col-12 col-sm-4">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-status-start bg-success"></div>
                <div class="card-body p-3">
                    <div class="text-muted small font-weight-medium">Submitted by Responden</div>
                    <div class="h2 fw-bold text-success m-0"><?= number_format($dataHead['total_submit'], 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-status-start bg-danger"></div>
                <div class="card-body p-3">
                    <div class="text-muted small font-weight-medium">Rejected by Admin</div>
                    <div class="h2 fw-bold text-danger m-0"><?= number_format($dataHead['total_rejected'], 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card card-sm border-0 shadow-sm">
                <div class="card-status-start bg-blue"></div>
                <div class="card-body p-3">
                    <div class="text-muted small font-weight-medium">Draft</div>
                    <div class="h2 fw-bold text-blue m-0"><?= number_format($dataHead['total_draft'], 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2">
                    <h4 class="card-title text-center text-muted w-100 m-0">Proporsi Keseluruhan Status</h4>
                </div>
                <div class="card-body p-3">
                    <div class="chart-donut-wrapper">
                        <canvas id="pieKeseluruhan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2">
                    <h4 class="card-title text-center text-muted w-100 m-0">Proporsi SE2026-L (Lapangan)</h4>
                </div>
                <div class="card-body p-3">
                    <div class="chart-donut-wrapper">
                        <canvas id="pieSE2026L"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2">
                    <h4 class="card-title text-center text-muted w-100 m-0">Proporsi SE2026-UB (Usaha Besar)</h4>
                </div>
                <div class="card-body p-3">
                    <div class="chart-donut-wrapper">
                        <canvas id="pieSE2026UB"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards mb-4">
        <div class="col-12 col-md-7 d-flex flex-column">
            <div class="card border-0 shadow-sm d-flex flex-column">
                <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h3 class="card-title fw-bold text-dark m-0">Sebaran Progres Status Menurut Wilayah</h3>
                    <div class="btn-group h6 m-0">
                        <button type="button" class="btn btn-sm btn-primary" id="btn-bar-all" onclick="switchBarChart('keseluruhan', this)">Keseluruhan</button>
                        <button type="button" class="btn btn-sm btn-white" id="btn-bar-l" onclick="switchBarChart('se2026_l', this)">SE2026-L</button>
                        <button type="button" class="btn btn-sm btn-white" id="btn-bar-ub" onclick="switchBarChart('se2026_ub', this)">SE2026-UB</button>
                    </div>
                </div>
                <div class="card-body p-3 d-flex flex-column flex-fill">
                    <div class="chartBarContainer">
                        <div class="chartBarInner">
                            <canvas id="barWilayahNgibar" role="img"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5 d-flex flex-column">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-header py-3 bg-light">
                    <h3 class="card-title fw-bold text-dark m-0">Tren Kenaikan Progres Per Sesi Upload</h3>
                </div>
                <div class="card-body p-3 d-flex flex-column flex-fill">
                    <div style="position: relative; flex-grow: 1; width: 100%; min-height: 320px;">
                        <div style="position: absolute; top:0; left:0; right:0; bottom:0;">
                            <canvas id="lineTimelineNgibar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Definisi Warna Standar
        const statusColors = ['#94C11F', '#EE8911', '#0369A1', '#B3B3B3']; // Submitted, Rejected, Draft, Open
        const statusLabels = ['Submitted by Responden', 'Rejected by Admin', 'Draft', 'Open'];

        // --- 1. CONFIG GRAPH PIE KESELURUHAN ---
        new Chart(document.getElementById('pieKeseluruhan').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: [<?= implode(',', $dataPie['keseluruhan']); ?>],
                    backgroundColor: statusColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            },
            plugins: [{
                id: 'hideOpenDefault',
                afterInit: function(chart) {
                    // Melakukan "klik otomatis" secara sistem pada indeks ke-3 (Open) saat chart selesai dimuat
                    chart.toggleDataVisibility(3);
                    chart.update();
                }
            }]
        });

        // --- 2. CONFIG GRAPH PIE SE2026-L ---
        new Chart(document.getElementById('pieSE2026L').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: [<?= implode(',', $dataPie['se2026_l']); ?>],
                    backgroundColor: statusColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            },
            plugins: [{
                id: 'hideOpenDefault',
                afterInit: function(chart) {
                    // Melakukan "klik otomatis" secara sistem pada indeks ke-3 (Open) saat chart selesai dimuat
                    chart.toggleDataVisibility(3);
                    chart.update();
                }
            }]
        });

        // --- 3. CONFIG GRAPH PIE SE2026-UB ---
        new Chart(document.getElementById('pieSE2026UB').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: [<?= implode(',', $dataPie['se2026_ub']); ?>],
                    backgroundColor: statusColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            },
            plugins: [{
                id: 'hideOpenDefault',
                afterInit: function(chart) {
                    // Melakukan "klik otomatis" secara sistem pada indeks ke-3 (Open) saat chart selesai dimuat
                    chart.toggleDataVisibility(3);
                    chart.update();
                }
            }]
        });


        // --- 4. ENGINE BAR CHART WILAYAH (Horizontal Stacked Bar) ---
        const barCtx = document.getElementById('barWilayahNgibar').getContext('2d');
        const dbBarRaw = <?= json_encode($dataBar); ?>;

        window.myBarChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: dbBarRaw.keseluruhan.categories, // Nama wilayah akan berada di sisi kiri (Y-Axis)
                datasets: [{
                        label: 'Submitted by Responden',
                        data: dbBarRaw.keseluruhan.submitted,
                        backgroundColor: '#94C11F',
                        borderRadius: {
                            topLeft: 0,
                            bottomLeft: 0,
                            topRight: 0,
                            bottomRight: 0
                        }
                    },
                    {
                        label: 'Rejected by Admin',
                        data: dbBarRaw.keseluruhan.rejected,
                        backgroundColor: '#EE8911'
                    },
                    {
                        label: 'Draft',
                        data: dbBarRaw.keseluruhan.draft,
                        backgroundColor: '#0369A1'
                    },
                    {
                        label: 'Open',
                        data: dbBarRaw.keseluruhan.open,
                        backgroundColor: '#6c7a9c',
                        // Memberikan efek melengkung hanya di ujung kanan bar terakhir
                        borderRadius: {
                            topLeft: 0,
                            bottomLeft: 0,
                            topRight: 4,
                            bottomRight: 4
                        },
                        // KUNCI UTAMA UNTUK BAR CHART: Sembunyikan dataset ini secara default di awal
                        hidden: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // KUNCI 1: Mengubah grafik menjadi Horizontal (Wilayah di Y-Axis)
                scales: {
                    x: {
                        stacked: true, // KUNCI 2: Menumpuk status menjadi 1 bar panjang (X-Axis)
                        beginAtZero: true,
                        grid: {
                            color: '#f1f1f1'
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Assignment/Dokumen',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        stacked: true, // KUNCI 3: Menumpuk baris wilayah (Y-Axis)
                        grid: {
                            display: false
                        }, // Menghilangkan garis vertikal agar lebih bersih
                        ticks: {
                            font: {
                                weight: '600'
                            }
                        } // Membuat nama wilayah sedikit lebih tegas
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false // Pengguna cukup mengarahkan kursor ke baris wilayah untuk melihat semua status sekaligus
                    }
                }
            },
        });
    });

    // Fungsi Switcher Data Bar Chart tanpa reload halaman
    function switchBarChart(kegiatan, element) {
        const dbBarRaw = <?= json_encode($dataBar); ?>;

        // Ubah data internal dataset Chart.js
        window.myBarChart.data.datasets[0].data = dbBarRaw[kegiatan].submitted;
        window.myBarChart.data.datasets[1].data = dbBarRaw[kegiatan].rejected;
        window.myBarChart.data.datasets[2].data = dbBarRaw[kegiatan].draft;
        window.myBarChart.data.datasets[3].data = dbBarRaw[kegiatan].open;
        window.myBarChart.update(); // Render ulang grafik

        // Ubah Style Tombol Aktif
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-white');
        });
        element.classList.remove('btn-white');
        element.classList.add('btn-primary');
    }

    // INISIALISASI LINE CHART TIMELINE (Tren Berdasarkan Log Upload)
    const timelineRaw = <?= json_encode($dataTimeline); ?>;
    new Chart(document.getElementById('lineTimelineNgibar').getContext('2d'), {
        type: 'line',
        data: {
            labels: timelineRaw.labels, // Tanggal upload (dd/mm) tiap log
            datasets: [{
                    label: 'Submitted',
                    data: timelineRaw.submitted,
                    borderColor: '#94C11F',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    borderWidth: 3,
                    pointRadius: 4
                },
                {
                    label: 'Draft',
                    data: timelineRaw.draft,
                    borderColor: '#0369A1',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 3
                }
                // Silakan tambahkan line rejected/open jika dibutuhkan
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f5f5f5'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10
                    }
                }
            }
        }
    });
</script>

<?= $this->endSection(); ?>