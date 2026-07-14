<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container" jenis-konf="0">
    <div class="card card-body">
        <div class="row">
            <div class="col">
                <h1>Statistik Seluruh Anomali</h1>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
        <div class="card-body p-3">
            <form action="<?= base_url('/monitoring') ?>" method="get">
                <div class="row g-2 align-items-end">
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label small text-muted fw-medium mb-1">Level Anomali</label>
                        <select name="fil-level" class="form-select bg-light border-0" id="filter-level">
                            <?php foreach ($listLevel as $l): ?>
                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterLevel) ? 'selected' : ''; ?>>Anomali <?= ($l['id'] == null) ? "Semua" : $l['id']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label small text-muted fw-medium mb-1">Status Anomali</label>
                        <select name="fil-stat" class="form-select bg-light border-0" id="filter-stat">
                            <option value=''>Semua Status</option>
                            <?php foreach ($listStatus as $l): ?>
                                <option value="<?= $l['value']; ?>" <?= ($l['value'] == $filterStatus) ? 'selected' : ''; ?>><?= $l['nama']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-primary px-3 shadow-sm" id="tombolFilterEdit">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card card-body">
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <div class="chartJumlahAnomali">
                    <canvas id="grafikAnomali" aria-label="grafik_jumlah_anomali" role="img">
                        <p>Browser Kamu tidak Support Canvas Element</p>
                    </canvas>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col d-flex flex-column align-items-center gap-1">
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Total Anomali: <?= number_format($dataHead['total seluruh'], 0, ',', '.'); ?></button>
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Total Public: <?= number_format($dataHead['total public'], 0, ',', '.'); ?></button>
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Total Non Public: <?= number_format($dataHead['total non public'], 0, ',', '.'); ?></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="d-flex">
                            <canvas id="grafPersenAnom" aria-label="grafik_persen_anomali" role="img">
                                <p>Browser Kamu tidak Support Canvas Element</p>
                            </canvas>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex">
                            <canvas id="grafPersenAnomPublic" aria-label="grafik_persen_anomali_public" role="img">
                                <p>Browser Kamu tidak Support Canvas Element</p>
                            </canvas>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <canvas id="grafPersenAnomNonPublic" aria-label="grafik_persen_anomali_non_public" role="img">
                                <p>Browser Kamu tidak Support Canvas Element</p>
                            </canvas>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <canvas id="grafPersenAnomFlag1" class="gefPersenFlag" aria-label="grafik_persen_anomali_flag1" role="img">
                            <p>Browser Kamu tidak Support Canvas Element</p>
                        </canvas>
                    </div>
                    <div class="col-4">
                        <canvas id="grafPersenAnomFlag2" class="gefPersenFlag" aria-label="grafik_persen_anomali_flag2" role="img">
                            <p>Browser Kamu tidak Support Canvas Element</p>
                        </canvas>
                    </div>
                    <div class="col-4">
                        <canvas id="grafPersenAnomFlag3" class="gefPersenFlag" aria-label="grafik_persen_anomali_flag3" role="img">
                            <p>Browser Kamu tidak Support Canvas Element</p>
                        </canvas>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="chart-container-timeline">
                                <canvas id="grafTimeline" aria-label="grafik_timeline" role="img">
                                    <p>Browser Kamu tidak Support Canvas Element</p>
                                </canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div class="row">
            <div class="col">
                <h1 class="mb-3">Top 5 Konfirmasi Anomali Terbaru</h1>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode Wilayah</th>
                                <th scope="col">Jenis Anomali</th>
                                <th scope="col">Detil Anomali</th>
                                <th scope="col">Jawaban Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $nomorBaris = 1; ?>
                            <?php foreach ($dataTop5 as $dat): ?>
                                <tr>
                                    <th scope="row"><?= $nomorBaris++; ?></th>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button type=" button" class="btn btn-primary-bps rounded-pill"><?= $dat['id_wilayah']; ?></button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button type=" button" class="btn btn-warning-bps rounded-pill"><?= $dat['kode_anomali']; ?></button>
                                        </div>
                                    </td>
                                    <td><?= $dat['detil_anomali']; ?></td>
                                    <td><?= $dat['konfirmasi']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Ambil konteks canvas
            const ctx = document.getElementById('grafikAnomali');
            const persenAnom = document.getElementById('grafPersenAnom');
            const persenAnomPublic = document.getElementById('grafPersenAnomPublic');
            const persenAnomNonPublic = document.getElementById('grafPersenAnomNonPublic');
            const persenAnomNonFlag1 = document.getElementById('grafPersenAnomFlag1');
            const persenAnomNonFlag2 = document.getElementById('grafPersenAnomFlag2');
            const persenAnomNonFlag3 = document.getElementById('grafPersenAnomFlag3');
            const timelineAnom = document.getElementById('grafTimeline');
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
                    labels: <?= $dataCharJmlAnom['labels'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                            label: <?= $dataCharJmlAnom['datesets'][0]['label'] ?>,
                            data: <?= $dataCharJmlAnom['datesets'][0]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                            backgroundColor: '#94C11F',
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: <?= $dataCharJmlAnom['datesets'][1]['label'] ?>,
                            data: <?= $dataCharJmlAnom['datesets'][1]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                            backgroundColor: '#0369A1',
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 1
                        }
                    ]
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
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });

            const grafPersenAnom = new Chart(persenAnom, {
                type: 'doughnut', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataProses['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $dataProses['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: ['#0369A1', '#B3B3B3'],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Persentase Anomali')
                }
            });

            const grafPersenAnomPublic = new Chart(persenAnomPublic, {
                type: 'doughnut', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataProsesPublic['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $dataProsesPublic['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: ['#94C11F', '#B3B3B3'],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Persentase Anomali Public')
                }
            });

            const grafPersenAnomNonPublic = new Chart(persenAnomNonPublic, {
                type: 'doughnut', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataProsesNonPublic['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $dataProsesNonPublic['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: ['#94C11F', '#B3B3B3'],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Persentase Anomali Non Public')
                }
            });

            const grafPersenAnomFlag1 = new Chart(persenAnomNonFlag1, {
                type: 'doughnut', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataProsesFlag1['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $dataProsesFlag1['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: ['#EE8911', '#B3B3B3'],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Persentase Anomali Flag1')
                }
            });

            const grafPersenAnomFlag2 = new Chart(persenAnomNonFlag2, {
                type: 'doughnut', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataProsesFlag2['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $dataProsesFlag2['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: ['#EE8911', '#B3B3B3'],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Persentase Anomali Flag2')
                }
            });

            const grafPersenAnomFlag3 = new Chart(persenAnomNonFlag3, {
                type: 'doughnut', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataProsesFlag3['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $dataProsesFlag3['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                        backgroundColor: ['#EE8911', '#B3B3B3'],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    // indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Persentase Anomali Flag3')
                }
            });

            const grafTimeline = new Chart(timelineAnom, {
                type: 'line', // Tipe grafik (bar, line, pie)
                data: {
                    labels: <?= $dataTimeline['labels']; ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                            label: <?= $dataTimeline['datasets'][1]['label']; ?>,
                            data: <?= $dataTimeline['datasets'][1]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                            backgroundColor: '#94C11F',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            fill: true
                        },
                        {
                            label: <?= $dataTimeline['datasets'][0]['label']; ?>,
                            data: <?= $dataTimeline['datasets'][0]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                            backgroundColor: '#0369A1',
                            borderColor: '#ffffff',
                            borderWidth: 1,
                            fill: true
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: getBaseOptions('Timeline Anomali')
                }
            });
        })
    </script>

    <style>
        /* Pembungkus grafik Donut/Pie */
        .chart-container-donut {
            position: relative;
            width: 100%;
            /* Di HP tingginya dibuat 250px agar proporsional, di PC bisa mengikuti */
            height: 250px;
        }

        /* Pembungkus grafik Timeline */
        .chart-container-timeline {
            position: relative;
            width: 100%;
            min-height: 50vh;
            /* Minimal 1/4 layar */
            max-height: 50vh;
            /* Maksimal 1/4 layar */
        }

        /* Pastikan canvas memaksa mengisi pembungkusnya */
        .chart-container-donut canvas,
        .chart-container-timeline canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .chartJumlahAnomali {
            position: relative;
            width: 100%;
            min-height: 50vh;
            /* Minimal setengah layar HP */
            max-height: 100vh;
            /* Maksimal 1 layar penuh HP */
        }

        .chartJumlahAnomali canvas,
        .chart-container-timeline canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* * OPSI TAMBAHAN: Jika di monitor PC/Laptop tingginya dirasa terlalu besar, */
        /* Anda bisa membatasinya khusus untuk layar komputer saja */
        @media (min-width: 768px) {
            */ .chartJumlahAnomali {
                min-height: 400px;
                max-height: 500px;
            }

            .chart-container-timeline {
                min-height: 300px;
                max-height: 300px;
            }
        }
    </style>

    <?= $this->endSection(); ?>