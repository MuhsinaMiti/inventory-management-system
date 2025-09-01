<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::login');
$routes->post('/do-login', 'AuthController::doLogin');
$routes->get('logout', 'AuthController::logout');
//$routes->get('/dashboard', 'Dashboard::index');
//$routes->get('my-dashboard', 'DashboardController::index'); <- test
$routes->get('dashboard', 'DashboardController::index');

$routes->get('products', 'ProductController::index');
$routes->get('products/create', 'ProductController::create');
$routes->post('products/store', 'ProductController::store');

$routes->get('products/edit/(:num)', 'ProductController::edit/$1');
$routes->post('products/update/(:num)', 'ProductController::update/$1');

$routes->get('products/delete/(:num)', 'ProductController::delete/$1');

$routes->get('stock/adjust/(:num)', 'StockController::adjustForm/$1');
$routes->post('stock/adjust/(:num)', 'StockController::adjust/$1');
$routes->get('stock/logs', 'StockController::logs');

$routes->get('/deliveries', 'DeliveriesController::index');
$routes->get('/deliveries/add', 'DeliveriesController::add');
$routes->post('/deliveries/store', 'DeliveriesController::store');

$routes->get('stock/reduce', 'StockController::reduceForm');
$routes->post('stock/reduce', 'StockController::reduce');

$routes->get('products/exportExcel', 'ProductController::export_excel');
$routes->post('products/importExcel', 'ProductController::import_excel');









