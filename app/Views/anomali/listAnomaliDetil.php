<div id="listAnomIndividu" class="">
    <?php foreach ($listAnom as $l): ?>
        <div class="d-flex align-items-center row row-cols-4 row-cols-2 row-cols-md-4 mb-2">
            <div class="d-flex col-12 col-md-2 justify-content-center">
                <p class="btn btn-warning"><?= $l['kdAnom']; ?></p>
            </div>
            <div class=" col-12 col-md-4">
                <p class="lh-1 text-full-justify"><?= $l['detilAnom']; ?></p>
            </div>
            <div class="d-flex col-12 col-md-4">
                <textarea class="form-control" aria-label="With textarea"></textarea>
            </div>
            <div class="d-flex col-12 col-md-2 justify-content-end">
                <button type="button" class="btn btn-primary my-2">Save</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>