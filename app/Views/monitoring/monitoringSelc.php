<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container" jenis-konf="0">
    <form action="<?= base_url('/monitoring-sel') ?>" method="get">
        <input type="hidden" name="fil-anomali-old" value="<?= $filterAnomali; ?>">
        <div class="card card-body mb-5 ">
            <div class="hr-text hr-text-left fs-5 mb-3">Filter Broadcast</div>
            <div class="mb-3">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Pilih Kode Anomali</label>
                        <select name="fil-anomali" class="form-select" id="filter-anomali">
                            <?php foreach ($listKodeAnom as $l): ?>
                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterAnomali) ? 'selected' : ''; ?>><?= $l['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kabupaten</label>
                        <select name="sel-kab" class="form-select">
                            <option value="">Semua Kabupaten</option>
                            <?php foreach ($list_kab ?? [] as $kab): ?>
                                <option value="<?= $kab['id']; ?>" <?= ($sel_kab == $kab['id']) ? 'selected' : ''; ?>><?= $kab['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" id="tombolFilterEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-4 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                            </svg>
                            Pilih Anomali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="card card-body">
        <div class="row">
            <div class="col mb-3">
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <h1 class="text-center">Statistik Anomali</h1>
                    <h1 class="btn btn-primary-bps rounded-pill"><?= $kode_anomali; ?></h1>
                </div>
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <div class="text-align-center" for="UpdateAll">
                        <i class="bi <?= ($is_public) ? 'bi-eye-fill text-success' : 'bi-eye-slash-fill text-warning'; ?>"></i> <?= ($is_public) ? '' : 'Not'; ?> Public
                    </div>
                    <i class="btn-warning-bps rounded-pill py-1 px-2">Flag <?= ($flag) ? $flag : "?"; ?></i>
                </div>
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
                    <div class="col">
                        <canvas id="grafTimeline" class="gefPersenFlag1" aria-label="grafik_timeline" role="img">
                            <p>Browser Kamu tidak Support Canvas Element</p>
                        </canvas>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div id="canvas-container" style="width: 100%; height: 400px;">
                            <canvas id="myWordCloud"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div class="row mt-5">
            <div class="col">
                <h1>Top 5 Konfirmasi Anomali Terbaru</h1>
                <div>
                    <button class="btn btn-warning-bps rounded-pill">AN01</button>
                    <h5>Definisi: Jenis Kelamin KK tidak sesuai dengan aturan sensus, Jenis Kelamin KK tidak sesuai dengan aturan sensus, Jenis Kelamin KK tidak sesuai dengan aturan sensus</h5>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode Wilayah</th>
                            <th scope="col">Kode Anomali</th>
                            <th scope="col">Detil Konfirmasi</th>
                            <th scope="col">Jawaban Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $nomorBaris = 1; ?>
                        <?php foreach ($dataTop5 as $dat): ?>
                            <tr>
                                <th scope="row"><?= $nomorBaris++; ?></th>
                                <th>
                                    <div class="d-flex justify-content-center">
                                        <button type=" button" class="btn btn-primary-bps rounded-pill"><?= $dat['id_wilayah']; ?></button>
                                    </div>
                                </th>
                                <th>
                                    <div class="d-flex justify-content-center">
                                        <button type=" button" class="btn btn-warning-bps rounded-pill"><?= $dat['kode_anomali']; ?></button>
                                    </div>
                                </th>
                                <td><?= $dat['detil_anomali']; ?></td>
                                <td><?= $dat['konfirmasi']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.5.0/chart.umd.min.js" integrity="sha512-Y51n9mtKTVBh3Jbx5pZSJNDDMyY+yGe77DGtBPzRlgsf/YLCh13kSZ3JmfHGzYFCmOndraf0sQgfM654b7dJ3w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.2.2/wordcloud2.min.js"></script>

    <script>
        // import {
        //     Colors
        // } from 'chart.js';
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Ambil konteks canvas
            const ctx = document.getElementById('grafikAnomali');
            const persenAnom = document.getElementById('grafPersenAnom');
            const timelineAnom = document.getElementById('grafTimeline');
            const wordCld = document.getElementById('myWordCloud');
            const container = document.getElementById('canvas-container');
            const dbData = <?php echo $dataWordCloud; ?>;
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
                    plugins: getBaseOptions('Timeline Anomali'),
                    scales: {
                        y: {
                            // beginAtZero: true,
                            ticks: {
                                // Cara paling efektif: Memaksa step antar angka adalah 1
                                stepSize: 1,
                            }
                        }
                    }
                }
            });

            wordCld.width = container.offsetWidth;
            wordCld.height = container.offsetHeight;
            WordCloud(wordCld, {
                list: dbData,
                fontFamily: 'Segoe UI, Arial, sans-serif',
                fontWeight: 'bold',

                // Warna: Menggunakan warna utama BPS dan variasi transparansinya
                color: function(word, weight) {
                    return (weight > 20) ? '#0369A1' : '#38bdf8';
                },

                backgroundColor: '#ffffff',
                gridSize: 10, // Jarak antar kata
                weightFactor: 5, // Faktor pengali ukuran (sesuaikan jika kata terlalu kecil)
                rotateRatio: 0.3, // Persentase kata yang tampil vertikal (0.3 = 30%)
                rotationSteps: 2, // Sudut rotasi (hanya horizontal dan vertikal)
                ellipticity: 0.65, // Membuat bentuk sebaran agak oval (lebih estetik)
                shuffle: true // Mengacak posisi setiap kali refresh
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

        #grafPersenAnom {
            /* position: relative; */
            width: 25vw;
            /* kontrol tinggi */
            /* width: 100%; lebar mengikuti container */
        }

        #grafTimeline {
            /* width: 15vw; */
            height: 30vh;
        }
    </style>

    <?= $this->endSection(); ?>