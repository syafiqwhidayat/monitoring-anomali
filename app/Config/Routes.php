<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/anomali', 'Anom::list');
$routes->get('/anomali/getListWilAnom/(:any)/(:num)', 'Anom::listWilAnom/$1/$2');
$routes->post('/anomali/updateKonfirmasi', 'Anom::updateKonfirmasi');
$routes->get('/anomali/listEdit', 'Anom::listEdit');
$routes->get('/anomali/konfirmasiBulk', 'Anom::konfirmasiBulk');

$routes->get('/manajemen-anomali/list', 'ManajAnom::manajemenList');
$routes->post('/manajemen-anomali/action', 'ManajAnom::manajemenAction');
$routes->get('/manajemen-anomali/upload', 'ManajAnom::upload');
$routes->get('/manajemen-anomali/upload/template-anomali', 'ManajAnom::template');
$routes->get('manajemen-anomali/edit/(:any)', 'ManajAnom::edit/$1');
$routes->post('/manajemen-anomali/updateKategori', 'ManajAnom::updateKategori');

$routes->get('monitoring/', 'Monitoring::index');
$routes->get('monitoringSelc/(:num)', 'Monitoring::view/$1');

$routes->get('/upload', 'Upload::index');
$routes->get('/upload/download-template', 'Upload::downloadTemplate');
$routes->post('/uploadFile', 'Upload::import');

$routes->get('/pages', 'Pages::index');
$routes->get('/about', 'Pages::about');
$routes->get('/contact', 'Pages::contact');
$routes->get('/comics', 'Comics::index');
$routes->get('/comics/create', 'Comics::create');
$routes->get('/comics/edit/(:any)', 'Comics::edit/$1');
$routes->post('/comics/save', 'Comics::save');
$routes->post('/comics/update/(:num)', 'Comics::update/$1');
$routes->delete('/comics/(:num)', 'Comics::delete/$1');
$routes->get('/comics/(:any)', 'Comics::detail/$1');
$routes->setAutoRoute(true);

$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('users', 'Admin\UserController::index');
    $routes->post('users/store', 'Admin\UserController::store');
    // Tambahkan route edit/delete di sini
});

service('auth')->routes($routes);
