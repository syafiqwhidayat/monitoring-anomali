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

        <!-- Target Collapse (Default tanpa class 'show' agar dikontrol oleh JS saat load) -->
        <div id="collapseFilter" class="collapse" aria-labelledby="headingFilter">
            <div class="card-body">

                <!-- FORM FILTER ANDA -->
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

                    <!-- Filter Status Cek -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status Cek</label>
                        <select name="fil-check" class="form-select form-control">
                            <option value="" <?= ($filterCheck === '') ? 'selected' : ''; ?>>Semua</option>
                            <option value="1" <?= ($filterCheck === '1') ? 'selected' : ''; ?>>Sudah Di Cek</option>
                            <option value="0" <?= ($filterCheck === '0') ? 'selected' : ''; ?>>Belum Di Cek</option>
                        </select>
                    </div>

                    <!-- Filter Konfirmasi Sejak Tanggal -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Konfirmasi setelah Tanggal</label>
                        <input type="date" name="fil-tgl-mulai" class="form-control" value="<?= esc($filterTglMulai); ?>">
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
                            <th width="15%">Wilayah, PPL & PML</th>
                            <th width="15%">KRT</th>
                            <th width="15%">ART</th>
                            <th width="8%">Kode Anom</th>
                            <th>Deskripsi Aturan / Rules</th>
                            <th width="20%" class="bg-warning text-dark">Konfirmasi Petugas Sebelumnya</th>
                            <th width="10%">Waktu Konfirmasi</th>
                            <th width="6%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($listAnom) && count($listAnom) > 0): ?>
                            <?php
                            // 1. HITUNG ROWSPAN UNTUK MENGELOMPOKKAN BERDASARKAN KRT
                            $krtCounts = [];
                            foreach ($listAnom as $row) {
                                // Menggunakan kd_krt sebagai key acuan merge
                                $krtKey = !empty($row['kd_krt']) ? $row['kd_krt'] : ($row['nm_krt'] ?? 'UNKNOWN');
                                if (!isset($krtCounts[$krtKey])) {
                                    $krtCounts[$krtKey] = 0;
                                }
                                $krtCounts[$krtKey]++;
                            }

                            $page = isset($_GET['page_group_assignment']) ? (int)$_GET['page_group_assignment'] : 1;
                            $no   = 1 + (($page - 1) * 15);
                            $processedKrt = []; // Menyimpan KRT yang sudah di-render td-nya

                            foreach ($listAnom as $row):
                                $krtKey = !empty($row['kd_krt']) ? $row['kd_krt'] : ($row['nm_krt'] ?? 'UNKNOWN');
                                $isFirstRowOfKrt = !in_array($krtKey, $processedKrt);

                                if ($isFirstRowOfKrt) {
                                    $rowspan = $krtCounts[$krtKey];
                                    $processedKrt[] = $krtKey;
                                }
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>

                                    <!-- KOLOM WILAYAH, PPL, & PML (DI-MERGE JIKA KRT SAMA) -->
                                    <?php if ($isFirstRowOfKrt): ?>
                                        <td rowspan="<?= $rowspan; ?>" class="text-center align-middle" style="background-color: #f8f9fa;">
                                            <div class="fw-bold mb-1" style="font-family: monospace; font-size: 13px;">
                                                <?= esc($row['nm_sls']); ?><br>
                                                <span class="text-muted">(<?= esc($row['id_wilayah']); ?>)</span>
                                            </div>
                                            <hr class="my-1">
                                            <small class="d-block text-start"><strong>PPL:</strong> <?= esc($row['nm_ppl'] ?? '-'); ?></small>
                                            <small class="d-block text-start text-muted"><strong>PML:</strong> <?= esc($row['nm_pml'] ?? '-'); ?></small>
                                        </td>
                                    <?php endif; ?>

                                    <!-- KOLOM KRT & TOMBOL FASIH (DI-MERGE JIKA KRT SAMA) -->
                                    <?php if ($isFirstRowOfKrt): ?>
                                        <td rowspan="<?= $rowspan; ?>" class="text-center align-middle" style="background-color: #f8f9fa;">
                                            <strong class="d-block mb-1"><?= esc($row['nm_krt'] ?? '-'); ?></strong>
                                            <small class="text-muted d-block mb-2" style="font-size: 11px;">Kode: <?= esc($row['kd_krt'] ?? '-'); ?></small>

                                            <!-- Tombol Ke Fasih diletakkan di bawah Nama KRT -->
                                            <?php if (session('isOrganik') && !empty($row['kd_krt'])): ?>
                                                <a href="https://fasih-sm.bps.go.id/app/assignment-detail/<?= esc($row['kd_krt']); ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm btn-outline-primary px-2 py-1 text-decoration-none"
                                                    style="font-size: 0.75rem;"
                                                    title="Buka Fasih untuk wilayah <?= esc($row['kd_krt']); ?>">
                                                    <i class="fas fa-external-link-alt me-1"></i> ke Fasih-SM
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>

                                    <!-- KOLOM ART -->
                                    <td>
                                        <strong><?= esc($row['nm_art'] ?? '-'); ?></strong>
                                    </td>

                                    <!-- KOLOM KODE ANOMALI -->
                                    <td class="text-center fw-bold text-danger"><?= esc($row['kode_anomali']); ?></td>

                                    <!-- KOLOM DESKRIPSI -->
                                    <td><small><?= esc($row['detil_anomali']); ?></small></td>

                                    <!-- KOLOM KONFIRMASI -->
                                    <td class="bg-light text-green" style="font-style: italic; font-weight: 500; font-size: 13px;">
                                        <?= esc($row['konfirmasi']); ?>
                                    </td>

                                    <!-- KOLOM WAKTU KONFIRMASI -->
                                    <td class="text-center">
                                        <small><?= !empty($row['date_konfirmasi']) ? date('d/m/Y H:i', strtotime($row['date_konfirmasi'])) : '-'; ?></small>
                                    </td>

                                    <!-- KOLOM AKSI -->
                                    <td class="text-center">
                                        <div class="d-flex flex-column gap-1">
                                            <button type="button"
                                                class="btn btn-sm <?= ($row['is_lap'] == 1) ? 'btn-outline-blue' : 'btn-outline-success'; ?> btn-toggle-lap"
                                                data-id="<?= $row['id_anomali']; ?>"
                                                title="Klik untuk mengubah status Kondisi Lapangan">
                                                <i class="fas fa-sync-alt me-1"></i>
                                                <?= ($row['is_lap'] == 1) ? 'Perbaikan Fasih' : 'Kondisi Lap'; ?>
                                            </button>

                                            <div class="d-flex gap-1 justify-content-center">
                                                <button type="button"
                                                    class="btn btn-sm <?= (!empty($row['is_check']) && $row['is_check'] == 1) ? 'btn-success' : 'btn-outline-secondary'; ?> btn-toggle-check"
                                                    data-id="<?= $row['id_anomali']; ?>"
                                                    title="<?= (!empty($row['is_check']) && $row['is_check'] == 1) ? 'Sudah Dicek' : 'Tandai Sudah Dicek'; ?>">
                                                    <i class="fas <?= (!empty($row['is_check']) && $row['is_check'] == 1) ? 'fa-check-circle' : 'fa-check'; ?>"></i>
                                                </button>

                                                <!-- Tombol Chat Modal -->
                                                <?php
                                                // Decode JSON chat untuk memastikan data bernilai array dan tidak kosong
                                                $chatData = !empty($row['chat']) ? json_decode($row['chat'], true) : [];
                                                $hasChat  = is_array($chatData) && count($chatData) > 0;
                                                ?>
                                                <div class="btn-chat-container">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary btn-open-chat"
                                                        data-id="<?= $row['id_anomali']; ?>"
                                                        title="Buka Chat Diskusi">
                                                        <i class="fas fa-comments"></i>
                                                    </button>
                                                    <?php if ($hasChat): ?>
                                                        <span class="chat-badge-dot" title="Ada pesan diskusi"></span>
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

        // logic filter
        if (collapseElement) {
            // Cek lebar viewport (768px adalah breakpoint md standar Bootstrap)
            if (window.innerWidth >= 768) {
                // Jika Desktop (>= 768px), buka accordion secara default
                collapseElement.classList.add('show');
            } else {
                // Jika HP/Mobile (< 768px), pastikan accordion tertutup
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
    });
</script>
<?= $this->endSection(); ?>