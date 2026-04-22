<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container" jenis-konf="0">
    <div class="card card-body">
        <h1>Daftar Anomali</h1>
    </div>
    <div class="card card-body">
        <!-- <div class="card-body"> -->
        <div class="hr-text hr-text-left fs-5 mb-3">Filter Anomali</div>
        <div class="mb-3">
            <form action="<?= base_url('anomali/filter') ?>" method="get">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Kode Anomali</label>
                        <select name="selected-kode-anomali" class="form-select" id="filterKode">
                            <option value="">Semua Anomali</option>
                            <option value="1">AN01</option>
                            <option value="2">AN02</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Flag Prioritas</label>
                        <select name="selected-flag" class="form-select" id="filterFlag">
                            <option value="">Semua Jenis</option>
                            <option value="1">Flag 1</option>
                            <option value="2">Flag 1</option>
                            <option value="3">Flag 3</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" id="tombolFilter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-4 2v-8.5l-4.414 -4.414a2 2 0 0 1 -.586 -1.414v-2.172z" />
                            </svg>
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col d-flex justify-content-end">
            <a class="btn btn-primary" aria-current="page" href="<?= base_url('/anomali/listEdit'); ?>">Edit Konfirmasi</a>
        </div>
    </div> -->
    <div class="card card-body">
        <div class="hr-text hr-text-left fs-5 mb-3">Daftar Anomali</div>
        <div class="row">
            <div class="col">
                <?php if (!$listAnom): ?>
                    <div class="alert alert-success" role="alert">
                        Tidak ada anomali
                    </div>
                <?php else:  ?>
                    <div class="accordion" id="accordionAnomaliKec">
                        <?php foreach ($listAnom as $d): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $d['id']; ?>" aria-expanded="false" aria-controls="collapse<?= $d['id']; ?>">
                                        <?= $d['kd'] . ' ' . $d['nmKec'] . ' (' . $d['jmlAnom'] . ')'; ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $d['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionAnomaliKec">
                                    <div class="accordion-body data-load-container p-2">
                                        <p class="fst-italic">Memuat data ...</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('js/scriptDaftarAnomali.js'); ?>"></script>
<?= $this->endSection(); ?>