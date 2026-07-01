<?php
// Buat ID unik untuk pembungkus part ini agar tidak saling tabrakan di DOM bertingkat
$uniqueId = $jenis . '_' . rand(1000, 9999);
// Tentukan apakah level saat ini adalah target pembatasan data (Ruta / Entitas Usaha)
$isTargetKhusus = ($jenis === 'Ruta' || $jenis === 'Anom');
?>

<div class="part-accordion-wrapper w-100" id="wrapper_<?= $uniqueId; ?>">

    <?php if (!$listAnom): ?>
        <div class="py-2 text-center text-muted small px-1">
            Tidak Ada Rincian Data Anomali.
        </div>
    <?php else: ?>
        <?php if ($isTargetKhusus): ?>
            <div class="mb-2 px-1 search-part-container">
                <input type="text"
                    class="form-control form-control-sm search-part-input"
                    placeholder="Cari Nama / Kode di sub-level ini..."
                    style="font-size: 0.75rem; border-radius: 6px;">
            </div>
        <?php endif; ?>

        <div class="accordion modern-accordion w-100 main-part-accordion" id="accordionAnomali<?= $uniqueId; ?>">
            <?php foreach ($listAnom as $d): ?>
                <div class="accordion-item search-target-item mb-2 rounded" data-id="<?= $d['id']; ?>">
                    <h2 class="accordion-header m-0 p-0">
                        <div class="d-flex align-items-stretch w-100">
                            <?php if ($jenis === 'Ruta' && session("isOrganik")): ?>
                                <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($d['kd']); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-primary d-flex align-items-center justify-content-center px-3 border-0 rounded-pill flex-shrink-0"
                                    style="font-size: 0.75rem; font-weight: 600; text-decoration: none; z-index: 2;"
                                    title="Buka detail assignment di Fasih">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link me-1-5 d-none d-md-inline" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                        <path d="M11 13l9 -9" />
                                        <path d="M15 4h5v5" />
                                    </svg>
                                    Fasih
                                </a>
                            <?php endif; ?>
                            <button class="accordion-button collapsed py-2 px-2 px-md-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse_<?= $jenis; ?>_<?= $d['id']; ?>"
                                aria-expanded="false"
                                aria-controls="collapse_<?= $jenis; ?>_<?= $d['id']; ?>">

                                <div class="d-flex align-items-center justify-content-between w-100 pe-2 pe-md-3">
                                    <div class="d-flex align-items-center gap-1 gap-md-3" style="min-width: 0;">
                                        <span class="badge bg-blue-lt text-blue font-monospace px-1-5 py-1 rounded text-truncate match-badge"
                                            style="font-size: 0.725rem; max-width: 85px; display: inline-block; vertical-align: middle;"
                                            title="<?= esc($d['kd']); ?>">
                                            <?= esc($d['kd']); ?>
                                        </span>

                                        <span class="text-secondary fw-semibold text-start text-truncate d-inline-block match-name"
                                            style="font-size: 0.775rem; max-width: 150px; vertical-align: middle; --bs-md-max-width: 100%;">
                                            <?= esc($d['nm']); ?>
                                        </span>
                                    </div>

                                    <span class="badge bg-light text-muted border rounded-pill fw-bold px-1-5 py-0-5 badge-counter flex-shrink-0" style="font-size: 0.65rem;">
                                        <?= number_format($d['jmlAnom'], 0, ',', '.'); ?> A
                                    </span>
                                </div>
                            </button>
                        </div>
                    </h2>

                    <!-- <h2 class="accordion-header position-relative">
                        <div class="d-flex align-items-center w-100">

                            <button class="accordion-button collapsed py-2 px-2 px-md-3" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse_<?= $jenis; ?>_<?= $d['id']; ?>"
                                aria-expanded="false"
                                aria-controls="collapse_<?= $jenis; ?>_<?= $d['id']; ?>"
                                style="padding-right: 140px !important;">
                                <div class="d-flex align-items-center gap-1 gap-md-3" style="min-width: 0;">
                                    <span class="badge bg-blue-lt text-blue font-monospace px-1-5 py-1 rounded text-truncate match-badge"
                                        style="font-size: 0.725rem; max-width: 85px; display: inline-block; vertical-align: middle;"
                                        title="<?= esc($d['kd']); ?>">
                                        <?= esc($d['kd']); ?>
                                    </span>

                                    <span class="text-secondary fw-semibold text-start text-truncate d-inline-block match-name"
                                        style="font-size: 0.775rem; max-width: 150px; vertical-align: middle; --bs-md-max-width: 100%;">
                                        <?= esc($d['nm']); ?>
                                    </span>
                                </div>
                            </button>

                            <div class="d-flex align-items-center gap-2 position-absolute" style="right: 40px; z-index: 10; pointer-events: auto;">

                                <?php if ($jenis === 'Ruta'): ?>
                                    <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($d['kd']); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        onclick="event.stopPropagation();"
                                        class="badge bg-blue-lt text-blue font-monospace px-1-5 py-1 rounded text-truncate match-badge"
                                        style="font-size: 0.725rem; max-width: 85px; display: inline-block; vertical-align: middle; text-decoration: none;"
                                        title="<?= esc($d['kd']); ?>">
                                        Fasih
                                    </a>
                                <?php endif; ?>

                                <span class="badge bg-light text-muted border rounded-pill fw-bold px-1-5 py-0-5 badge-counter" style="font-size: 0.65rem;">
                                    <?= number_format($d['jmlAnom'], 0, ',', '.'); ?> A
                                </span>
                            </div>

                        </div>
                    </h2> -->

                    <div id="collapse_<?= $jenis; ?>_<?= $d['id']; ?>" class="accordion-collapse collapse">
                        <div class="accordion-body nested-body data-load-container container-<?= $jenis; ?>">
                            <div class="d-flex align-items-center py-2 text-muted small px-1">
                                <div class="spinner-border spinner-border-sm text-muted me-2" role="status"></div>
                                <span class="fst-italic small">Memuat...</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($isTargetKhusus): ?>
            <div class="d-flex align-items-center justify-content-between mt-2 px-1 part-pagination-controls">
                <span class="text-muted style-info" style="font-size: 0.7rem;">
                    Melihat <span class="txt-range fw-bold">0-0</span> dari <span class="txt-total fw-bold">0</span> data
                </span>
                <div class="btn-group">
                    <button type="button" class="btn btn-white btn-sm py-0 px-2 btn-part-prev" style="font-size: 0.7rem;">‹ Prev</button>
                    <button type="button" class="btn btn-white btn-sm py-0 px-2 btn-part-next" style="font-size: 0.7rem;">Next ›</button>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>