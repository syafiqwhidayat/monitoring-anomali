<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Pastikan style.css utama tetap ter-load -->
    <link href="<?= base_url('css/style.css'); ?>" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6fa;
            color: #1c2434;
        }

        /* PEMETAAN WARNA UTAMA BERBASIS HIJAU TABLER */
        :root {
            --anomali-teal: var(--tblr-primary);
            /* #1e7960 (Hijau Utama) */
            --anomali-teal-dark: #0f4e3e;
            /* Hijau Gelap untuk Gradasi */
            --anomali-navy: var(--sidik-prm);
            /* #182433 */
            --anomali-orange: var(--oren-bps);
            /* #ee8911 */
            --anomali-orange-hover: var(--oren-bps-hover);
        }

        /* Navbar Menggunakan Warna Hijau Utama */
        .navbar-anomali {
            background-color: var(--anomali-teal);
            border-bottom: 3px solid var(--anomali-navy);
        }

        /* Hero Section Berubah Menjadi Dominan Hijau Primary */
        .hero-section {
            background: linear-gradient(135deg, var(--anomali-teal) 0%, var(--anomali-teal-dark) 100%);
            color: #ffffff;
            padding: 5rem 0 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: #f4f6fa;
            clip-path: polygon(0 100%, 100% 100%, 100% 0);
        }

        .search-box-container {
            margin-top: -3.5rem;
            position: relative;
            z-index: 10;
        }

        /* Card Radius Mengikuti Aturan 12px */
        .search-card {
            border: none;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.1);
        }

        .form-control-anomali {
            border: 2px solid #ced4da;
            border-radius: 8px 0 0 8px;
            padding: 1rem 1.5rem;
            font-size: 1.05rem;
        }

        .form-control-anomali:focus {
            border-color: var(--anomali-teal);
            box-shadow: none;
        }

        /* Tombol Cari Menggunakan Warna Navy Sidik Prm */
        .btn-anomali-search {
            background-color: var(--anomali-navy);
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: 0 8px 8px 0;
            padding: 0 2rem;
            transition: all 0.2s ease;
        }

        .btn-anomali-search:hover {
            background-color: #2c3e50;
            color: #ffffff;
        }

        /* Card FAQ */
        .faq-card {
            border: none;
            border-radius: 12px !important;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 5px solid #ced4da;
            overflow: hidden;
        }

        .faq-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 20px -5px rgba(6, 78, 59, 0.08);
            border-left-color: var(--anomali-teal);
        }

        /* Badge Kategori Menggunakan Aksen Oranye BPS Agar Kontras */
        .badge-kategori {
            background-color: #fff9f2;
            color: var(--anomali-orange);
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
        }

        .btn-action-view {
            color: var(--anomali-teal);
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .btn-action-view:hover {
            color: var(--anomali-teal-dark);
        }

        /* Style Toast Notifikasi Kiri Bawah Sesuai Variabel Sidik Anomali */
        .toast-anomali {
            position: fixed;
            bottom: 20px;
            left: -350px;
            /* Sembunyi di luar layar kiri secara default */
            background-color: var(--anomali-navy);
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            border-left: 4px solid var(--anomali-teal);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 9999;
            transition: left 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .toast-anomali.show {
            left: 20px;
            /* Muncul bergeser ke kanan masuk ke dalam layar */
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-anomali shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold text-uppercase tracking-wider" href="<?= base_url('faq'); ?>">
                <!-- WADAH KAPSUL PUTIH MEMANJANG AGAR LOGO LEBIH STANDOUT DAN PROPORSIONAL -->
                <div class="bg-white d-flex align-items-center justify-content-center rounded-pill shadow-sm px-3 py-1 me-2" style="height: 45px; min-width: 80px;">
                    <img src="<?= base_url('img/logo.png'); ?>"
                        alt="Logo Sidik Anomali"
                        style="max-height: 35px; width: auto; object-fit: contain;"
                        onerror="this.src='https://placehold.co/60x30/ffffff/1e7960?text=BPS'">
                </div>
                <span class="text-uppercase text-white" style="font-size: 1.5rem;">FASIH FAQ</span>
            </a>
            <a href="<?= base_url('login'); ?>" class="btn btn-outline-light btn-sm fw-bold px-3 rounded-pill"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
        </div>
    </nav>

    <div class="hero-section text-center">
        <div class="container" style="max-width: 800px;">
            <span class="badge bg-warning text-dark fw-bold mb-3 px-3 py-2 text-uppercase rounded-pill" style="font-size: 0.75rem; letter-spacing: 1px;">Pusat Pengetahuan Lapangan</span>
            <h1 class="display-5 fw-bold mb-2">Ada Kendala dengan Aplikasi FASIH?</h1>
            <p class="text-white-50 lead fs-6">Temukan solusi mandiri, penanganan kode kesalahan, dan panduan teknis kuesioner secara instan tanpa antre.</p>
        </div>
    </div>

    <div class="container search-box-container" style="max-width: 750px;">
        <div class="card search-card p-2 bg-white">
            <form method="GET" action="<?= base_url('faq'); ?>">
                <div class="input-group">
                    <input type="text" name="cari" class="form-control form-control-anomali"
                        placeholder="Ketik kata kunci kendala... (misal: eror 403, lokasi ganda, gagal kirim)"
                        value="<?= esc($filterCari); ?>" autocomplete="off" required>
                    <button class="btn btn-anomali-search shadow-sm" type="submit">
                        <i class="fas fa-search me-1"></i> Cari Solusi
                    </button>
                </div>
            </form>
        </div>

        <?php if (!empty($filterCari)): ?>
            <div class="text-center mt-3">
                <p class="text-muted small">Menampilkan hasil pencarian kata kunci: <strong class="text-dark">"<?= esc($filterCari); ?>"</strong>
                    <a href="<?= base_url('faq'); ?>" class="text-danger ms-2 text-decoration-none fw-bold"><i class="fas fa-times-circle"></i> Reset</a>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="container my-5" style="max-width: 750px;">
        <div class="row g-3">
            <?php if (empty($knowledges)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-4 bg-white rounded-3 shadow-sm border">
                        <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold text-dark">Arsip Dokumen Tidak Ditemukan</h5>
                        <p class="text-muted small mb-0">Kata kunci tidak cocok dengan dokumen solusi manapun. Silakan laporkan kendala ini langsung ke Koordinator Kecamatan / Admin Kabupaten via aplikasi.</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($knowledges as $row): ?>
                <div class="col-12">
                    <div class="card faq-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-kategori"><i class="fas fa-tags me-1"></i> <?= esc($row['kategori']); ?></span>
                                <small class="text-muted" style="font-size: 0.8rem;"><i class="far fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($row['date_updated'])); ?></small>
                            </div>

                            <h4 class="fw-bold text-dark mb-2" style="font-size: 1.25rem; line-height: 1.4;"><?= esc($row['judul']); ?></h4>

                            <p class="text-muted small mb-3 text-line-clamp" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                                <strong>Identifikasi Gejala:</strong> <?= esc($row['masalah']); ?>
                            </p>

                            <hr class="border-dashed my-3 text-black-50">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?= base_url('faq/baca/' . $row['id']); ?>" class="btn-action-view">
                                    Baca Arahan Lengkap <i class="fas fa-arrow-right ms-2" style="font-size: 0.85rem;"></i>
                                </a>
                                <!-- TOMBOL SALIN TAUTAN TANPA EMBEL-EMBEL WA -->
                                <button class="btn-action-share" onclick="copyLink('<?= base_url('faq/baca/' . $row['id']); ?>')">
                                    <i class="fas fa-link me-1"></i> Salin Tautan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="py-4 text-center text-muted border-top bg-white mt-auto small">
        <div class="container">
            <strong>Sidik Anomali &copy; 2026</strong> — Pusat Penanganan Informasi Kendala Lapangan Terpadu.<br>
            <span class="text-black-50 small">Badan Pusat Statistik Kabupaten Dharmasraya</span>
        </div>
    </footer>

    <!-- TOAST NOTIFICATION KUSTOM DI KIRI BAWAH -->
    <div id="toast-salin" class="toast-anomali">
        <i class="fas fa-check-circle me-2"></i> Tautan berhasil disalin ke clipboard!
    </div>

    <script>
        function copyLink(url) {
            // Proses menyalin teks tautan ke clipboard
            navigator.clipboard.writeText(url).then(() => {
                const toast = document.getElementById('toast-salin');

                // Tambahkan class 'show' untuk memunculkan notifikasi dari kiri bawah
                toast.classList.add('show');

                // Sembunyikan kembali secara otomatis setelah 3 detik
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }).catch(err => {
                console.error('Gagal menyalin tautan: ', err);
            });
        }
    </script>
</body>

</html>