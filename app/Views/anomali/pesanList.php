<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4 mt-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item text-orange fw-bold">Daftar Anomali yang sudah dijawab dan bukan kondisi lapangan namun masih muncul sebagai anomali. Kemungkinan belum diperbaiki di FASIH.</li>
    </ol>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Alert Notifikasi Flashdata -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <!-- Card Filter Utama -->
    <div class="card border-primary mb-3 shadow-sm">
        <div class="card-header bg-primary p-0" id="headingFilter">
            <h2 class="mb-0 w-100">
                <button class="btn bg-primary text-white w-100 d-flex justify-content-between align-items-center p-3 border-0 rounded-top"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFilter"
                    aria-expanded="false"
                    aria-controls="collapseFilter">
                    <span class="fw-bold text-white">
                        <i class="fas fa-filter me-2"></i>Filter Konfirmasi Fasih
                    </span>
                    <i class="fas fa-chevron-down text-white icon-collapse"></i>
                </button>
            </h2>
        </div>

        <!-- Target Collapse -->
        <div id="collapseFilter" class="collapse" aria-labelledby="headingFilter">
            <div class="card-body">

                <!-- FORM FILTER -->
                <form method="GET" action="<?= current_url(); ?>" class="row g-3">
                    <!-- Filter Level Anomali -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Level Anomali</label>
                        <select name="fil-level" class="form-select form-control">
                            <?php foreach ($listLevel as $lvl): ?>
                                <option value="<?= $lvl['id']; ?>" <?= ($filterLevel == $lvl['id']) ? 'selected' : ''; ?>>
                                    <?= empty($lvl['id']) ? $lvl['nama'] : 'Level ' . $lvl['id']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Jenis / Kode Anomali -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Jenis Anomali</label>
                        <select name="fil-kategori" class="form-select form-control">
                            <?php foreach ($listSelKdAnom as $anom): ?>
                                <option value="<?= $anom['id']; ?>" <?= ($filterKategori == $anom['id']) ? 'selected' : ''; ?>>
                                    <?= empty($anom['id']) ? $anom['nama'] : $anom['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Flag Prioritas -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Flag Prioritas</label>
                        <select name="fil-flag" class="form-select form-control">
                            <?php foreach ($listSelFlag as $flg): ?>
                                <option value="<?= $flg['value']; ?>" <?= ($filterFlag == $flg['value']) ? 'selected' : ''; ?>>
                                    <?= empty($flg['value']) ? $flg['nama'] : 'Flag ' . $flg['value']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Wilayah Kerja -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Wilayah Kerja</label>
                        <select name="fil-wilayah" class="form-select form-control" <?= $isKunciWilayah ? 'disabled' : ''; ?>>
                            <option value="">-- Semua Wilayah --</option>
                            <?php foreach ($listWilayah as $wil): ?>
                                <option value="<?= $wil['id']; ?>" <?= ($filterWilayah == $wil['id']) ? 'selected' : ''; ?>>
                                    <?= $wil['id']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($isKunciWilayah): ?>
                            <input type="hidden" name="fil-wilayah" value="<?= $filterWilayah; ?>">
                        <?php endif; ?>
                    </div>

                    <!-- Filter Sub SLS -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Sub SLS</label>
                        <select name="fil-subsls" class="form-select form-control">
                            <option value="">-- Semua Sub SLS --</option>
                            <?php foreach ($listSubSls as $sub): ?>
                                <option value="<?= $sub['id_wilayah']; ?>" <?= ($filterSubSls == $sub['id_wilayah']) ? 'selected' : ''; ?>>
                                    [<?= esc($sub['id_wilayah']); ?>] <?= esc($sub['nm_sls']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kondisi Lapangan -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Kondisi Lapangan?</label>
                        <select name="fil-lap" class="form-select form-control">
                            <option value="0" <?= ($filterIsLap === '0') ? 'selected' : ''; ?>>Perbaikan Fasih</option>
                            <option value="1" <?= ($filterIsLap === '1') ? 'selected' : ''; ?>>Kondisi Lapangan</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-50">Filter Data</button>
                        <a href="<?= current_url(); ?>" class="btn btn-secondary w-50">Reset</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <div>
                <i class="fas fa-exclamation-triangle text-warning me-1"></i> Total Anomali Memerlukan Perhatian Fasih
            </div>
            <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['export' => 'excel'])); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-file-excel me-1"></i> Download ke Excel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="3%">No</th>
                            <th width="10%">Wilayah</th>
                            <th>Identitas Objek (KRT/ART)</th>
                            <th width="10%">Petugas (PPL / PML)</th>
                            <th width="10%">Kode Anom</th>
                            <th>Deskripsi Aturan / Rules</th>
                            <th width="25%" class="bg-warning text-dark">Konfirmasi Petugas Sebelumnya</th>
                            <th width="10%">Waktu Chat</th>
                            <th width="6%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listAnom) && count($listAnom) > 0): ?>
                            <?php
                            $page = isset($_GET['page_group_assignment']) ? (int)$_GET['page_group_assignment'] : 1;
                            $no   = 1 + (($page - 1) * 15);
                            $currentUserEmail = session('email') ?? (function_exists('auth') && auth()->user() ? auth()->user()->email : '');

                            foreach ($listAnom as $row):
                                // Parse JSON Data Chat
                                $chatData = !empty($row['chat']) ? json_decode($row['chat'], true) : [];
                                $hasChat  = is_array($chatData) && count($chatData) > 0;

                                // Ambil pesan chat terakhir
                                $lastChat = $hasChat ? end($chatData) : null;
                                $lastChatTime   = $lastChat && isset($lastChat['waktu']) ? $lastChat['waktu'] : null;
                                $lastChatSender = $lastChat && isset($lastChat['email']) ? $lastChat['email'] : null;

                                // Kondisi Tampil Titik Oren: Ada chat DAN pengirim terakhir BUKAN user yang login saat ini
                                $showOrangeDot = $hasChat && ($lastChatSender !== $currentUserEmail);
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center" style="font-family: monospace; font-size: 13px;">
                                        `<?= $row['nm_sls']; ?>
                                        `<?= $row['id_wilayah']; ?>
                                        <?php if (session('isOrganik')): ?>
                                            <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($row['kd_krt']); ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="badge bg-blue-lt text-blue px-1-5 py-0-5 rounded text-decoration-none"
                                                style="font-size: 0.65rem; max-width: 80px; font-family: var(--bs-font-sans-serif);"
                                                title="Buka Fasih untuk wilayah <?= esc($row['kd_krt']); ?>">
                                                ke Fasih-SM
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= $row['nm_krt'] ?? $row['nm_art'] ?? $row['nm_nrt'] ?? '-'; ?></strong>/
                                        <strong><?= $row['nm_art'] ?? $row['nm_art'] ?? $row['nm_art'] ?? '-'; ?></strong>
                                    </td>
                                    <td>
                                        <small class="d-block"><strong>PPL:</strong> <?= esc($row['nm_ppl'] ?? '-'); ?></small>
                                        <small class="d-block text-muted"><strong>PML:</strong> <?= esc($row['nm_pml'] ?? '-'); ?></small>
                                    </td>
                                    <td class="text-center fw-bold text-danger"><?= $row['kode_anomali']; ?></td>
                                    <td><small><?= $row['detil_anomali']; ?></small></td>
                                    <td class="bg-light text-green" style="font-style: italic; font-weight: 500; font-size: 13px;">
                                        <?= esc($row['konfirmasi']); ?>
                                    </td>
                                    <!-- Menampilkan Waktu Chat Terakhir -->
                                    <td class="text-center">
                                        <small><?= !empty($lastChatTime) ? date('d/m/Y H:i', strtotime($lastChatTime)) : '-'; ?></small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column gap-1">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-warning btn-open-edit-konfirmasi"
                                                data-id="<?= $row['id_anomali']; ?>"
                                                data-konfirmasi="<?= esc($row['konfirmasi']); ?>"
                                                title="Edit Konfirmasi">
                                                <i class="fas fa-edit"></i> Edit Konfirmasi
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm <?= ($row['is_lap'] == 1) ? 'btn-outline-blue' : 'btn-outline-success'; ?> btn-toggle-lap"
                                                data-id="<?= $row['id_anomali']; ?>"
                                                title="Klik untuk mengubah status Kondisi Lapangan">
                                                <i class="fas fa-sync-alt me-1"></i>
                                                <?= ($row['is_lap'] == 1) ? 'Perbaikan Fasih' : 'Kondisi Lap'; ?>
                                            </button>

                                            <div class="d-flex gap-1 justify-content-center">
                                                <!-- Tombol Check (Flag) -->
                                                <?php if (session('isOrganik')): ?>
                                                    <button type="button"
                                                        class="btn btn-sm <?= (!empty($row['is_check']) && $row['is_check'] == 1) ? 'btn-success' : 'btn-outline-secondary'; ?> btn-toggle-check"
                                                        data-id="<?= $row['id_anomali']; ?>"
                                                        title="<?= (!empty($row['is_check']) && $row['is_check'] == 1) ? 'Sudah Dicek' : 'Tandai Sudah Dicek'; ?>">
                                                        <i class="fas <?= (!empty($row['is_check']) && $row['is_check'] == 1) ? 'fa-check-circle' : 'fa-check'; ?>"></i>
                                                    </button>
                                                <?php endif; ?>

                                                <!-- Tombol Chat Modal -->
                                                <div class="btn-chat-container">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary btn-open-chat"
                                                        data-id="<?= $row['id_anomali']; ?>"
                                                        title="Buka Chat Diskusi">
                                                        <i class="fas fa-comments"></i>
                                                    </button>

                                                    <!-- Titik Oren hanya jika pengirim terakhir bukan user yang sedang login -->
                                                    <?php if ($showOrangeDot): ?>
                                                        <span class="chat-badge-dot" title="Ada pesan baru dari pengguna lain"></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                    Hebat! Tidak ditemukan anomali yang belum diperbaiki di fasih.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($pager)): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?= $pager; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Chat -->
<div class="modal fade" id="modalChat" tabindex="-1" aria-labelledby="modalChatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalChatLabel"><i class="fas fa-comments me-2"></i> Diskusi Anomali</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="mb-2 pb-2 border-bottom">
                    <small class="text-muted d-block">Login sebagai:</small>
                    <strong id="chatUserEmail" class="text-primary"></strong>
                </div>

                <!-- Box Chat Scrollable -->
                <div id="chatContainer" style="height: 300px; overflow-y: auto;" class="d-flex flex-column gap-2 p-2">
                    <!-- Bubble Chat Direset & Diisi Via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <form id="formSendChat" class="w-100">
                    <input type="hidden" id="chatIdAnomali" name="id_anomali">
                    <div class="input-group">
                        <input type="text" id="chatInputPesan" class="form-control"
                            placeholder="Ketik pesan (huruf, angka, ., , ?)"
                            pattern="[a-zA-Z0-9\s.,?]+"
                            title="Hanya diperbolehkan huruf, angka, titik, koma, dan tanda tanya."
                            required>
                        <button type="submit" class="btn btn-primary" id="btnSendChat">
                            <i class="fas fa-paper-plane"></i> Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Konfirmasi -->
<div class="modal fade" id="modalEditKonfirmasi" tabindex="-1" aria-labelledby="modalEditKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditKonfirmasiLabel"><i class="fas fa-edit me-2"></i> Edit Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKonfirmasi">
                <div class="modal-body">
                    <input type="hidden" id="editIdAnomali" name="id">

                    <div class="mb-3">
                        <label for="editKonfirmasiText" class="form-label fw-bold">Konfirmasi Petugas</label>

                        <!-- Wadah pesan error jika validasi gagal (Terletak di atas textarea) -->
                        <div id="editKonfirmasiError" class="alert alert-danger py-2 px-3 small d-none" role="alert">
                            <i class="fas fa-exclamation-triangle me-1"></i> <span id="editKonfirmasiErrorText"></span>
                        </div>

                        <textarea class="form-control"
                            id="editKonfirmasiText"
                            name="konfirmasi"
                            rows="4"
                            pattern="[a-zA-Z0-9\s.,?]+"
                            title="Hanya diperbolehkan huruf, angka, spasi, titik, koma, tanda tanya, dan tanda seru"
                            required></textarea>
                        <small class="text-muted d-block mt-1">
                            * Karakter yang diizinkan: Huruf, angka, spasi, titik (.), koma (,), tanda tanya (?), dan tanda seru (!). Minimum 5 karakter.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="btnSaveKonfirmasi">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-chat-container {
        position: relative;
        display: inline-block;
    }

    .chat-badge-dot {
        position: absolute;
        top: -3px;
        right: -3px;
        width: 9px;
        height: 9px;
        background-color: #fd7e14;
        /* Warna Oren Bootstrap */
        border: 1.5px solid #ffffff;
        border-radius: 50%;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        const collapseElement = document.getElementById('collapseFilter');

        // Logic accordion filter
        if (collapseElement) {
            if (window.innerWidth >= 768) {
                collapseElement.classList.add('show');
            } else {
                collapseElement.classList.remove('show');
            }
        }

        // 1. Logic Toggle Is Lap
        document.querySelectorAll('.btn-toggle-lap').forEach(button => {
            button.addEventListener('click', function() {
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>...';

                const formData = new FormData();
                formData.append('id_anomali', this.getAttribute('data-id'));
                formData.append(csrfToken, csrfHash);

                fetch('<?= base_url('anomali/ubah-is-lap'); ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Gagal: ' + data.message);
                            btn.disabled = false;
                        }
                    });
            });
        });

        // 2. Logic Toggle Check (Flag Status)
        document.querySelectorAll('.btn-toggle-check').forEach(button => {
            button.addEventListener('click', function() {
                const btn = this;
                const idAnomali = btn.getAttribute('data-id');

                const formData = new FormData();
                formData.append('id_anomali', idAnomali);
                formData.append(csrfToken, csrfHash);

                fetch('<?= base_url('anomali/toggle-check'); ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.is_check == 1) {
                                btn.className = 'btn btn-sm btn-success btn-toggle-check';
                                btn.innerHTML = '<i class="fas fa-check-circle"></i>';
                                btn.title = 'Sudah Dicek';
                            } else {
                                btn.className = 'btn btn-sm btn-outline-secondary btn-toggle-check';
                                btn.innerHTML = '<i class="fas fa-check"></i>';
                                btn.title = 'Tandai Sudah Dicek';
                            }
                        } else {
                            alert(data.message);
                        }
                    });
            });
        });

        // 3. Logic Chat Modal
        const modalChatElement = document.getElementById('modalChat');
        const modalChat = new bootstrap.Modal(modalChatElement);
        const chatContainer = document.getElementById('chatContainer');
        const chatUserEmail = document.getElementById('chatUserEmail');
        const chatIdAnomali = document.getElementById('chatIdAnomali');
        const chatInputPesan = document.getElementById('chatInputPesan');
        let currentUserEmail = '';

        // Buka Modal & Fetch Data Chat
        document.querySelectorAll('.btn-open-chat').forEach(button => {
            button.addEventListener('click', function() {
                const idAnomali = this.getAttribute('data-id');
                chatIdAnomali.value = idAnomali;
                chatContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Memuat chat...</div>';
                modalChat.show();

                fetch(`<?= base_url('anomali/get-chat'); ?>?id_anomali=${idAnomali}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            currentUserEmail = data.user_email;
                            chatUserEmail.textContent = currentUserEmail;
                            renderChatBubbles(data.chat);
                        }
                    });
            });
        });

        // Render Bubble Chat
        function renderChatBubbles(chatList) {
            chatContainer.innerHTML = '';
            if (!chatList || chatList.length === 0) {
                chatContainer.innerHTML = '<div class="text-center text-muted my-auto"><small>Belum ada percakapan.</small></div>';
                return;
            }

            chatList.forEach(item => {
                const isMe = item.email === currentUserEmail;
                const bubbleHtml = `
                <div class="d-flex flex-column ${isMe ? 'align-items-end' : 'align-items-start'}">
                    <small class="text-muted" style="font-size: 10px;">${item.email} - ${item.waktu ?? ''}</small>
                    <div class="p-2 rounded text-break shadow-sm ${isMe ? 'bg-primary text-white' : 'bg-white text-dark border'}" style="max-width: 80%; font-size: 13px;">
                        ${item.pesan}
                    </div>
                </div>
            `;
                chatContainer.insertAdjacentHTML('beforeend', bubbleHtml);
            });

            // Auto scroll ke paling bawah
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Sanitisasi Input Chat secara real-time
        chatInputPesan.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9\s.,?]/g, '');
        });

        // Submit Form Chat
        document.getElementById('formSendChat').addEventListener('submit', function(e) {
            e.preventDefault();
            const pesan = chatInputPesan.value.trim();

            if (!pesan) return;

            const btnSend = document.getElementById('btnSendChat');
            btnSend.disabled = true;

            const formData = new FormData();
            formData.append('id_anomali', chatIdAnomali.value);
            formData.append('pesan', pesan);
            formData.append(csrfToken, csrfHash);

            fetch('<?= base_url('anomali/send-chat'); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btnSend.disabled = false;
                    if (data.success) {
                        chatInputPesan.value = '';
                        renderChatBubbles(data.chat);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => {
                    btnSend.disabled = false;
                    alert('Gagal mengirim pesan.');
                });
        });

        // Modal Edit Konfirmasi
        const modalEditKonfirmasi = new bootstrap.Modal(document.getElementById('modalEditKonfirmasi'));
        const editIdAnomali = document.getElementById('editIdAnomali');
        const editKonfirmasiText = document.getElementById('editKonfirmasiText');
        const editKonfirmasiError = document.getElementById('editKonfirmasiError');
        const editKonfirmasiErrorText = document.getElementById('editKonfirmasiErrorText');

        // Buka Modal Edit Konfirmasi
        document.querySelectorAll('.btn-open-edit-konfirmasi').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const textKonfirmasi = this.getAttribute('data-konfirmasi');

                editIdAnomali.value = id;
                editKonfirmasiText.value = textKonfirmasi;

                // Sembunyikan error lama saat modal dibuka
                editKonfirmasiError.classList.add('d-none');
                editKonfirmasiText.classList.remove('is-invalid');

                modalEditKonfirmasi.show();
            });
        });

        // Submit Form Edit Konfirmasi
        document.getElementById('formEditKonfirmasi').addEventListener('submit', function(e) {
            e.preventDefault();
            const btnSave = document.getElementById('btnSaveKonfirmasi');

            // Reset tampilan error
            editKonfirmasiError.classList.add('d-none');
            editKonfirmasiText.classList.remove('is-invalid');

            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

            const formData = new FormData();
            formData.append('id', editIdAnomali.value);
            formData.append('konfirmasi', editKonfirmasiText.value);
            formData.append(csrfToken, csrfHash);

            fetch('<?= base_url('anomali/updateKonfirmasi'); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btnSave.disabled = false;
                    btnSave.innerHTML = 'Simpan Perubahan';

                    if (data.status === 'success') {
                        const updatedId = editIdAnomali.value;
                        const newKonfirmasi = editKonfirmasiText.value;

                        // Update UI teks secara langsung pada tabel
                        const textSpan = document.getElementById(`text-konfirmasi-${updatedId}`);
                        if (textSpan) {
                            textSpan.textContent = newKonfirmasi;
                        }

                        // Update attribute tombol
                        const btnEdit = document.querySelector(`.btn-open-edit-konfirmasi[data-id="${updatedId}"]`);
                        if (btnEdit) {
                            btnEdit.setAttribute('data-konfirmasi', newKonfirmasi);
                        }

                        modalEditKonfirmasi.hide();
                    } else {
                        // Munculkan pesan error di atas input text
                        editKonfirmasiErrorText.textContent = data.message || 'Inputan tidak sesuai aturan!';
                        editKonfirmasiError.classList.remove('d-none');
                        editKonfirmasiText.classList.add('is-invalid');
                    }
                })
                .catch(() => {
                    btnSave.disabled = false;
                    btnSave.innerHTML = 'Simpan Perubahan';
                    editKonfirmasiErrorText.textContent = 'Terjadi kesalahan sistem/koneksi.';
                    editKonfirmasiError.classList.remove('d-none');
                });
        });
    });
</script>
<?= $this->endSection(); ?>