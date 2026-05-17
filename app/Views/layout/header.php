<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<body>
    <?php $user = auth()->user(); ?>
    <div class="container-fluid p-3 header-sidik">
        <div class="row d-flex align-items-center g-2">
            <!-- Sisi Kiri: Toggle & Dropdown Kegiatan -->
            <div class="col d-flex align-items-center g-2">
                <!-- <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="/img/bps.svg" alt="Logo" height="40" class="d-inline-block ms-2">
                    <span class="m-0 fs-6 fw-bold fst-italic lh-1 text-family-logo"> Badan Pusat Statistik<br>Kabupaten Dharmasraya</span>
                </a> -->
                <button id="toggle-sidebar" class="btn btn-icon bg-transparent border-0 text-white" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-burger icon-tabler icons-tabler-outline icon-tabler-burger">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 15h16a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4" />
                        <path d="M12 4c3.783 0 6.953 2.133 7.786 5h-15.572c.833 -2.867 4.003 -5 7.786 -5" />
                        <path d="M5 12h14" />
                    </svg>
                </button>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="kegiatan-wrapper">
                    <?= view_cell('App\Cells\KegiatanCell::dropdown') ?>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2">
                    <div class="text-end d-none d-sm-block text-white">
                        <h3 class="page-title mb-0 fs-4"><?php echo ucwords($user->name) ?></h3>
                        <small class="page-subtitle opacity-75 d-flex align-items-center justify-content-end">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                            <?php echo ucwords(session()->get('aktif_role')) ?>
                        </small>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-md rounded-circle bg-azure-lt shadow-sm">
                                <?= strtoupper(substr($user->name, 0, 2)) ?>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="<?= base_url('profile') ?>" class="dropdown-item">Profil <span class="badge bg-orange text-orange-fg mx-1">Soon</span></a>
                            <a href="<?= base_url('set_role') ?>?return=<?= urlencode(current_url()) ?>" class="dropdown-item">Ganti Role</a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= base_url('logout') ?>" class="dropdown-item text-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>