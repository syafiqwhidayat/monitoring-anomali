<div class="accordion" id="accordionAnomali<?= $jenis; ?>">
    <?php foreach ($listAnom as $d): ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $d['id']; ?>" aria-expanded="false" aria-controls="collapse<?= $d['id']; ?>">
                    <?= $d['kd'] . ' ' . $d['nm'] . ' (' . $d['jmlAnom'] . ')'; ?>
                </button>
            </h2>
            <div id="collapse<?= $d['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionAnomali<?= $jenis; ?>">
                <div class="accordion-body data-load-container p-2">
                    <p class="fst-italic">Memuat data ...</p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>