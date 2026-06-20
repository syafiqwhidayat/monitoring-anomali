<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS Tabler -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" />
    <!-- CSS Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title><?= $title; ?></title>
</head>

<body>
    <!-- Sidebar -->
    <aside class="navbar navbar-vertical navbar-expand-lg" id="sidebar-main">
        <div class="container-fluid">
            <!-- tombol sidebar -->
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                <span class="navbar-toggler-icon"></span>
            </button> -->
            <div class="navbar-brand navbar-brand-autodark">
                <a href="<?= base_url() ?>">
                    <img src="<?= base_url('img/logo.png') ?>"
                        width="110"
                        height="48"
                        alt="Sidik Anomali"
                        class="navbar-brand-image">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">

                    <!-- Home -->
                    <li class="nav-item <?= url_is('/') ? 'active' : '' ?>">
                        <a class="nav-link" href=<?= base_url('/') ?>>
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title"> Home </span>
                        </a>
                    </li>
                    <?php if (session('aktif_role') !== 'mitra'): ?>
                        <li class="nav-item dropdown <?= url_is('se/*') ? 'active' : '' ?>">
                            <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-desktop-analytics">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 5a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1l0 -10" />
                                        <path d="M7 20h10" />
                                        <path d="M9 16v4" />
                                        <path d="M15 16v4" />
                                        <path d="M9 12v-4" />
                                        <path d="M12 12v-1" />
                                        <path d="M15 12v-2" />
                                        <path d="M12 12v-1" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Monitoring SE2026</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <a class="dropdown-item" href="<?= base_url('/se/monitoring-progres') ?>">Progress SE2026</a>
                                    <a class="dropdown-item" href="<?= base_url('/se/monitoring-ub') ?>">Progress SE2026-UB</a>
                                    <a class="dropdown-item" href="<?= base_url('/se/monitoring') ?>">Moniroing Keseluruhan</a>
                                    <a class="dropdown-item" href="<?= base_url('/se/monitoring-ngibar') ?>">Moniroing Ngibar</a>
                                    <a class="dropdown-item" href="<?= base_url('/se/duplikat') ?>">Duplikat Ngibar</a>
                                    <a class="dropdown-item" href="<?= base_url('/se/ngibar') ?>">Daftar Assigment Ngibar</a>
                                    <?php if (session('aktif_role') === 'superadmin'): ?>
                                        <a class="dropdown-item" href="<?= base_url('/se/upload') ?>">Upload Monitoring SE2026</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown <?= url_is('broadcast') ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-flag-share">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M13.13 14.833a5.002 5.002 0 0 1 -1.13 -.833a5 5 0 0 0 -7 0v-9a5 5 0 0 1 7 0a5 5 0 0 0 7 0v8" />
                                    <path d="M5 21v-7" />
                                    <path d="M16 22l5 -5" />
                                    <path d="M21 21.5v-4.5h-4.5" />
                                </svg>
                            </span>
                            <span class="nav-link-title"> Broadcast</span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <a class="dropdown-item" href="<?= base_url('/broadcast') ?>">Broadcast</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown <?= url_is('anomali*') ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bug">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                    <path d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                    <path d="M3 13l4 0" />
                                    <path d="M17 13l4 0" />
                                    <path d="M12 20l0 -6" />
                                    <path d="M4 19l3.35 -2" />
                                    <path d="M20 19l-3.35 -2" />
                                    <path d="M4 7l3.75 2.4" />
                                    <path d="M20 7l-3.75 2.4" />
                                </svg>
                            </span>
                            <span class="nav-link-title"> Anomali </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <a class="dropdown-item" href="<?= base_url('/anomali') ?>">Konfirmasi Anomali</a>
                                <a class="dropdown-item" href="<?= base_url('/anomali/listEdit') ?>">Edit Konfirmasi Anomali</a>
                                <?php if (session('aktif_role') !== 'mitra'): ?>
                                    <a class="dropdown-item" href="<?= base_url('/anomali/konfirmasiBulk') ?>">Konfirmasi Bulk Anomali</a>
                                <?php endif; ?>
                                <a class="dropdown-item" href="<?= base_url('/anomali/konfir-fasih') ?>">Konfirmasi Fasih</a>
                                <?php if (session('aktif_role') !== 'mitra'): ?>
                                    <a class="dropdown-item" href="<?= base_url('/anomali/rekap-anomali') ?>">Rekap By Assigment</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    <?php if (session('aktif_role') !== 'mitra'): ?>
                        <li class="nav-item dropdown <?= url_is('manajemen-anomali/*') ? 'active' : '' ?>">
                            <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                        <path d="M12 12l8 -4.5" />
                                        <path d="M12 12l0 9" />
                                        <path d="M12 12l-8 -4.5" />
                                        <path d="M16 5.25l-8 4.5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title"> Manajemen Anomali </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url('/manajemen-anomali/list') ?>">Manajemen Anomali</a>
                                    <a class="dropdown-item" href="<?= base_url('/manajemen-anomali/log') ?>">Log Upload Anomali</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown <?= url_is('monitoring*') ? 'active' : '' ?>">
                            <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-dashboard">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 13a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M13.45 11.55l2.05 -2.05" />
                                        <path d="M6.4 20a9 9 0 1 1 11.2 0l-11.2 0" />
                                    </svg>
                                </span>
                                <span class="nav-link-title"> Monitoring Anomali</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url('/monitoring') ?>">Monitoring Anomali All</a>
                                    <a class="dropdown-item" href="<?= base_url('monitoring-sel') ?>">Monitoring Anomali Tertentu</a>
                                    <a class="dropdown-item" href="<?= base_url('') ?>">Evaluasi Petugas <span class="badge bg-orange text-orange-fg mx-1">Soon</span></a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php $allowedRoles = ['superadmin', 'admin'];
                    if (in_array(session('aktif_role'), $allowedRoles)): ?>
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Manajemen Akun</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url('/user/organik') ?>">Manajemen User Organik</a>
                                    <a class="dropdown-item" href="<?= base_url('/user/mitra') ?>">Manajemen User Mitra</a>
                                    <a class="dropdown-item" href="<?= base_url('/wilayah') ?>">Manajemen Wilayah Tugas</a>
                                    <a class=" dropdown-item" href="<?= base_url('/wilayah/logs') ?>">Log Upload Wilayah Tugas</a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-icons">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 6.5a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                                    <path d="M2.5 21h8l-4 -7l-4 7" />
                                    <path d="M14 3l7 7" />
                                    <path d="M14 10l7 -7" />
                                    <path d="M14 14h7v7h-7l0 -7" />
                                </svg>
                            </span>
                            <span class="nav-link-title">Manajemen Kegiatan</span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item" href="<?= base_url('/user/organik') ?>">Daftar Kegiatan <span class="badge bg-orange text-orange-fg mx-1">Soon</span></a>
                                <?php $allowedRoles = ['superadmin', 'admin'];
                                if (in_array(session('aktif_role'), $allowedRoles)): ?>
                                    <a class="dropdown-item" href="<?= base_url('/user/organik') ?>">Manajemen Kegiatan <span class="badge bg-orange text-orange-fg mx-1">Soon</span></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                    <?php if (session('aktif_role') !== 'mitra'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-sql">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M5 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" />
                                        <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                        <path d="M18 15v6h2" />
                                        <path d="M13 15a2 2 0 0 1 2 2v2a2 2 0 1 1 -4 0v-2a2 2 0 0 1 2 -2" />
                                        <path d="M14 20l1.5 1.5" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Generate SQL</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url('') ?>">Genarte SQL Lab <span class="badge bg-orange text-orange-fg mx-1">Soon</span></a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if (session('aktif_role') !== 'mitra'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-auto-close="false" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-zoom-question">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                        <path d="M21 21l-6 -6" />
                                        <path d="M10 13l0 .01" />
                                        <path d="M10 10a1.5 1.5 0 1 0 -1.14 -2.474" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">Helpdesk Fasih</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="<?= base_url('fasihhelpdesk') ?>">Knowledge Base</a>
                                    <a class="dropdown-item" href="<?= base_url('fasihhelpdesk/listLaporan') ?>">Lapor Fasih</a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </aside>
</body>

</html>