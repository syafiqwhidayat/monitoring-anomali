<div id="listAnomIndividu" class="">
    <?php foreach ($listAnom as $l): ?>
        <form action="/anomali/updateKonfirmasi" method="POST">
            <div class="d-flex align-items-center row row-cols-4 row-cols-2 row-cols-md-4 mb-2">
                <div class="d-flex col-12 col-md-2 justify-content-center">
                    <p class="btn btn-warning"><?= $l['kdAnom']; ?></p>
                </div>
                <div class=" col-12 col-md-4">
                    <p class="lh-1 text-full-justify"><?= $l['detilAnom']; ?></p>
                </div>
                <div class="d-flex col-12 col-md-4">
                    <input name="id" type="hidden" value="<?= $l['id']; ?>">
                    <textarea
                        id="konfirmasi"
                        name="konfirmasi"
                        class="form-control text-end"
                        aria-label="With textarea"><?= (old('konfirmasi')) ? old('konfirmasi') : $l['konfirmasi']; ?></textarea>
                </div>
                <div class="d-flex col-12 col-md-2 justify-content-end">
                    <button type="submit" class="btn btn-primary my-2">Save</button>
                </div>
            </div>
        </form>
    <?php endforeach; ?>
</div>