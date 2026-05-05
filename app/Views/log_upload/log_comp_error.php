<div>
    <?php if (empty($errors)): ?>
        <div class="text-center p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 12l5 5l10 -10" />
            </svg>
            <p class="text-secondary">Tidak ada rincian kesalahan untuk ID ini atau proses berhasil 100%.</p>
        </div>';
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th class="w-1">Baris</th>
                        <th>Nama/Data</th>
                        <th>Keterangan Error</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($errors as $err): ?>
                        <?php
                        $pesanTampil = '';
                        if (is_array($err['messages'])) {
                            $pesanTampil = implode(', ', $err['messages']);
                        } else {
                            $pesanTampil = $err['messages'];
                        }
                        ?>
                        <tr>
                            <td><span class="badge bg-red-lt">' <?= $err['baris']; ?> '</span></td>
                            <td class="small fw-bold text-uppercase">' <?= $err['data']; ?> '</td>
                            <td class="text-danger small">' <?= $pesanTampil; ?> '</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>