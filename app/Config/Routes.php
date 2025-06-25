<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Cron Routes
$routes->get('/cron/check-expired-bookings', 'Cron::checkExpiredBookings');
$routes->get('/cron/expire-booking/(:segment)', 'Cron::forceExpireBooking/$1');
$routes->get('/cron/check-booking/(:segment)', 'Cron::checkBookingStatus/$1');
$routes->get('/cron/list-pending-bookings', 'Cron::listPendingBookings');
$routes->get('/customer/check-expired-bookings', 'Customer\Booking::checkAllExpiredBookings');

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

    // Tambahkan route untuk profil pelanggan
    $routes->get('profil', 'Pelanggan::profile', ['filter' => 'auth']);
    $routes->post('update-profil', 'Pelanggan::updateProfile', ['filter' => 'auth']);

    // Route Booking Pelanggan
    $routes->group('booking', ['filter' => 'auth'], function ($routes) {
        $routes->get('/', 'Customer\Booking::index');
        $routes->get('create', 'Customer\Booking::create');
        $routes->post('store', 'Customer\Booking::store');
        $routes->get('detail/(:segment)', 'Customer\Booking::detail/$1');
        $routes->get('payment/(:segment)', 'Customer\Booking::payment/$1');
        $routes->post('savePayment', 'Customer\Booking::savePayment');
        $routes->get('getBookings', 'Customer\Booking::getBookings');
        $routes->get('getAvailableKaryawan', 'Customer\Booking::getAvailableKaryawan');
        $routes->get('checkAvailability', 'Customer\Booking::checkAvailability');
        $routes->get('create-test-notification', 'Customer\Booking::createTestNotification');
        $routes->post('expire/(:segment)', 'Customer\Booking::expire/$1');
    });
});

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin::index', ['filter' => 'role:admin,manager']);
    $routes->get('dashboard', 'Admin::index');

    // Notifications routes
    $routes->group('notifications', function ($routes) {
        $routes->get('unread', 'Admin\NotificationController::getUnreadNotifications');
        $routes->post('mark-read', 'Admin\NotificationController::markAsRead');
        $routes->post('mark-all-read', 'Admin\NotificationController::markAllAsRead');
        $routes->get('view/(:num)', 'Admin\NotificationController::viewDetail/$1');
        $routes->get('create-test', 'Admin\NotificationController::createTest');
        $routes->get('view-all', 'Admin\NotificationController::viewAll');
        $routes->get('all', 'Admin\NotificationController::allNotifications');
    });

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

    // Booking Routes
    $routes->group('booking', ['filter' => 'role:admin,manager'], function ($routes) {
        $routes->get('/', 'Admin\BookingNewController::index');
        $routes->get('create', 'Admin\BookingNewController::create');
        $routes->get('show/(:segment)', 'Admin\BookingNewController::show/$1');
        $routes->get('edit/(:segment)', 'Admin\BookingNewController::edit/$1');
        $routes->post('store', 'Admin\BookingNewController::store');
        $routes->post('update', 'Admin\BookingNewController::update');
        $routes->get('getBookings', 'Admin\BookingNewController::getBookings');
        $routes->get('getAllPelanggan', 'Admin\BookingNewController::getAllPelanggan');
        $routes->get('getAvailableKaryawan', 'Admin\BookingNewController::getAvailableKaryawan');
        $routes->get('getTimeSlotAvailability', 'Admin\BookingNewController::getTimeSlotAvailability');
        $routes->get('check-availability', 'Admin\BookingNewController::checkAvailability');
        $routes->post('storePelanggan', 'Admin\BookingNewController::storePelanggan');
        $routes->post('updateStatus', 'Admin\BookingNewController::updateStatus');
        $routes->post('getPaymentInfo', 'Admin\BookingNewController::getPaymentInfo');
        $routes->post('print-invoice', 'Admin\BookingNewController::print_invoice');
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

    // Pengeluaran Routes
    $routes->group('pengeluaran', ['filter' => 'role:admin,manager'], function ($routes) {
        $routes->get('/', 'Admin\PengeluaranController::index');
        $routes->get('create', 'Admin\PengeluaranController::create');
        $routes->post('store', 'Admin\PengeluaranController::store');
        $routes->get('edit/(:segment)', 'Admin\PengeluaranController::edit/$1');
        $routes->post('update/(:segment)', 'Admin\PengeluaranController::update/$1');
        $routes->get('delete/(:segment)', 'Admin\PengeluaranController::delete/$1');
        $routes->get('getPengeluaran', 'Admin\PengeluaranController::getPengeluaran');
    });
});

// Customer Booking Routes
$routes->group('customer/booking', function ($routes) {
    $routes->get('/', 'Customer\Booking::index', ['filter' => 'auth:customer']);
    $routes->get('create', 'Customer\Booking::create', ['filter' => 'auth:customer']);
    $routes->post('store', 'Customer\Booking::store', ['filter' => 'auth:customer']);
    $routes->get('detail/(:segment)', 'Customer\Booking::detail/$1', ['filter' => 'auth:customer']);
    $routes->get('payment/(:segment)', 'Customer\Booking::payment/$1', ['filter' => ['auth:customer', 'booking:pending']]);
    $routes->post('payment/save', 'Customer\Booking::savePayment', ['filter' => 'auth:customer']);
    $routes->get('expire/(:segment)', 'Customer\Booking::expire/$1', ['filter' => 'auth:customer']);
    $routes->get('check-status/(:segment)', 'Customer\Booking::checkStatus/$1', ['filter' => 'auth:customer']);

    // AJAX Endpoints
    $routes->get('check-availability', 'Customer\Booking::checkAvailability');
    $routes->get('get-available-karyawan', 'Customer\Booking::getAvailableKaryawan');
    $routes->get('get-bookings', 'Customer\Booking::getBookings', ['filter' => 'auth:customer']);
    $routes->get('create-test-notification', 'Customer\Booking::createTestNotification');
});
