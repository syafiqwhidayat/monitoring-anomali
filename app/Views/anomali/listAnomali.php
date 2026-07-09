<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<style>
    /* Mengadopsi gaya modern minimalis */
    .modern-accordion .accordion-item {
        border: none;
        background: transparent;
        margin-bottom: 10px;
    }

    .modern-accordion .accordion-button {
        background-color: #ffffff;
        border: 1px solid #e6e8eb;
        border-radius: 10px !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.01);
        transition: all 0.2s ease;
    }

    .modern-accordion .accordion-button:not(.collapsed) {
        color: #206bc4;
        background-color: #ffffff;
        border-color: #206bc4;
        box-shadow: 0 4px 12px rgba(32, 107, 196, 0.05);
    }

    .modern-accordion .accordion-button::after {
        background-size: 0.75rem;
    }

    /* ======================================================= */
    /* PENGATURAN RESPONSIF INDENTASI (SOLUSI UTAMA HP BENTOK) */
    /* ======================================================= */

    /* DEFAULT UNTUK HP: Jarak masuk diperkecil drastis agar menghemat ruang horizontal */
    .modern-accordion .nested-body {
        padding: 6px 0 2px 8px;
        /* Hanya masuk 8px di HP */
        position: relative;
    }

    /* Garis vertikal di HP dibuat sangat tipis dan mepet ke kiri */
    .modern-accordion .nested-body::before {
        content: "";
        position: absolute;
        left: 2px;
        top: 0;
        bottom: 8px;
        width: 1.5px;
        background: #cbd5e1;
    }

    /* UNTUK DESKTOP (Layar Lebar): Jarak masuk dikembalikan normal */
    @media (min-width: 768px) {
        .modern-accordion .nested-body {
            padding: 12px 0 4px 26px;
            /* Masuk 26px di desktop */
        }

        .modern-accordion .nested-body::before {
            left: 10px;
            width: 2px;
        }
    }
</style>

<div class="container-xl py-4">
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 mb-1 text-dark fw-bold">Daftar Temuan Anomali</h1>
            <p class="text-muted small m-0">Gunakan filter untuk mempersempit ruang lingkup pemeriksaan data.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
        <div class="card-body p-3">
            <form action="<?= base_url('/anomali') ?>" method="get">
                <input type="hidden" name="isEdit" id="isEdit" value="<?= $isEdit; ?>">
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
                        <label class="form-label small text-muted fw-medium mb-1">Wilayah Kerja</label>
                        <select name="fil-wilayah" class="form-select bg-light border-0" id="filter-wilayah">
                            <?php foreach ($listWilayah as $l): ?>
                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterWilayah) ? 'selected' : ''; ?>>Wilayah <?= $l['id']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label small text-muted fw-medium mb-1">Kode Jenis Anomali</label>
                        <select name="fil-kategori" class="form-select bg-light border-0" id="filter-kategori">
                            <?php foreach ($listSelKdAnom as $l): ?>
                                <option value="<?= $l['id']; ?>" <?= ($l['id'] == $filterKategori) ? 'selected' : ''; ?>><?= $l['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label small text-muted fw-medium mb-1">Flag Prioritas</label>
                        <select name="fil-flag" class="form-select bg-light border-0" id="filter-flag">
                            <option value="">Semua Jenis</option>
                            <?php foreach ($listSelFlag as $l): ?>
                                <option value="<?= $l['value']; ?>" <?= ($l['value'] == $filterFlag) ? 'selected' : ''; ?>> Flag <?= $l['value']; ?></option>
                            <?php endforeach ?>
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

    <div class="row">
        <div class="col-12">
            <?php if (empty($listAnom)): ?>
                <div class="text-center py-5 bg-white rounded-3 border">
                    <p class="text-muted m-0 fs-3">🎉 Tidak ditemukan berkas anomali saat ini.</p>
                </div>
            <?php else: ?>
                <div class="accordion modern-accordion" id="accordionAnomaliKec">
                    <?php foreach ($listAnom as $d): ?>
                        <div class="accordion-item" data-id="<?= $d['id']; ?>">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $d['id']; ?>" aria-expanded="false" aria-controls="collapse<?= $d['id']; ?>">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100 pe-3 gap-1 gap-md-3">
                                        <span class="badge text-muted-inverse font-monospace px-2 py-1" style="font-size: 0.75rem;">
                                            <?= esc($d['kd']); ?>
                                        </span>
                                        <span class="text-dark fw-semibold fs-3"><?= esc($d['nm']); ?></span>
                                        <span class="badge bg-red-lt text-red ms-md-auto mt-1 mt-md-0 fw-bold px-2 py-1 badge-counter" style="font-size: 0.8rem;">
                                            <?= number_format($d['jmlAnom'], 0, ',', '.'); ?> Kasus
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse<?= $d['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionAnomaliKec">
                                <div class="accordion-body nested-body data-load-container">
                                    <div class="d-flex align-items-center py-2 text-muted small">
                                        <div class="spinner-border spinner-border-sm text-secondary me-2" role="status"></div>
                                        <span class="fst-italic">Memuat sub-level...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('js/scriptDaftarAnomali.js'); ?>"></script>
<?= $this->endSection(); ?>