<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <!-- Card Judul Halaman -->
    <div class="card card-body mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h2 m-0">Upload Data Peta & Titik Bangunan</h1>
            <a href="<?= base_url('peta'); ?>" class="btn btn-outline-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l14 0" />
                    <path d="M5 12l6 6" />
                    <path d="M5 12l6 -6" />
                </svg>
                Kembali ke Peta
            </a>
        </div>
    </div>

    <!-- Alert Error Flashdata -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('error'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Alert Sukses Flashdata -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('success'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <!-- Card 1: Form Upload CSV Titik Bangunan -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="hr-text hr-text-left fs-5 mb-3">1. Upload CSV Titik Bangunan</div>
                    <form action="<?= base_url('peta/upload-csv'); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label required">File CSV Titik</label>
                            <input type="file" name="file_csv" class="form-control" accept=".csv" required>
                            <div class="form-text text-muted small mt-2">
                                Data akan disimpan ke tabel <code>se_bangunan</code> (menimpa data lama).<br>
                                <strong>Header Kolom:</strong> <code>id_subsls, no_bang, geotag_latitude, geotag_longitude, jns_bangunan_value, nama_principal</code> (urutan bebas).
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M12 11v6" />
                                <path d="M9.5 13.5l2.5 -2.5l2.5 2.5" />
                            </svg>
                            Upload & Replace Titik Bangunan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 2: Form Upload GeoJSON Poligon SLS -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="hr-text hr-text-left fs-5 mb-3">2. Upload GeoJSON Poligon Sub SLS</div>
                    <form action="<?= base_url('peta/upload-geojson'); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label required">File GeoJSON (.json / .geojson)</label>
                            <input type="file" name="file_geojson" class="form-control" accept=".json,.geojson" required>
                            <div class="form-text text-muted small mt-2">
                                Data dipisah per Sub SLS dan disimpan ke <code>wilayah_geojson</code>.<br>
                                Pastikan atribut Feature memuat <code>properties.idsubsls</code> (16 Digit Kode).
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin-share" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path d="M12.02 17.514l-.02 .486l-4.244 -4.243a6 6 0 1 1 8.486 0" />
                                <path d="M16 22l5 -5" />
                                <path d="M21 21.5v-4.5h-4.5" />
                            </svg>
                            Upload & Split Poligon Sub SLS
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>