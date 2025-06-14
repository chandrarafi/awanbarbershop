<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes (Admin)
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Customer Auth Routes
$routes->group('customer', function ($routes) {
    $routes->get('login', 'CustomerAuth::index');
    $routes->get('register', 'CustomerAuth::register');
    $routes->post('doRegister', 'CustomerAuth::doRegister');
    $routes->get('verify', 'CustomerAuth::verify');
    $routes->post('doVerify', 'CustomerAuth::doVerify');
    $routes->post('resendOTP', 'CustomerAuth::resendOTP');
    $routes->post('login', 'CustomerAuth::login');
    $routes->get('logout', 'CustomerAuth::logout');
    $routes->get('complete-profile', 'CustomerAuth::completeProfile', ['filter' => 'auth']);
});

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin::index', ['filter' => 'role:admin,manager']);

    // User Management (hanya admin)
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('users', 'Admin::users');
        $routes->get('getUsers', 'Admin::getUsers');
        $routes->get('getUser/(:num)', 'Admin::getUser/$1');
        $routes->post('createUser', 'Admin::createUser');
        $routes->post('addUser', 'Admin::addUser');
        $routes->post('updateUser/(:num)', 'Admin::updateUser/$1');
        $routes->post('deleteUser/(:num)', 'Admin::deleteUser/$1');
        $routes->get('getRoles', 'Admin::getRoles');
    });

    // Karyawan Routes
    $routes->group('karyawan', ['filter' => 'role:admin,manager'], function ($routes) {
        $routes->get('/', 'Karyawan::index');
        $routes->get('getKaryawan', 'Karyawan::getKaryawan');
        $routes->get('getNewId', 'Karyawan::getNewId');
        $routes->get('getById/(:segment)', 'Karyawan::getById/$1');
        $routes->post('store', 'Karyawan::store');
        $routes->post('update/(:segment)', 'Karyawan::update/$1');
        $routes->delete('delete/(:segment)', 'Karyawan::delete/$1');
    });

    // Paket Routes
    $routes->group('paket', ['filter' => 'role:admin,manager'], function ($routes) {
        $routes->get('/', 'Paket::index');
        $routes->get('create', 'Paket::create');
        $routes->get('edit/(:segment)', 'Paket::edit/$1');
        $routes->get('getPaket', 'Paket::getPaket');
        $routes->get('getNewId', 'Paket::getNewId');
        $routes->get('getById/(:segment)', 'Paket::getById/$1');
        $routes->post('store', 'Paket::store');
        $routes->post('update/(:segment)', 'Paket::update/$1');
        $routes->delete('delete/(:segment)', 'Paket::delete/$1');
    });

    // Pelanggan Routes
    $routes->group('pelanggan', function ($routes) {
        $routes->get('/', 'Pelanggan::index');
        $routes->get('create', 'Pelanggan::create');
        $routes->get('edit/(:num)', 'Pelanggan::edit/$1');
        $routes->get('getNewId', 'Pelanggan::getNewId');
        $routes->get('getPelanggan', 'Pelanggan::getPelanggan');
        $routes->post('store', 'Pelanggan::store');
        $routes->post('update/(:num)', 'Pelanggan::update/$1');
        $routes->delete('delete/(:num)', 'Pelanggan::delete/$1');
    });
});
