<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

Events::on('post_controller_constructor', function () {
    // Jalankan cleanup booking secara otomatis di background
    // Hanya dijalankan pada 5% request untuk mengurangi beban server
    if (mt_rand(1, 100) <= 5) {
        helper('filesystem');

        // Cek kapan terakhir kali cleanup dijalankan (max 1x per menit)
        $lockFile = WRITEPATH . 'booking_cleanup.lock';
        $canRun = true;

        if (file_exists($lockFile)) {
            $lastRun = filemtime($lockFile);
            if (time() - $lastRun < 60) { // 60 detik
                $canRun = false;
            }
        }

        if ($canRun) {
            // Update lock file
            write_file($lockFile, date('Y-m-d H:i:s'), 'w');

            // Set timezone
            date_default_timezone_set('Asia/Jakarta');

            // Koneksi ke database
            $db = \Config\Database::connect();

            // Ambil semua booking yang expired
            $expiredBookings = $db->table('booking')
                ->where('jenispembayaran', 'Belum Bayar')
                ->where('status', 'pending')
                ->where('expired_at <', date('Y-m-d H:i:s'))
                ->get()
                ->getResultArray();

            if (!empty($expiredBookings)) {
                $db->transBegin();

                try {
                    $bookingModel = new \App\Models\BookingModel();
                    $detailBookingModel = new \App\Models\DetailBookingModel();

                    foreach ($expiredBookings as $booking) {
                        // Update status booking
                        $bookingModel->update($booking['kdbooking'], [
                            'status' => 'expired'
                        ]);

                        // Update detail booking
                        $detailBookingModel->where('kdbooking', $booking['kdbooking'])
                            ->set(['status' => '0'])
                            ->update();

                        // Log
                        log_message('info', 'Auto Cleanup: Booking ' . $booking['kdbooking'] . ' updated to expired');
                    }

                    $db->transCommit();

                    // Log hasil
                    log_message('info', 'Auto Cleanup: Processed ' . count($expiredBookings) . ' expired bookings');
                } catch (\Exception $e) {
                    $db->transRollback();
                    log_message('error', 'Auto Cleanup Error: ' . $e->getMessage());
                }
            }
        }
    }
});
