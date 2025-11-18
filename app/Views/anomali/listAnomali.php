<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Daftar Anomali</h1>
        </div>
    </div>
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

<?= $this->endSection(); ?>