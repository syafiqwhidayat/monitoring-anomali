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
                        <select name="fil-anomali" class="form-select" id="filter-anomali" placeholder="Cari kode anomali...">
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
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 text-nowrap" id="tombolFilterEdit">
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
    <!-- <div class="card card-body">
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
    </div> -->
    <div class="card card-stacked">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Bagian Kiri: Identitas Utama -->
                <div class="col-md-auto text-center mb-3 mb-md-0">
                    <div class="avatar avatar-xl rounded-pill bg-primary-lt mb-2" style="width: 80px; height: 80px; font-size: 1.5rem; font-weight: bold;">
                        <?= $kode_anomali; ?>
                    </div>
                    <div>
                        <span class="badge <?= ($is_public) ? 'bg-success-lt' : 'bg-warning-lt'; ?> px-2 py-1">
                            <i class="bi <?= ($is_public) ? 'bi-eye-fill' : 'bi-eye-slash-fill'; ?> me-1"></i>
                            <?= ($is_public) ? 'Public' : 'Internal Only'; ?>
                        </span>
                    </div>
                </div>

                <!-- Bagian Tengah: Judul dan Badge Flag -->
                <div class="col">
                    <div class="row">
                        <div class="d-flex align-items-center">
                            <h1 class="m-0 me-2">Statistik Anomali <?= $kode_anomali; ?></h1>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning text-warning-fg rounded-pill">Prioritas <?= $flag ?: "?"; ?></span>
                        </div>


                        <!-- Detail & Definisi (Ditambahkan) -->
                        <div class="text-muted mt-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-1 text-dark">Definisi Anomali</h4>
                                    <p class="text-secondary small">
                                        <!-- Isi dengan variabel definisi jika ada, atau teks statis -->
                                        <?= $definisi ?? "Belum ada Definisi Anomali"; ?>
                                    </p>
                                </div>
                                <div class="col-md-6 border-start">
                                    <h4 class="mb-1 text-dark">Detail Teknis</h4>
                                    <p class="text-primary small">
                                        <!-- Isi dengan variabel definisi jika ada, atau teks statis -->
                                        <?= $detil ?? "Belum ada Detil Anomali"; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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

            // menghitung weight untuk wordcloud
            const totalData = dbData.length;
            let dynamicWeightFactor = 5;
            if (totalData > 0 && totalData <= 5) {
                dynamicWeightFactor = 40; // Sangat sedikit data
            } else if (totalData > 5 && totalData <= 20) {
                dynamicWeightFactor = 15; // Data sedang
            } else {
                dynamicWeightFactor = 5; // Data banyak (default Anda)
            }

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
                    labels: <?= $data_proses_wilayah['labels'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                            label: <?= $data_proses_wilayah['datesets'][0]['label'] ?>,
                            data: <?= $data_proses_wilayah['datesets'][0]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
                            backgroundColor: '#94C11F',
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: <?= $data_proses_wilayah['datesets'][1]['label'] ?>,
                            data: <?= $data_proses_wilayah['datesets'][1]['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
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
                    labels: <?= $data_proses['label'] ?>, // Mengambil data Jan, Feb, Mar dari PHP
                    datasets: [{
                        data: <?= $data_proses['nilai'] ?>, // Mengambil data 10, 45, 30 dari PHP
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

            // 1. Ambil nilai maksimal dari data
            const maxVal = Math.max(...dbData.map(d => d[1]));
            // 2. Normalisasi data agar ukuran font masuk akal (misal 10px - 80px)
            const normalizedData = dbData.map(d => {
                // Rumus sederhana: (nilai / max) * ukuran_maksimal_yang_diinginkan
                // Ditambah 10 agar kata yang nilainya 1 tetap terbaca
                let size = (d[1] / maxVal) * 80 + 10;
                return [d[0], size];
            });
            const dpr = window.devicePixelRatio || 1;
            wordCld.width = container.offsetWidth * dpr;
            wordCld.height = container.offsetHeight * dpr;
            wordCld.style.width = container.offsetWidth + 'px';
            wordCld.style.height = container.offsetHeight + 'px';
            setTimeout(function() {
                WordCloud(wordCld, {
                    list: normalizedData,
                    fontFamily: 'Segoe UI, Arial, sans-serif',
                    fontWeight: 'bold',

                    // Warna: Menggunakan warna utama BPS dan variasi transparansinya
                    color: function(word, weight) {
                        return (weight > 20) ? '#0369A1' : '#38bdf8';
                    },

                    backgroundColor: '#ffffff',
                    gridSize: 15, // Jarak antar kata
                    weightFactor: 1, //dynamicWeightFactor, // Faktor pengali ukuran (sesuaikan jika kata terlalu kecil)
                    rotateRatio: 0.3, // Persentase kata yang tampil vertikal (0.3 = 30%)
                    rotationSteps: 2, // Sudut rotasi (hanya horizontal dan vertikal)
                    ellipticity: 0.65, // Membuat bentuk sebaran agak oval (lebih estetik)
                    shuffle: false, // Mengacak posisi setiap kali refresh
                    drawOutOfBound: false, // Jangan gambar kata jika melebihi canvas
                    shrinkToFit: true, // Otomatis mengecilkan kata jika tidak muat
                })
            }, 900)
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