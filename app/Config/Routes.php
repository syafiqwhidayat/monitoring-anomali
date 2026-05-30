<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = service('routes');

service('auth')->routes($routes);
$routes->setAutoRoute(true);

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
});
$routes->get('/anomali/konfirmasiBulk', 'Anom::konfirmasiBulk', ['filter' => 'activeRole:superadmin,admin,operator']); //konfirmasi bulk anomali

$routes->group('manajemen-anomali', ['filter' => 'activeRole:superadmin,admin'], static function ($routes) {
    $routes->get('list', 'ManajAnom::manajemenList');  //daftar kategori anomali
    $routes->post('action', 'ManajAnom::manajemenAction'); //mati dan munculkan is public
    $routes->get('edit/(:any)', 'ManajAnom::edit/$1'); //edit kategori anomali
    $routes->post('updateKategori', 'ManajAnom::updateKategori'); //update kategori anomali
    $routes->get('log', 'ManajAnom::log'); //memunculkan log upload
    $routes->get('template', 'ManajAnom::downloadTemplate'); //download template
    $routes->post('store', 'ManajAnom::store'); //menambah log tambahan anomali
    $routes->get('log-detil/(:num)', 'ManajAnom::logDetil/$1'); //memunculkan error log
});

$routes->get('monitoring/', 'Monitoring::index', ['filter' => 'activeRole:superadmin,admin,operator']); //menampilkan seluruh anomali
$routes->get('monitoring-sel', 'Monitoring::view', ['filter' => 'activeRole:superadmin,admin,operator']); //menampilkan selected

$routes->group('se', ['filter' => 'activeRole:superadmin,admin,operator'], static function ($routes) {
    $routes->get('monitoring', 'SeMonitoring::index');
    $routes->get('monitoring-ngibar', 'SeMonitoring::monitorNgibar');
    $routes->get('duplikat', 'SeMonitoring::listDuplikat');
    $routes->get('ngibar', 'SeMonitoring::listNgibar');
    $routes->get('flag-duplikat/(:num)/(:num)', 'SeMonitoring::flagDuplikat/$1/$2');
    $routes->get('monitoring-ub', 'SeMonitoring::monitoringUB');
});
$routes->group('se', ['filter' => 'activeRole:superadmin,admin'], static function ($routes) {
    $routes->get('upload', 'SeMonitoring::logs');
    $routes->get('upload-ngibar', 'SeMonitoring::logs');
    $routes->post('upload-duplikat', 'SeMonitoring::uploadDuplikat');
    $routes->get('downloadTemplate', 'SeMonitoring::downloadTemplate');
    $routes->post('store', 'SeMonitoring::store');
    $routes->post('hapus', 'SeMonitoring::hapus');
    $routes->post('monitoring-ub/upload', 'SeMonitoring::uploadSEUB');
    $routes->post('monitoring-ub/updateTimPj', 'SeMonitoring::updatePJSEUB');
});

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

$routes->get('set_kegiatan/(:any)', 'Kegiatan::set/$1', ['filter' => 'activeRole:superadmin,admin,operator,mitra']); // untuk ubah kegiatan di header
$routes->get('set_role', 'Admin\UserController::gantiRole', ['filter' => 'activeRole:superadmin,admin,operator,mitra']); // untuk ubah kegiatan di header
$routes->get('profile', 'Admin\UserController::profile');
$routes->post('profile/update', 'Admin\UserController::updateProfile');
