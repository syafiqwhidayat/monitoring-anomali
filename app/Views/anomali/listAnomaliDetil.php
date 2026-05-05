<div id="listAnomIndividu" class="container-Anom p-0">
    <?php foreach ($listAnom as $l): ?>
        <!-- <form action="/anomali/updateKonfirmasi" method="POST"> -->
        <form class="anomali-form-submit border-bottom py-3 px-1">
            <input name="id" type="hidden" value="<?= $l['id']; ?>">
            <div class="align-items-center row g-2">
                <!-- sisi kiri -->
                <div class="col-12 col-md-5">
                    <div class="" d-flex align-items-center mb-1">
                        <span class="badge bg-warning text-dark me-2" style="font-size: 0.7rem;"><?= $l['kdAnom']; ?></span>
                        <div id="feedback-<?= $l['id']; ?>" class="small fw-bold"></div>
                    </div>
                    <p class="mb-0 text-secondary" style="font-size: 0.85rem; text-align: justify; line-height: 1.2;"><?= $l['detilAnom']; ?></p>
                </div>
                <!-- sisi tengah -->
                <div class="col-9 col-md-5">
                    <textarea
                        id="konfirmasi"
                        name="konfirmasi"
                        placeholder="Tulis Konfirmasi..."
                        rows="2"
                        style="font-size: 0.85rem;"
                        class="form-control text-end"
                        aria-label="With textarea"><?= (old('konfirmasi')) ? old('konfirmasi') : $l['konfirmasi']; ?></textarea>
                </div>
                <!-- sisi kanan tombol aksi -->
                <div class="col-3 col-md-2 d-grid">
                    <button type="submit" id="submit-button" class="btn btn-primary btn-sm submit-button px-0" data-id="<?= $l['id']; ?>">
                        <i class="bi bi-save"></i> <span class="d-none d-md-inline">Save</span>
                    </button>
                </div>
        </form>

    <?php endforeach; ?>
</div>