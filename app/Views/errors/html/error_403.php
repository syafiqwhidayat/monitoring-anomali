<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>403 - Akses Ditolak</title>
    <!-- CSS Tabler (Sesuaikan path dengan proyek Anda) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class="border-top-wide border-danger d-flex flex-column">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">403</div>
                <div class="empty-img">
                    <!-- Ilustrasi Gembok/Akses Ditolak -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-off text-danger" width="128" height="128" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15 11v-1a3 3 0 0 0 -4.202 -2.744m-1.798 2.244v1.5"></path>
                        <path d="M7.025 11h-1.025a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 1.561 -.753m.439 -3.247v-4a2 2 0 0 0 -2 -2h-4"></path>
                        <path d="M12 16v.01"></path>
                        <path d="M3 3l18 18"></path>
                    </svg>
                </div>
                <p class="empty-title">Ups! Akses Dibatasi</p>
                <p class="empty-subtitle text-muted">
                    Maaf, akun Anda tidak memiliki izin untuk melihat halaman ini.
                    Hal ini mungkin terjadi karena Anda berada dalam mode <strong><?= session()->get('aktif_role') ?? 'Tamu' ?></strong>.
                </p>
                <div class="empty-action">
                    <div class="btn-list">
                        <a href="<?= base_url('/') ?>" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l14 0" />
                                <path d="M5 12l6 6" />
                                <path d="M5 12l6 -6" />
                            </svg>
                            Kembali ke Dashboard
                        </a>
                        <!-- Opsi Ganti Role Jika Perlu -->
                        <!-- <a href="#" class="btn btn-outline-secondary" data-bs-toggle="dropdown">
                            Ganti Role Aktif
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JS Tabler -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
</body>

</html>