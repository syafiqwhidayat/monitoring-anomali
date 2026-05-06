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
        <div class="row d-flex align-items-center">
            <div class="col-12 col-sm-6 d-flex align-items-center justify-content-center justify-content-sm-start">
                <!-- <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="/img/bps.svg" alt="Logo" height="40" class="d-inline-block ms-2">
                    <span class="m-0 fs-6 fw-bold fst-italic lh-1 text-family-logo"> Badan Pusat Statistik<br>Kabupaten Dharmasraya</span>
                </a> -->
                <button id="toggle-sidebar" class="btn btn-icon bg-transparent border-0 text-white" type="button" aria-label="Toggle Sidebar" data-bs-target="#sidebar-menu" data-bs-toggle="collapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon icon-burger icon-tabler icons-tabler-outline icon-tabler-burger">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 15h16a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4" />
                        <path d="M12 4c3.783 0 6.953 2.133 7.786 5h-15.572c.833 -2.867 4.003 -5 7.786 -5" />
                        <path d="M5 12h14" />
                    </svg>
                </button>
                <?= view_cell('App\Cells\KegiatanCell::dropdown') ?>
            </div>
            <div class="col-12 col-sm-6 d-flex align-items-center justify-content-center justify-content-sm-end">
                <div class="row align-items-center">
                    <div class="col text-white">
                        <h3 class="page-title"><?php echo ucwords($user->name) ?></h3>
                        <div class="page-subtitle">
                            <div class="d-flex align-items-center justify-content-end">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/check -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                                <?php echo ucwords(session()->get('aktif_role')) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown" data-bs-toggle="dropdown">
                            <span
                                class="avatar avatar-md rounded-circle "
                                style="background-image: url(/img/bps.svg)"></span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="<?= base_url('profile') ?>" class="dropdown-item">Profil</a>
                            <a href="<?= base_url('set_role') ?>?return=<?= current_url() ?>" class="dropdown-item">Ganti Role</a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= base_url('logout') ?>" class="dropdown-item text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                                    <path d="M9 12h12l-3 -3"></path>
                                    <path d="M18 15l3 -3"></path>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>