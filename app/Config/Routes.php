<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/anomali', 'Anom::list');
$routes->get('/anomali/getListWilAnom/(:any)', 'Anom::listWilAnom/$1');
$routes->get('/anomali/manajemen', 'Anom::manajemen');
$routes->post('/anomali/manajemen-see', 'Anom::manajemenSee');
$routes->get('/anomali/upload', 'Anom::upload');
$routes->get('/anomali/upload/template-anomali', 'Anom::template');
$routes->get('/anomali/edit/(:any)', 'Anom::edit/$1');
$routes->post('/anomali/updateKonfirmasi', 'Anom::updateKonfirmasi');
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
