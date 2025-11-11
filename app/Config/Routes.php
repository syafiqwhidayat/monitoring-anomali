<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Pages::index');
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
