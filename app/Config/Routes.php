<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = service('routes');

service('auth')->routes($routes);
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');

$routes->get('access-denied', function () {
    return view('errors/html/error_403');
});

$routes->get('/broadcast', 'Broadcast::index', ['filter' => 'activeRole:superadmin,admin,operator,mitra']); //untuk halaman awal broadcast
$routes->group('broadcast', ['filter' => 'activeRole:superadmin,admin,operator'], static function ($routes) {
    $routes->post('simpan', 'Broadcast::simpan'); //untuk tambah dan edit broadcast
    $routes->post('hapus', 'Broadcast::hapus'); //untuk hapus broadcast
});

$routes->group('anomali', ['filter' => 'activeRole:superadmin,admin,operator,mitra'], static function ($routes) {
    $routes->get('/', 'Anom::list'); //untuk halaman awal konfirmasi anomali dan konfirmasi anomali edit
    $routes->get('listDetil', 'Anom::listDetil'); //untuk memunculkan part accordion
    $routes->post('updateKonfirmasi', 'Anom::updateKonfirmasi'); //untuk update anomali
    $routes->get('listEdit', 'Anom::list/1'); //memunculkan anomali yg mau di edit
    $routes->get('konfir-fasih', 'Anom::konfirFasih'); //memunculkan anomali yg mau di edit
});
$routes->group('anomali', ['filter' => 'activeRole:superadmin,admin,operator'], static function ($routes) {
    $routes->get('konfirmasiBulk', 'Anom::konfirmasiBulk'); //untuk konfirmasi secara bulk
    $routes->get('rekap-anomali', 'Anom::rekapAnomali'); //untuk konfirmasi secara bulk
    $routes->get('log', 'Anom::log'); //untuk konfirmasi secara bulk
    $routes->get('log-detil/(:num)', 'Anom::logDetil/$1'); //untuk memunculkan detil error
    $routes->post('store', 'Anom::store'); //untuk upload template

});

$routes->group('manajemen-anomali', ['filter' => 'activeRole:superadmin,admin'], static function ($routes) {
    $routes->get('list', 'ManajAnom::manajemenList');  //daftar kategori anomali
    $routes->post('action', 'ManajAnom::manajemenAction'); //mati dan munculkan is public
    $routes->get('edit/(:any)', 'ManajAnom::edit/$1'); //edit kategori anomali
    $routes->post('updateKategori', 'ManajAnom::updateKategori'); //update kategori anomali
    $routes->get('log', 'ManajAnom::log'); //memunculkan log upload
    $routes->get('template/(:any)', 'ManajAnom::downloadTemplate/$1'); //download template
    $routes->post('store/(:any)', 'ManajAnom::store/$1'); //menambah log tambahan anomali
    $routes->get('log-detil/(:num)', 'ManajAnom::logDetil/$1'); //memunculkan error log
});

$routes->get('monitoring/', 'Monitoring::index', ['filter' => 'activeRole:superadmin,admin,operator']); //menampilkan seluruh anomali
$routes->get('monitoring-sel', 'Monitoring::view', ['filter' => 'activeRole:superadmin,admin,operator']); //menampilkan selected
$routes->post('monitoring-sel/update-request', 'Monitoring::updateRequestKesimpulan', ['filter' => 'activeRole:superadmin']); // sesuaikan dengan nama controller Anda
$routes->get('monitoring-petugas', 'Monitoring::viewPetugas', ['filter' => 'activeRole:superadmin,admin,operator']); //menampilkan Monitoring Petugas

$routes->group('identifikasi', ['filter' => 'activeRole:superadmin,admin,operator'], static function ($routes) {
    $routes->get('anades', 'Identifikasi::anades'); //untuk konfirmasi secara bulk
    $routes->post('anades/update', 'Identifikasi::update'); //untuk konfirmasi secara bulk
    $routes->get('kategorik', 'Identifikasi::kategorik'); //
    $routes->post('kategorik/update', 'Identifikasi::update_kategorik'); //
});

$routes->group('se', ['filter' => 'activeRole:superadmin,admin,operator'], static function ($routes) {
    // Rute yang bisa diakses oleh semua (superadmin, admin, operator)
    $routes->get('monitoring', 'SeMonitoring::index');
    $routes->get('monitoring-ngibar', 'SeMonitoring::monitorNgibar');
    $routes->get('duplikat', 'SeMonitoring::listDuplikat');
    $routes->get('ngibar', 'SeMonitoring::listNgibar');
    $routes->get('flag-duplikat/(:num)/(:num)', 'SeMonitoring::flagDuplikat/$1/$2');
    $routes->get('monitoring-ub', 'SeMonitoring::monitoringUB');
    $routes->post('monitoring-ub/update-keberadaan', 'SeMonitoring::updateKeberadaan');
    $routes->get('monitoring-progres', 'SeMonitoring::dashboardProgres');
    $routes->get('monitoring-progres/getTableData', 'SeMonitoring::getTabelProgres');
    $routes->get('monitoring-progres/downloadExcel', 'SeMonitoring::downloadExcelProgres');
    $routes->get('monitoring-progres/getPmlByKoseka', 'SeMonitoring::getPmlByKoseka');
    $routes->get('monitoring-progres/getPplByPml', 'SeMonitoring::getPplByPml');

    // Rute khusus yang HANYA bisa diakses oleh superadmin & admin
    // Kita timpa filternya secara spesifik di sini
    $routes->get('upload', 'SeMonitoring::logs', ['filter' => 'activeRole:superadmin,admin']);
    $routes->get('upload-ngibar', 'SeMonitoring::logs', ['filter' => 'activeRole:superadmin,admin']);
    $routes->post('upload-duplikat', 'SeMonitoring::uploadDuplikat', ['filter' => 'activeRole:superadmin,admin']);
    $routes->get('downloadTemplate', 'SeMonitoring::downloadTemplate', ['filter' => 'activeRole:superadmin,admin']);
    $routes->post('store', 'SeMonitoring::store', ['filter' => 'activeRole:superadmin,admin']);
    $routes->post('hapus', 'SeMonitoring::hapus', ['filter' => 'activeRole:superadmin,admin']);
    $routes->post('monitoring-ub/upload', 'SeMonitoring::uploadSEUB', ['filter' => 'activeRole:superadmin,admin']);
    $routes->post('monitoring-ub/updateTimPj', 'SeMonitoring::updatePJSEUB', ['filter' => 'activeRole:superadmin,admin']);
});
// $routes->group('se', ['filter' => 'activeRole:superadmin,admin'], static function ($routes) {
//     $routes->get('upload', 'SeMonitoring::logs');
//     $routes->get('upload-ngibar', 'SeMonitoring::logs');
//     $routes->post('upload-duplikat', 'SeMonitoring::uploadDuplikat');
//     $routes->get('downloadTemplate', 'SeMonitoring::downloadTemplate');
//     $routes->post('store', 'SeMonitoring::store');
//     $routes->post('hapus', 'SeMonitoring::hapus');
//     $routes->post('monitoring-ub/upload', 'SeMonitoring::uploadSEUB');
//     $routes->post('monitoring-ub/updateTimPj', 'SeMonitoring::updatePJSEUB');
// });

// $routes->get('/upload', 'Upload::index');
// $routes->get('/upload/download-template', 'Upload::downloadTemplate');
// $routes->post('/uploadFile', 'Upload::import');
$routes->group('user', ['filter' => 'activeRole:superadmin,admin'], static function ($routes) {
    $routes->get('organik', 'ManajUser::list');
    $routes->get('mitra', 'ManajUser::list/1');
    $routes->get('tambah', 'ManajUser::tambah');
    $routes->post('tambah-organik/store', 'ManajUser::simpan');
    $routes->get('edit/(:num)', 'ManajUser::edit/$1');
    $routes->post('edit/store', 'ManajUser::simpanEdit');
    $routes->get('hapus', 'ManajUser::hapus');
});

$routes->group('wilayah', ['filter' => 'activeRole:superadmin,admin,operator'], static function ($routes) {
    $routes->get('/', 'Wilayah::manajWilayahTugas');
    $routes->get('download-template', 'Wilayah::downloadTemplate');
    $routes->post('upload', 'Wilayah::store');
    $routes->get('logs', 'Wilayah::logsWilayah');
    $routes->get('log-detil/(:num)', 'Wilayah::logDetil/$1');
    $routes->post('edit', 'Wilayah::edit');
});

$routes->group('admin', ['filter' => 'activeRole:admin'], static function ($routes) {
    $routes->get('users', 'Admin\UserController::index');
    $routes->post('users/store', 'Admin\UserController::store');
    // Tambahkan route edit/delete di sini
});

$routes->group('fasihhelpdesk', ['filter' => 'activeRole:superadmin,admin,operator,mitra'], function ($routes) {
    $routes->get('/', 'FasihHelpdesk::index');                          // Menu Daftar FAQ + Form Manajemen
    $routes->get('listLaporan', 'FasihHelpdesk::listLaporan');          // Menu Daftar Tiket Masuk
    $routes->get('detailLaporan/(:num)', 'FasihHelpdesk::detailLaporan/$1'); // Ruang Diskusi Chat
    $routes->post('storeKnowledge', 'FasihHelpdesk::storeKnowledge');    // Tambah FAQ Manual
    $routes->post('updateKnowledge/(:num)', 'FasihHelpdesk::updateKnowledge/$1'); // Edit FAQ
    $routes->post('storeLaporan', 'FasihHelpdesk::storeLaporan');        // Kirim Tiket Baru (PPL)
    $routes->post('balasDiskusi/(:num)', 'FasihHelpdesk::balasDiskusi/$1');   // Kirim Balasan Chat
    $routes->get('closeDanJadikanKnowledge/(:num)', 'FasihHelpdesk::closeDanJadikanKnowledge/$1'); // Flag Selesai + Kloning Berkas
    $routes->get('closeLaporanBiasa/(:num)', 'FasihHelpdesk::closeLaporanBiasa/$1'); //hanya close laporan tertentu
});

// Semua route di dalam grup 'api' akan otomatis melewati pengecekan ApiKeyFilter
$routes->group('api', ['filter' => 'apiKeyAuth'], function ($routes) {

    // 1. API Upload Kabupaten (yang kita diskusikan sebelumnya)
    // $routes->post('anomali/upload-kabupaten', 'Api\Anomali::uploadKabupaten');

    // Endpoint untuk upload progres CSV dari Python
    $routes->post('se/upload-progres', 'Api\SeProgresController::uploadProgres');
    $routes->post('se/upload-list-ub', 'Api\SeListUbController::uploadListUb');
    $routes->post('anades/upload', 'Api\AnadesController::uploadCsv');
    $routes->post('anomali/store-individu', 'Api\AnomaliController::storeFromApi');

    // 2. Rencana API baru Anda di masa depan tinggal ditaruh di bawah sini:
    // $routes->get('anomali/rekap', 'Api\Anomali::getRekap');
    // $routes->post('se/sync-status', 'Api\MonitoringSE::syncStatus');
    // $routes->get('wilayah/list', 'Api\Wilayah::index');
});

$routes->get('faq', 'FasihHelpdesk::publikIndex');              // Portal FAQ Publik + Search Tanpa Login
$routes->get('faq/baca/(:num)', 'FasihHelpdesk::publikDetail/$1'); // Direct link halaman tunggal FAQ untuk di-share

$routes->get('set_kegiatan/(:any)', 'Kegiatan::set/$1', ['filter' => 'activeRole:superadmin,admin,operator,mitra']); // untuk ubah kegiatan di header
$routes->get('set_role', 'Admin\UserController::gantiRole', ['filter' => 'activeRole:superadmin,admin,operator,mitra']); // untuk ubah kegiatan di header
$routes->get('profile', 'Admin\UserController::profile');
$routes->post('profile/update', 'Admin\UserController::updateProfile');
