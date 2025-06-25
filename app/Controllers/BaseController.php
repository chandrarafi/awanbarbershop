<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Database connection
     *
     * @var \CodeIgniter\Database\BaseConnection
     */
    protected $db;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');

        // Initialize database
        $this->db = \Config\Database::connect();
    }

    /**
     * Memeriksa dan memperbarui status booking yang expired
     * 
     * @return bool
     */
    protected function checkExpiredBookings()
    {
        try {
            $bookingModel = new \App\Models\BookingModel();
            $detailBookingModel = new \App\Models\DetailBookingModel();
            $notificationModel = new \App\Models\NotificationModel();
            $pelangganModel = new \App\Models\PelangganModel();

            // Ambil semua booking yang belum dibayar dan sudah expired
            $expiredBookings = $bookingModel->where('jenispembayaran', 'Belum Bayar')
                ->where('status', 'pending')
                ->where('expired_at <', date('Y-m-d H:i:s'))
                ->findAll();

            if (empty($expiredBookings)) {
                return true;
            }

            log_message('debug', 'BaseController: Found ' . count($expiredBookings) . ' expired bookings to clean up');

            $this->db->transBegin();

            foreach ($expiredBookings as $booking) {
                log_message('debug', 'BaseController: Auto cleaning expired booking: ' . $booking['kdbooking']);

                // Update status booking menjadi expired
                $bookingModel->update($booking['kdbooking'], [
                    'status' => 'expired'
                ]);

                // Ambil detail booking
                $details = $detailBookingModel->where('kdbooking', $booking['kdbooking'])->findAll();

                // Update status detail booking dan bebaskan karyawan
                foreach ($details as $detail) {
                    // Update status detail booking menjadi dibatalkan
                    $detailBookingModel->update($detail['iddetail'], [
                        'status' => '0' // 0 = Dibatalkan/Expired
                    ]);

                    // Log informasi karyawan yang dibebaskan
                    if (!empty($detail['idkaryawan'])) {
                        log_message('info', 'BaseController: Membebaskan karyawan ID: ' . $detail['idkaryawan'] . ' dari booking: ' . $booking['kdbooking']);
                    }
                }

                // Buat notifikasi untuk admin tentang booking yang expired
                $pelanggan = $pelangganModel->find($booking['idpelanggan']);
                if ($pelanggan) {
                    $notificationModel->createNotification(
                        'booking_expired',
                        'Booking Expired',
                        'Booking ' . $booking['kdbooking'] . ' atas nama ' . ($pelanggan['nama_lengkap'] ?? 'Pelanggan') . ' telah expired karena tidak melakukan pembayaran dalam waktu yang ditentukan.',
                        $booking['kdbooking'],
                        null
                    );
                }
            }

            $this->db->transCommit();
            return true;
        } catch (\Exception $e) {
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
            }
            log_message('error', 'BaseController: Error cleaning up expired bookings: ' . $e->getMessage());
            return false;
        }
    }
}
