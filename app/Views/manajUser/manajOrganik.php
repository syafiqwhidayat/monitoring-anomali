<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-xl">
    <!-- Header -->
    <div class="page-header d-print-none mb-3">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <?= $title; ?>
                </h2>
                <div class="text-muted mt-1">Kelola hak akses dan informasi pengguna organik aplikasi Sidik Anomali</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="<?= base_url('/user/tambah-organik'); ?>" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Tambah User Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Filter -->
    <div class="card mb-3 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <form action="" method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kabupaten</label>
                    <select name="kec" class="form-select">
                        <option value="">Kabupaten</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari Nama User/SLS</label>
                    <input type="text" name="keyword" class="form-control" placeholder="Ketik nama...">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Allert Error -->
    <?php if (session()->getFlashdata('message_errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('message_errors'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Allert Pesan -->
    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('message'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Allert Sukses -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div><?= session()->getFlashdata('success'); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card Tabel -->
    <div class="card card-rounded-custom shadow-sm">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th class="w-1">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Wilayah</th>
                        <th>Role</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 + ($perPage * ($currentPage - 1));
                    foreach ($users as $user) : ?>
                        <tr>
                            <td class="text-muted"><?= $no++; ?></td>
                            <td class="font-weight-medium"><?= $user['nama']; ?></td>
                            <td class="text-muted">
                                <a href="mailto:<?= $user['email']; ?>" class="text-reset"><?= $user['email']; ?></a>
                            </td>
                            <td><?= $user['wilayah_kerja']; ?></td>
                            <td>
                                <?php switch ($user['role']) {
                                    case 'superadmin':
                                        echo ('<span class="badge bg-red-lt">Superadmin</span>');
                                        break;
                                    case 'admin':
                                        echo ('<span class="badge bg-blue-lt">Admin</span>');
                                        # code...
                                        break;
                                    case 'operator':
                                        echo ('<span class="badge bg-green-lt">Operator</span>');
                                        # code...
                                        break;
                                    case 'mitra':
                                        echo ('<span class="badge bg-orange-lt">Mitra</span>');
                                        # code...
                                        break;
                                    default:
                                        echo ('<span class="badge bg-grey-lt">null</span>');
                                        # code...
                                        break;
                                } ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/user/edit-organik/' . $user['id']); ?>" class="btn btn-sm btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </a>
                                    <button
                                        class="btn btn-sm btn-outline-danger btn-hapus-user"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKonfirHapus"
                                        data-id=<?= $user['id']; ?>
                                        data-email=<?= $user['email']; ?>>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trash-x">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16zm-9.489 5.14a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" />
                                            <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)) : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <?= $pager->links('default', 'my_pager'); ?>
            </div>
        </div>
    </div>

    <!-- Modal Konfir Hapus -->
    <div class="modal modal-blur fade" id="modalKonfirHapus" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="<?= base_url('/user/hapus-organik') ?>" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasih Hapus Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span>Apakah Anda Yakin Menghapus Email: </span>
                        <span id="display-email" class="fw-bold">-</span>
                        <input type="hidden" name="id" id="id" value="">
                        <div class="d-flex justify-content-around">
                            <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-success ms-auto">Ya</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Menggunakan teknik rounded yang kita bahas sebelumnya */
    .card-rounded-custom {
        border-radius: 12px !important;
        overflow: hidden;
    }

    .table thead th {
        background: #f6f8fb;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.02em;
    }
</style>
<script>
    const hapusButton = document.querySelectorAll(".btn-hapus-user");

    hapusButton.forEach((button) => {
        button.addEventListener("click", function() {
            // Ambil data dari atribut tombol
            const id = this.getAttribute("data-id");
            const email = this.getAttribute("data-email");

            // Masukkan ke dalam modal rincian wilayah
            document.getElementById("display-email").innerText = email;

            // Set value dropdown ke nilai saat ini
            document.getElementById("id").value = id;
        });
    });
</script>
<?= $this->endSection(); ?>