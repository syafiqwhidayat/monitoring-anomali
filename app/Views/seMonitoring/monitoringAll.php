<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container-xl" jenis-konf="0">
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title text-dark fs-1"><?= $title; ?></h2>
                <div class="text-muted mt-1 small">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-edit d-inline" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M21 12a9 9 0 1 0 -9.972 8.948" />
                        <path d="M12 7v5l1 1" />
                        <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                    </svg>
                    Terakhir Diperbarui: <span class="fw-bold text-primary-bps"><?= $dateUpdated; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cards">
        <!-- sisi kiri -->
        <div class="col-12 col-md-7">
            <!-- jumlah button -->
            <div class="row row-cards mb-3">
                <div class="col-12 col-sm-4">
                    <div class="card card-sm border-0 shadow-sm">
                        <div class="card-status-start bg-bps-prm"></div>
                        <div class="card-body p-3">
                            <div class="text-muted small font-weight-medium">Jumlah Submit <span class="badge bg-red-lt">Dummy</span></div>
                            <div class="h2 fw-bold text-blue m-0"><?= number_format($dataHead['total_submit'], 0, ',', '.'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card card-sm border-0 shadow-sm">
                        <div class="card-status-start bg-success"></div>
                        <div class="card-body p-3">
                            <div class="text-muted small font-weight-medium">Usaha Digital (ED) <span class="badge bg-red-lt">Dummy</span></div>
                            <div class="h2 fw-bold text-success m-0"><?= number_format($dataHead['total_ED'], 0, ',', '.'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card card-sm border-0 shadow-sm">
                        <div class="card-status-start bg-orange"></div>
                        <div class="card-body p-3">
                            <div class="text-muted small font-weight-medium">Usaha Non Digital (NED) <span class="badge bg-red-lt">Dummy</span></div>
                            <div class="h2 fw-bold text-orange m-0"><?= number_format($dataHead['total_NED'], 0, ',', '.'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- piechart progres -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header py-2">
                    <h4 class="card-title text-muted m-0">Progres Capaian Ngibar <span class="badge bg-red-lt">Dummy</span></h4>
                </div>
                <div class="card-body p-3">
                    <div style="height: 240px; position: relative;">
                        <canvas id="grafNgibar"></canvas>
                    </div>
                </div>
            </div>
            <!-- piechart ekonomi digital -->
            <div class="row row-cards mb-3">
                <div class="col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-2 text-center">
                            <div style="height: 240px; position: relative;">
                                <canvas id="grafPersenAnom"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-2 text-center">
                            <div style="height: 240px; position: relative;">
                                <canvas id="grafPersenEkonomiDigital"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- linechat graifik jumlah -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header py-2">
                    <h4 class="card-title text-muted m-0">Tren Riwayat Submit harian <span class="badge bg-red-lt">Dummy</span></h4>
                </div>
                <div class="card-body p-3">
                    <div style="height: 200px; position: relative;">
                        <canvas id="grafTimeline"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- sisi kanan -->
        <div class="col-12 col-md-5 d-flex flex-column">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bold text-dark m-0">Status Dokumen Menurut Wilayah</h3>
                    <span class="badge bg-red-lt">Perlu Atensi</span>
                </div>
                <div class="card-body p-3 d-flex flex-column flex-fill">
                    <div class="chartBarContainer">
                        <div class="chartBarInner">
                            <canvas id="grafikAnomali" aria-label="grafik_jumlah_anomali" role="img">
                                <p>Browser Kamu tidak Support Canvas Element</p>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cards mt-1 mb-5">
        <div class="col-12">
            <div class="hr-text text-blue fw-bold fs-3">Analisis Potensi Ekonomi Digital</div>
        </div>
        <div class="col-12 col-md-4 d-flex flex-column">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-header py-2">
                    <h4 class="card-title text-muted m-0">Distribusi Level Potensi <span class="badge bg-red-lt">Dummy</span></h4>
                </div>
                <div class="card-body p-3 d-flex flex-column flex-fill">
                    <div class="chart-donut-potensi">
                        <canvas id="pie_submit_level" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 d-flex flex-column">
            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                <div class="card-header py-2">
                    <h4 class="card-title text-muted m-0">Peringkat Potensi ED Per Wilayah <span class="badge bg-red-lt">Dummy</span></h4>
                </div>
                <div class="card-body p-3 d-flex flex-column flex-fill">
                    <div class="chartBarContainer">
                        <div class="chartBarInner">
                            <canvas id="bar_potensi_ed_kab" role="img"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Ambil konteks canvas
        const ctx = document.getElementById('grafikAnomali');
        const persenNgibar = document.getElementById('grafNgibar');
        const persenAssigment = document.getElementById('grafPersenAnom');
        const persenSubmit = document.getElementById('grafPersenEkonomiDigital');
        const timelineAnom = document.getElementById('grafTimeline');
        const bar_potensiEd_kab = document.getElementById('bar_potensi_ed_kab');
        const pie_submit_level = document.getElementById('pie_submit_level');
        // console.log(ctx);

        // option legend
        const legend_donut = {
            legend: {
                position: 'bottom'
            }
        };

        const getBaseOptions = (judul) => {
            return {
                legend: {
                    position: 'bottom' // Standar legend Anda
                },
                title: {
                    display: true,
                    text: judul, // Judul akan berubah sesuai input
                    font: {
                        size: 16
                    }
                }
            }
        };

        // 2. Inisialisasi Chart.js
        const myChart = new Chart(ctx, {
            type: 'bar', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataStatusKab['labels'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                        label: <?= $dataStatusKab['datesets'][0]['label'] ?>,
                        data: <?= $dataStatusKab['datesets'][0]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#94C11F',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: <?= $dataStatusKab['datesets'][1]['label'] ?>,
                        data: <?= $dataStatusKab['datesets'][1]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#EE8911',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: <?= $dataStatusKab['datesets'][2]['label'] ?>,
                        data: <?= $dataStatusKab['datesets'][2]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#0369A1',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: "Status Dokumen Menurut Wilayah", // Judul akan berubah sesuai input
                        font: {
                            size: 16
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });

        const grafPersenNgibar = new Chart(persenNgibar, {
            type: 'doughnut', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataProgresNgibar['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                    data: <?= $dataProgresNgibar['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                    backgroundColor: ['#94C11F', '#EE8911', '#0369A1', '#B3B3B3'],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: getBaseOptions('Status Ngibar')
            }
        });
        const grafPersenAssigment = new Chart(persenAssigment, {
            type: 'doughnut', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataProgresFasih['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                    data: <?= $dataProgresFasih['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                    backgroundColor: ['#EE8911', '#0369A1', '#B3B3B3'],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: getBaseOptions('Status Dokumen')
            }
        });

        const grafPersenSubmit = new Chart(persenSubmit, {
            type: 'doughnut', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataProsesSubmit['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                    data: <?= $dataProsesSubmit['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                    backgroundColor: ['#EE8911', '#94C11F'],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: getBaseOptions('Usaha Ekonomi Digital')
            }
        });

        const grafTimeline = new Chart(timelineAnom, {
            type: 'line', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataTimeline['labels']; ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                        label: <?= $dataTimeline['datasets'][0]['label']; ?>,
                        data: <?= $dataTimeline['datasets'][0]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#EE8911',
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        fill: true
                    },
                    {
                        label: <?= $dataTimeline['datasets'][1]['label']; ?>,
                        data: <?= $dataTimeline['datasets'][1]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#94C11F',
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        fill: true
                    },
                    {
                        label: <?= $dataTimeline['datasets'][2]['label']; ?>,
                        data: <?= $dataTimeline['datasets'][2]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#0369A1',
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        fill: true
                    },
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: getBaseOptions('Timeline Anomali')
            }
        });
        const barPotensiEd = new Chart(bar_potensiEd_kab, {
            type: 'bar', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataPotensiKab['labels'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                        label: <?= $dataPotensiKab['datesets'][0]['label'] ?>,
                        data: <?= $dataPotensiKab['datesets'][0]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#94C11F',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: <?= $dataPotensiKab['datesets'][1]['label'] ?>,
                        data: <?= $dataPotensiKab['datesets'][1]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#EE8911',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: <?= $dataPotensiKab['datesets'][2]['label'] ?>,
                        data: <?= $dataPotensiKab['datesets'][2]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#0369A1',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: <?= $dataPotensiKab['datesets'][3]['label'] ?>,
                        data: <?= $dataPotensiKab['datesets'][3]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: '#B3B3B3',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: "Potensi Ekonomi Digital Menurut Wilayah", // Judul akan berubah sesuai input
                        font: {
                            size: 16
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });

        const pieSubmit = new Chart(pie_submit_level, {
            type: 'pie', // Tipe grafik (bar, line, pie)
            data: {
                // labels: ['Fasih Submit', 'Fasih Open', 'Submit ED', 'Submit NED', 'Submit Open', 'Potensi Submit', 'Potensi ED', 'Potensi NED', 'Potensi Open'], // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                        label: 'Level 1',
                        backgroundColor: ['#0369A1', '#B3B3B3'],
                        data: [70, 30],
                        customLabels: ['Fasih Submit', 'Fasih Open']
                    },
                    {
                        label: 'Level 2',
                        backgroundColor: ['#94C11F', '#6E9115', '#8C8C8C'],
                        data: [30, 40, 30],
                        customLabels: ['Submit ED', 'Submit NED', 'Fasih Open']
                    },
                    {
                        label: 'Level 3',
                        backgroundColor: ['#F4AD58', '#EE8911', '#B86A0D', '#B3B3B3'],
                        data: [30, 25, 15, 30],
                        customLabels: ['Potensi Submit', 'Potensi ED', 'Potensi NED', 'Fasih Open']
                    },
                    {
                        label: 'Level 4',
                        backgroundColor: ['#ffffff'],
                        data: [100],
                        customLabels: ['NULL']
                    },
                ]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            // Logika untuk menampilkan label yang benar saat di-hover
                            label: function(context) {
                                const dataset = context.dataset;
                                const index = context.dataIndex;
                                return dataset.customLabels[index] + ': ' + context.raw;
                            }
                        }
                    },
                    legend: {
                        display: true // Matikan legend default karena akan kacau
                    },
                    title: {
                        display: true,
                        text: "Persentase Potensi Ekonomi Digital", // Judul akan berubah sesuai input
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    })
</script>

<style>
    .chartBar {
        position: relative;
        /* Wajib bagi Chart.js */
        width: 100%;
        /* Lebar boleh 100% mengikuti card */
        height: 450px;
        /* KUNCI: Beri angka pasti, jangan 100% atau vh! */
    }

    /* Mengizinkan kontainer luar melebar/memanjang elastis mengikuti card-body */
    .chartBarContainer {
        position: relative;
        flex-grow: 1;
        /* Mengisi seluruh sisa ruang vertikal yang ada */
        width: 100%;
        min-height: 300px;
        /* Batas minimum di HP agar chart tidak terlalu gepeng */
    }

    /* KUNCI: Memaksa kontainer dalam pas dengan kontainer luar TANPA bisa mendorongnya */
    .chartBarInner {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .heigtMax {
        height: 100%;
    }

    #grafPersenAnom,
    #grafPersenAnomPublic,
    #grafPersenAnomNonPublic,
    .grafPersenAnomFlag {
        /* position: relative; */
        width: 15vw;
        /* kontrol tinggi */
        /* width: 100%; lebar mengikuti container */
    }

    #grafTimeline {
        /* width: 15vw; */
        height: 30vh;
    }
</style>

<?= $this->endSection(); ?>