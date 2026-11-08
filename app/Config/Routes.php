<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');
$routes->get('/', static fn () => redirect()->to('/login'));


// Only for guests
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::store');
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attempt');
});

// Only for logged-in users
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('logout', 'AuthController::logout');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile/edit', 'DashboardController::edit');
    $routes->post('profile/update', 'DashboardController::update');
    $routes->post('profile/delete', 'DashboardController::delete');
});

$routes->group('book', static function ($routes) {
    $routes->get('/', 'BookController::index');
    $routes->get('create', 'BookController::create');
    $routes->post('store', 'BookController::store');
    $routes->get('trash', 'BookController::trash');
    $routes->get('show/(:num)', 'BookController::show/$1');
    $routes->get('edit/(:num)', 'BookController::edit/$1');
    $routes->post('update/(:num)', 'BookController::update/$1');
    $routes->post('delete/(:num)', 'BookController::delete/$1');
    $routes->post('restore/(:num)', 'BookController::restore/$1');
});