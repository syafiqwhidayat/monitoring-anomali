<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/broadcast', 'Broadcast::index'); //untuk halaman awal broadcast
$routes->post('/broadcast/simpan', 'Broadcast::simpan'); //untuk simpang/edit broadcast
$routes->post('/broadcast/hapus', 'Broadcast::hapus'); //untuk hapus broadcast
// untuk memberi respon broadcast
// untuk lihat respon broadcast

$routes->get('/anomali', 'Anom::list'); //untuk halaman awal konfirmasi anomali dan konfirmasi anomali edit
$routes->get('/anomali/listDetil', 'Anom::listDetil'); //untuk memunculkan part accordion
$routes->post('/anomali/updateKonfirmasi', 'Anom::updateKonfirmasi');
// $routes->get('/anomali/listEdit', 'Anom::listEdit');
$routes->get('/anomali/listEdit', 'Anom::list/1');
$routes->get('/anomali/konfirmasiBulk', 'Anom::konfirmasiBulk');

$routes->get('/manajemen-anomali/list', 'ManajAnom::manajemenList');  //daftar kategori anomali
$routes->post('/manajemen-anomali/action', 'ManajAnom::manajemenAction');
$routes->get('manajemen-anomali/edit/(:any)', 'ManajAnom::edit/$1');
$routes->post('/manajemen-anomali/updateKategori', 'ManajAnom::updateKategori'); //update kategori anomali
$routes->get('/manajemen-anomali/log', 'ManajAnom::log');
$routes->get('/manajemen-anomali/template', 'ManajAnom::downloadTemplate');
$routes->post('/manajemen-anomali/store', 'ManajAnom::store'); //menambah log tambahan anomali
$routes->get('/manajemen-anomali/log-detil/(:num)', 'ManajAnom::logDetil/$1');

$routes->get('monitoring/', 'Monitoring::index');
$routes->get('monitoring-sel', 'Monitoring::view');

$routes->get('se/monitoring', 'SeMonitoring::index');
$routes->get('se/upload', 'SeMonitoring::logs');
$routes->get('se/downloadTemplate', 'SeMonitoring::downloadTemplate');
$routes->post('se/store', 'SeMonitoring::store');
$routes->post('se/hapus', 'SeMonitoring::hapus');

// $routes->get('/upload', 'Upload::index');
// $routes->get('/upload/download-template', 'Upload::downloadTemplate');
// $routes->post('/uploadFile', 'Upload::import');

// $routes->get('/pages', 'Pages::index');
// $routes->get('/about', 'Pages::about');
// $routes->get('/contact', 'Pages::contact');
// $routes->get('/comics', 'Comics::index');
// $routes->get('/comics/create', 'Comics::create');
// $routes->get('/comics/edit/(:any)', 'Comics::edit/$1');
// $routes->post('/comics/save', 'Comics::save');
// $routes->post('/comics/update/(:num)', 'Comics::update/$1');
// $routes->delete('/comics/(:num)', 'Comics::delete/$1');
// $routes->get('/comics/(:any)', 'Comics::detail/$1');

$routes->get('/user/organik', 'ManajUser::list');
$routes->get('/user/mitra', 'ManajUser::list/1');
$routes->get('/user/tambah', 'ManajUser::tambah');
$routes->post('/user/tambah-organik/store', 'ManajUser::simpan');
$routes->get('/user/edit/(:num)', 'ManajUser::edit/$1');
$routes->post('/user/edit/store', 'ManajUser::simpanEdit');
$routes->get('/user/hapus', 'ManajUser::hapus');

$routes->get('/wilayah', 'Wilayah::manajWilayahTugas');
$routes->get('/wilayah/downloadTemplate', 'Wilayah::downloadTemplate');
$routes->post('/wilayah/upload', 'Wilayah::store');
$routes->get('/wilayah/logs', 'Wilayah::logsWilayah');
$routes->get('/wilayah/log-detil/(:num)', 'Wilayah::logDetil/$1');
$routes->post('/wilayah/edit', 'Wilayah::edit');

$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('users', 'Admin\UserController::index');
    $routes->post('users/store', 'Admin\UserController::store');
    // Tambahkan route edit/delete di sini
});

$routes->get('set_kegiatan/(:any)', 'Kegiatan::set/$1'); // untuk ubah kegiatan di header
$routes->get('set_role', 'Admin\UserController::gantiRole'); // untuk ubah kegiatan di header

service('auth')->routes($routes);
$routes->setAutoRoute(true);
