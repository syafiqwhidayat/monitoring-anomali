<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $kb['judul']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="<?= base_url('css/style.css'); ?>" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6fa;
            color: #1c2434;
        }

        :root {
            --anomali-teal: var(--tblr-primary);
            /* #1e7960 */
            --anomali-teal-dark: #0f4e3e;
            --anomali-navy: var(--sidik-prm);
            /* #182433 */
            --anomali-orange: var(--oren-bps);
            /* #ee8911 */
        }

        .navbar-anomali {
            background-color: var(--anomali-teal);
            border-bottom: 3px solid var(--anomali-navy);
        }

        .main-card {
            border: none;
            border-radius: 12px !important;
            background: #ffffff;
            box-shadow: 0 10px 30px -5px rgba(6, 78, 59, 0.08);
            overflow: hidden;
        }

        /* Card Header Berubah Menjadi Gradasi Hijau Sidik Anomali */
        .card-header-premium {
            background: linear-gradient(135deg, var(--anomali-teal) 0%, var(--anomali-teal-dark) 100%);
            color: #ffffff;
            padding: 2.5rem 2rem;
            border-bottom: none;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        /* Box Temuan Masalah Anomali (Aksen Oranye) */
        .box-container-masalah {
            background-color: #fff9f2;
            border-left: 5px solid var(--anomali-orange);
            border-radius: 8px;
            padding: 1.5rem;
        }

        /* Box Solusi Bersih (Aksen Hijau Tipis) */
        .box-container-solusi {
            background-color: rgba(6, 78, 59, 0.04);
            border-left: 5px solid var(--anomali-teal);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .box-header-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-anomali shadow-sm py-3">
        <div class="container">
            <div class="bg-white d-flex align-items-center justify-content-center rounded-pill shadow-sm px-3 py-1 me-2" style="height: 45px; min-width: 80px;">
                <img src="<?= base_url('img/logo.png'); ?>"
                    alt="Logo Sidik Anomali"
                    style="max-height: 35px; width: auto; object-fit: contain;"
                    onerror="this.src='https://placehold.co/60x30/ffffff/1e7960?text=BPS'">
            </div>
            <span class="text-uppercase text-white" style="font-size: 1.5rem;">FASIH FAQ</span>
            <a href="<?= base_url('faq'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3"><i class="fas fa-search me-1"></i> Cari Lainnya</a>
        </div>
    </nav>

    <div class="container my-5" style="max-width: 780px;">
        <div class="mb-4">
            <a href="<?= base_url('faq'); ?>" class="text-decoration-none fw-bold text-muted small">
                <i class="fas fa-chevron-left me-1"></i> Kembali ke Pusat Daftar FAQ
            </a>
        </div>

        <div class="card main-card shadow-lg">
            <div class="card-header-premium">
                <span class="badge bg-warning text-dark fw-bold text-uppercase mb-2 px-3 py-1.5" style="font-size: 0.7rem; border-radius: 5px;"><?= esc($kb['kategori']); ?></span>
                <h2 class="fw-bold mb-2" style="font-size: 1.65rem; line-height: 1.4;"><?= esc($kb['judul']); ?></h2>
                <div class="text-white-50 small">
                    <i class="far fa-clock me-1"></i> Dokumen Solusi Diperbarui: <span class="text-white"><?= date('d M Y — H:i', strtotime($kb['date_updated'])); ?> WIB</span>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">

                <div class="box-container-masalah mb-4 shadow-sm">
                    <div class="box-header-title text-warning-emphasis">
                        <i class="fas fa-exclamation-circle me-2 fs-5 text-warning"></i> Gejala / Penjelasan Masalah:
                    </div>
                    <div style="white-space: pre-line; font-size: 0.95rem; line-height: 1.6; color: #451a03;">
                        <?= esc($kb['masalah']); ?>
                    </div>
                </div>

                <div class="box-container-solusi mb-4 shadow-sm">
                    <div class="box-header-title text-success-emphasis">
                        <i class="fas fa-check-circle me-2 fs-5 text-success"></i> Langkah-Langkah Solusi Penyelesaian:
                    </div>
                    <div style="white-space: pre-line; font-size: 1.05rem; line-height: 1.7; color: #14532d;">
                        <?= esc($kb['solusi']); ?>
                    </div>
                </div>

                <?php if (!empty($kb['foto'])): ?>
                    <div class="mt-5 p-3 bg-light rounded-3 border text-center shadow-sm">
                        <span class="d-block text-muted small fw-bold mb-3 text-start"><i class="fas fa-image text-secondary me-1"></i> Dokumentasi Lampiran Lampiran Visual Terkait:</span>
                        <a href="<?= base_url('uploads/helpdesk/knowledge/' . $kb['foto']); ?>" target="_blank">
                            <img src="<?= base_url('uploads/helpdesk/knowledge/' . $kb['foto']); ?>"
                                class="img-fluid rounded shadow-sm border bg-white p-1"
                                style="max-height: 420px; object-fit: contain; width: 100%;"
                                alt="Gambar Dokumen Pendukung">
                        </a>
                        <div class="form-text text-muted mt-2" style="font-size: 11px;">*Sentuh atau klik pada gambar untuk melihat resolusi penuh di tab baru.</div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="card-footer bg-light p-4 text-center text-muted small border-0">
                Pusat Integrasi Data & Sistem Informasi Jaminan Mutu Lapangan<br>
                <strong class="text-dark">Sidik Anomali — BPS Kabupaten Dharmasraya</strong>
            </div>
        </div>
    </div>

    <footer class="py-4 text-center text-muted small mt-auto">
        &copy; 2026 Hak Cipta Dilindungi Undang-Undang. Tim IT Organik BPS.
    </footer>

</body>

</html>