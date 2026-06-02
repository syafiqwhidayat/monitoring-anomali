<div id="listAnomIndividu" class="w-100 pe-1">
    <?php foreach ($listAnom as $l): ?>
        <div class="card border-0 mb-3 bg-white shadow-xs p-3 p-md-4" style="border-radius: 14px; border: 1px solid #eaf0f6 !important;">

            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3 gap-2 flex-wrap">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-red-lt text-red font-monospace fw-bold px-2 py-1 rounded" style="font-size: 0.725rem;">
                        <?= esc($l['kdAnom']); ?>
                    </span>
                    <!-- <span class="text-muted font-monospace text-truncate d-inline-block" style="font-size: 0.75rem; max-width: 150px; @media(min-width:768px){max-width:100%;}" title="<?= esc($l['id']); ?>">
                        ID: <strong class="text-dark"><?= esc($l['id']); ?></strong>
                    </span> -->
                </div>
                <div id="feedback-<?= $l['id']; ?>" class="small fw-bold text-success" style="font-size: 0.75rem;"></div>
            </div>

            <form class="anomali-form-submit m-0">
                <input name="id" type="hidden" value="<?= $l['id']; ?>">

                <div class="row g-3">
                    <div class="col-12 col-lg-5 mb-1">
                        <label class="text-muted fw-semibold mb-1-5 d-block" style="font-size: 0.75rem;">Deskripsi Anomali Data</label>
                        <div class="p-3 rounded-3 h-100" style="background-color: #fdfaf6; border-left: 4px solid #f59e0b; box-shadow: inset 0 0 4px rgba(0,0,0,0.01);">
                            <p class="mb-0 text-dark" style="font-size: 0.825rem; text-align: justify; line-height: 1.5; word-break: break-word;">
                                <?= esc($l['detilAnom']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-8 col-lg-5">
                        <label class="text-muted fw-semibold mb-1-5 d-block" style="font-size: 0.75rem;" for="konfirmasi-<?= $l['id']; ?>">Catatan Konfirmasi</label>
                        <textarea
                            id="konfirmasi-<?= $l['id']; ?>"
                            name="konfirmasi"
                            placeholder="Tuliskan alasan / justifikasi konfirmasi kebenaran data di sini..."
                            rows="3"
                            style="font-size: 0.825rem; border-radius: 8px; border: 1px solid #cbd5e1; resize: vertical;"
                            class="form-control p-2-5 text-start bg-white focus-ring"
                            aria-label="Isi Catatan Konfirmasi"><?= (old('konfirmasi')) ? old('konfirmasi') : esc($l['konfirmasi']); ?></textarea>
                    </div>

                    <div class="col-12 col-md-4 col-lg-2 d-flex flex-column justify-content-between gap-2.5">
                        <div class="mb-1">
                            <label class="text-muted fw-semibold mb-1-5 d-block" style="font-size: 0.75rem;">Status Validasi</label>
                            <div class="d-flex align-items-center bg-light p-2-5 rounded-3 w-100 justify-content-start border border-light-subtle">
                                <label class="form-check form-switch m-0 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="kondisi_lapangan" value="1" <?= ($l['is_lap']) ? 'checked' : ''; ?>>
                                    <span class="form-check-label text-dark fw-bold ms-2" style="user-select: none; font-size: 0.775rem; white-space: nowrap;">
                                        Sesuai Lapangan
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mt-auto">
                            <button type="submit" id="submit-button" class="btn btn-emerald submit-button py-2 px-2 text-white fw-bold d-flex align-items-center justify-content-center gap-1 w-100 shadow-xs" style="border-radius: 8px; font-size: 0.8rem; background-color: #0ca678;" data-id="<?= $l['id']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                </svg>
                                Simpan Konfirmasi
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    <?php endforeach; ?>
</div>