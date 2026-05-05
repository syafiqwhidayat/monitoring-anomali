<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<!-- Hero Section: Penjelasan Singkat -->
<div class="py-5 bg-primary-lt border-bottom">
    <div class="container-xl">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 text-center text-lg-start">
                <h1 class="display-5 fw-bold mb-3">Monitoring Kualitas Data <br><span class="text-primary">Terintegrasi.</span></h1>
                <p class="lead text-secondary mb-4">
                    <strong>SIDIK ANOMALI</strong> adalah sistem untuk mengindetifikasi, mengkonfirmasi dan memastikan kualitas data BPS tetap terjaga.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-2">
                    <a href="/upload" class="btn btn-primary btn-lg px-4 rounded-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                            <path d="M7 9l5 -5l5 5" />
                            <path d="M12 4l0 12" />
                        </svg>
                        Upload Wilayah
                    </a>
                    <a href="#tentang" class="btn btn-white btn-lg px-4 rounded-pill">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="<?= base_url('img/logo.png') ?>" alt="Sidik Anomali" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<!-- Statistik Section -->
<div class="py-5">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="bg-blue text-white avatar avatar-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                            </span>
                            <div class="ms-3">
                                <div class="h2 fw-bold mb-0"><?= $total_petugas ?></div>
                                <div class="text-secondary">Petugas Terdaftar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-sm border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="bg-green text-white avatar avatar-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 11l3 3l8 -8" />
                                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                                </svg>
                            </span>
                            <div class="ms-3">
                                <div class="h2 fw-bold mb-0"><?= $total_kegiatan ?></div>
                                <div class="text-secondary">Kegiatan Terdaftar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-sm border-red-lt">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="bg-red text-white avatar avatar-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 9v4" />
                                    <path d="M12 17h.01" />
                                    <path d="M19.033 19h-14.066a1.967 1.967 0 0 1 -1.7 -2.95l7.033 -12a1.967 1.967 0 0 1 3.4 0l7.033 12a1.967 1.967 0 0 1 -1.7 2.95z" />
                                </svg>
                            </span>
                            <div class="ms-3">
                                <div class="h2 fw-bold mb-0"><?= $total_anomali ?></div>
                                <div class="text-secondary">Anomali Terdeteksi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="tentang" class="py-5 bg-white">
    <div class="container-xl">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Kenapa Menggunakan SIDIK?</h2>
            <p class="section-subtitle text-secondary">Tiga pilar utama dalam menjaga akurasi data lapangan.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="mb-3 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler-device-computer-camera" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M8 16l-2.091 3.486a1 1 0 0 0 .857 1.514h10.468a1 1 0 0 0 .857 -1.514l-2.091 -3.486" />
                    </svg>
                </div>
                <h3>Deteksi Cerdas</h3>
                <p class="text-secondary">Mengenali kesalahan input, duplikasi petugas, hingga ketidaksesuaian kode wilayah secara instan saat upload.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3 text-green">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler-users-group" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                        <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                        <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                    </svg>
                </div>
                <h3>Kolaborasi Tim</h3>
                <p class="text-secondary">Hubungkan PPL dan PML dalam satu wadah diskusi untuk menyelesaikan temuan anomali di lapangan.</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-3 text-red">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler-report-analytics" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                        <path d="M9 17v-5" />
                        <path d="M12 17v-1" />
                        <path d="M15 17v-3" />
                    </svg>
                </div>
                <h3>Log Transparan</h3>
                <p class="text-secondary">Setiap perubahan dan error terdokumentasi dengan rapi untuk keperluan audit kualitas data.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>