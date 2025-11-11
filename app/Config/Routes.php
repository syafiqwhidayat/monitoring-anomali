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
$routes->post('/comics/save', 'Comics::save');
$routes->get('/comics/(:segment)', 'Comics::detail/$1');
$routes->setAutoRoute(true);
