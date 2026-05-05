<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container" jenis-konf="0">
    <div class="card card-body">
        <div class="row">
            <div class="col">
                <h1><?= $title; ?></h1>
                <h4>Data Update : <span><?= $dateUpdated; ?></span></h4>
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
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Jumlah Submit : <?= $dataHead['total_submit']; ?></button>
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Jumlah Usaha Digital : <?= $dataHead['total_ED']; ?></button>
                        <button type="button" class="btn btn-primary-bps label-bps" disabled>Jumlah Usana Non Digital : <?= $dataHead['total_NED']; ?></button>
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
                    <div class="col-12">
                        <div class="d-flex">
                            <canvas id="grafPersenEkonomiDigital" aria-label="grafik_persen_anomali_public" role="img">
                                <p>Browser Kamu tidak Support Canvas Element</p>
                            </canvas>
                        </div>
                    </div>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Ambil konteks canvas
        const ctx = document.getElementById('grafikAnomali');
        const persenAssigment = document.getElementById('grafPersenAnom');
        const persenSubmit = document.getElementById('grafPersenEkonomiDigital');
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
                        backgroundColor: '#EE8911',
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: <?= $dataCharJmlAnom['datesets'][2]['label'] ?>,
                        data: <?= $dataCharJmlAnom['datesets'][2]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
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

        const grafPersenAssigment = new Chart(persenAssigment, {
            type: 'doughnut', // Tipe grafik (bar, line, pie)
            data: {
                labels: <?= $dataProses['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                datasets: [{
                    data: <?= $dataProses['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                    backgroundColor: ['#EE8911', '#0369A1', '#B3B3B3'],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: getBaseOptions('% Status Dokumen')
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
                plugins: getBaseOptions('% Usaha Ekonomi Digital')
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