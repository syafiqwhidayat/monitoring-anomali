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
    <div class="card card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="chartJumlahAnomali">
                    <canvas id="grafikAnomali" aria-label="grafik_jumlah_anomali" role="img">
                        <p>Browser Kamu tidak Support Canvas Element</p>
                    </canvas>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col d-flex flex-column align-items-center gap-1">
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Total Anomali: <?= $dataHead['total seluruh']; ?></button>
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Total Public: <?= $dataHead['total public']; ?></button>
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Total Non Public: <?= $dataHead['total non public']; ?></button>
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
                        <div class="col">
                            <canvas id="grafTimeline" class="gefPersenFlag1" aria-label="grafik_timeline" role="img">
                                <p>Browser Kamu tidak Support Canvas Element</p>
                            </canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div class="row mt-5">
            <h1>Top 5 Konfirmasi Anomali Terbaru</h1>
            <table class="table">
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
    .chartJumlahAnomali {
        /* position: relative; */
        height: 100%;
        /* kontrol tinggi */
        /* width: 100%; lebar mengikuti container */
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