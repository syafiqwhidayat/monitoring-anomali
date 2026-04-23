<div class="accordion accordion<?= $jenis; ?>" id="accordionAnomali<?= $jenis; ?>">
    <?php if (!$listAnom): ?>
        <div class="accordion-item">
            <div class="p-2 container-<?= $jenis; ?>">
                <p class="text-center">Tidak Ada Anomali</p>
            </div>
        </div>
    <?php else:  ?>
        <?php foreach ($listAnom as $d): ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed text-end" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $d['id']; ?>" aria-expanded="false" aria-controls="collapse<?= $d['id']; ?>">
                        <?= $d['kd'] . ' ' . $d['nm'] . ' (' . $d['jmlAnom'] . ')'; ?>
                    </button>
                </h2>
                <div id="collapse<?= $d['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionAnomali<?= $jenis; ?>">
                    <div class="accordion-body data-load-container p-2 container-<?= $jenis; ?>">
                        <p class="fst-italic">Memuat data ...</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>