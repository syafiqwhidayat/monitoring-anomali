<div class="accordion modern-accordion w-100" id="accordionAnomali<?= $jenis; ?>_<?= rand(100, 999); ?>">
    <?php if (!$listAnom): ?>
        <div class="py-2 text-center text-muted small px-1">
            Tidak Ada Rincian Data Anomali.
        </div>
    <?php else: ?>
        <?php foreach ($listAnom as $d): ?>
            <div class="accordion-item" data-id="<?= $d['id']; ?>">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 px-2 px-md-3" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse_<?= $jenis; ?>_<?= $d['id']; ?>"
                        aria-expanded="false"
                        aria-controls="collapse_<?= $jenis; ?>_<?= $d['id']; ?>">

                        <div class="d-flex align-items-center justify-content-between w-100 pe-2 pe-md-3">
                            <div class="d-flex align-items-center gap-1 gap-md-3" style="min-width: 0;">
                                <span class="badge bg-blue-lt text-blue font-monospace px-1-5 py-1 rounded text-truncate"
                                    style="font-size: 0.725rem; max-width: 85px; display: inline-block; vertical-align: middle;"
                                    title="<?= esc($d['kd']); ?>">
                                    <?= esc($d['kd']); ?>
                                </span>

                                <span class="text-secondary fw-semibold text-start text-truncate d-inline-block"
                                    style="font-size: 0.775rem; max-width: 150px; vertical-align: middle; --bs-md-max-width: 100%;">
                                    <?= esc($d['nm']); ?>
                                </span>
                            </div>

                            <span class="badge bg-light text-muted border rounded-pill fw-bold px-1-5 py-0-5 badge-counter flex-shrink-0" style="font-size: 0.65rem;">
                                <?= number_format($d['jmlAnom'], 0, ',', '.'); ?> A
                            </span>
                        </div>
                    </button>
                </h2>

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
    <?php endif; ?>
</div>