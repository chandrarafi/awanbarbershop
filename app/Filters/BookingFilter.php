<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\NotificationModel;
use App\Models\PelangganModel;

class BookingFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Periksa dan perbarui status booking yang expired
        $this->cleanupExpiredBookings();

        return;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }

    /**
     * Membersihkan booking yang sudah expired tapi belum diupdate statusnya
     */
    private function cleanupExpiredBookings()
    {
        try {
            $bookingModel = new BookingModel();
            $detailBookingModel = new DetailBookingModel();
            $notificationModel = new NotificationModel();
            $pelangganModel = new PelangganModel();
            $db = \Config\Database::connect();

            // Ambil semua booking yang belum dibayar dan sudah expired
            $expiredBookings = $bookingModel->where('jenispembayaran', 'Belum Bayar')
                ->where('status', 'pending')
                ->where('expired_at <', date('Y-m-d H:i:s'))
                ->findAll();

            if (empty($expiredBookings)) {
                return true;
            }

            log_message('debug', 'BookingFilter: Found ' . count($expiredBookings) . ' expired bookings to clean up');

            $db->transBegin();

            foreach ($expiredBookings as $booking) {
                log_message('debug', 'BookingFilter: Auto cleaning expired booking: ' . $booking['kdbooking']);

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
                        log_message('info', 'BookingFilter: Membebaskan karyawan ID: ' . $detail['idkaryawan'] . ' dari booking: ' . $booking['kdbooking']);
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

            $db->transCommit();
            log_message('info', 'BookingFilter: Successfully processed ' . count($expiredBookings) . ' expired bookings');
            return true;
        } catch (\Exception $e) {
            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }
            log_message('error', 'BookingFilter: Error cleaning up expired bookings: ' . $e->getMessage());
            return false;
        }
    }
}
