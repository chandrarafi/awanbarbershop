<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\NotificationModel;
use App\Models\PelangganModel;

class Cron extends Controller
{
    /**
     * Memeriksa dan memperbarui booking yang sudah expired
     */
    public function checkExpiredBookings()
    {

        $apiKey = $this->request->getGet('key');
        if ($apiKey !== 'awanbarbershop_secret_key') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $notificationModel = new NotificationModel();
        $pelangganModel = new PelangganModel();
        $db = \Config\Database::connect();


        log_message('info', 'Cron: Memeriksa booking yang expired...');


        $currentTime = date('Y-m-d H:i:s');
        $expiredBookings = $bookingModel->builder()
            ->where('jenispembayaran', 'Belum Bayar')
            ->where('status', 'pending')
            ->where('expired_at <', $currentTime)
            ->get()
            ->getResultArray();

        $debugInfo = [
            'current_time' => $currentTime,
            'query' => $db->getLastQuery()->getQuery(),
            'found_bookings' => count($expiredBookings)
        ];

        log_message('info', 'Cron: Debug info: ' . json_encode($debugInfo));
        log_message('info', 'Cron: Ditemukan ' . count($expiredBookings) . ' booking yang expired');

        if (empty($expiredBookings)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Tidak ada booking yang expired',
                'processed' => 0,
                'debug_info' => $debugInfo
            ]);
        }

        $db->transBegin();
        $processed = 0;
        $processedBookings = [];

        try {
            foreach ($expiredBookings as $booking) {
                log_message('info', 'Cron: Memproses booking: ' . $booking['kdbooking']);
                log_message('debug', 'Cron: Detail booking: ' . json_encode($booking));


                if (strtotime($booking['expired_at']) > time()) {
                    log_message('warning', 'Cron: Booking ' . $booking['kdbooking'] . ' belum expired. Waktu expired: ' . $booking['expired_at'] . ', Waktu sekarang: ' . $currentTime);
                    continue;
                }


                $updated = $bookingModel->update($booking['kdbooking'], [
                    'status' => 'expired'
                ]);

                if (!$updated) {
                    log_message('error', 'Cron: Gagal update booking ' . $booking['kdbooking'] . ': ' . json_encode($bookingModel->errors()));
                    continue;
                }


                $details = $detailBookingModel->where('kdbooking', $booking['kdbooking'])->findAll();


                foreach ($details as $detail) {
                    $detailBookingModel->update($detail['iddetail'], [
                        'status' => '0' // 0 = Dibatalkan/Expired
                    ]);


                    if (!empty($detail['idkaryawan'])) {
                        log_message('info', 'Cron: Membebaskan karyawan ID: ' . $detail['idkaryawan'] . ' dari booking: ' . $booking['kdbooking']);
                    }
                }


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

                $processed++;
                $processedBookings[] = $booking['kdbooking'];
                log_message('info', 'Cron: Booking ' . $booking['kdbooking'] . ' berhasil diupdate menjadi expired');
            }

            $db->transCommit();
            log_message('info', 'Cron: Semua booking expired berhasil diproses (' . $processed . ' booking)');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Berhasil memproses ' . $processed . ' booking expired',
                'processed' => $processed,
                'processed_bookings' => $processedBookings,
                'debug_info' => $debugInfo
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Cron Error: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses booking expired: ' . $e->getMessage(),
                'processed' => 0,
                'debug_info' => $debugInfo
            ]);
        }
    }

    /**
     * Force update status booking menjadi expired
     */
    public function forceExpireBooking($kdbooking = null)
    {

        $apiKey = $this->request->getGet('key');
        if ($apiKey !== 'awanbarbershop_secret_key') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        if (empty($kdbooking)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Kode booking tidak valid'
            ]);
        }

        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $notificationModel = new NotificationModel();
        $pelangganModel = new PelangganModel();
        $db = \Config\Database::connect();


        $booking = $bookingModel->find($kdbooking);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan'
            ]);
        }

        $db->transBegin();

        try {

            $updated = $bookingModel->update($kdbooking, [
                'status' => 'expired'
            ]);

            if (!$updated) {
                $db->transRollback();
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal update booking: ' . json_encode($bookingModel->errors())
                ]);
            }


            $details = $detailBookingModel->where('kdbooking', $kdbooking)->findAll();


            foreach ($details as $detail) {
                $detailBookingModel->update($detail['iddetail'], [
                    'status' => '0' // 0 = Dibatalkan/Expired
                ]);
            }


            $pelanggan = $pelangganModel->find($booking['idpelanggan']);
            if ($pelanggan) {
                $notificationModel->createNotification(
                    'booking_expired',
                    'Booking Expired',
                    'Booking ' . $kdbooking . ' atas nama ' . ($pelanggan['nama_lengkap'] ?? 'Pelanggan') . ' telah diupdate secara manual menjadi expired.',
                    $kdbooking,
                    null
                );
            }

            $db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking ' . $kdbooking . ' berhasil diupdate menjadi expired'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Endpoint untuk memeriksa booking tertentu yang sudah expired
     * Endpoint ini dapat diakses melalui browser atau AJAX call
     */
    public function checkBookingStatus($kdbooking = null)
    {

        $apiKey = $this->request->getGet('key');
        if ($apiKey !== 'awanbarbershop_secret_key') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        if (empty($kdbooking)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Kode booking tidak valid'
            ]);
        }

        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();


        $booking = $bookingModel->find($kdbooking);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan'
            ]);
        }


        $debug = [
            'kdbooking' => $kdbooking,
            'status' => $booking['status'],
            'jenispembayaran' => $booking['jenispembayaran'],
            'expired_at' => $booking['expired_at'],
            'current_time' => date('Y-m-d H:i:s'),
            'is_expired' => !empty($booking['expired_at']) && strtotime($booking['expired_at']) < time()
        ];


        $needsUpdate = $booking['status'] == 'pending' &&
            $booking['jenispembayaran'] == 'Belum Bayar' &&
            !empty($booking['expired_at']) &&
            strtotime($booking['expired_at']) < time();

        if ($needsUpdate) {

            $bookingModel->update($kdbooking, [
                'status' => 'expired'
            ]);


            $details = $detailBookingModel->where('kdbooking', $kdbooking)->findAll();
            foreach ($details as $detail) {
                $detailBookingModel->update($detail['iddetail'], [
                    'status' => '0' // 0 = Dibatalkan/Expired
                ]);
            }

            $debug['action_taken'] = 'Booking updated to expired';

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking berhasil diperbarui menjadi expired',
                'booking' => $booking,
                'debug' => $debug
            ]);
        }

        return $this->response->setJSON([
            'status' => 'info',
            'message' => 'Booking tidak perlu diperbarui',
            'booking' => $booking,
            'debug' => $debug
        ]);
    }

    /**
     * Mendapatkan semua booking yang masih pending dan menampilkan status serta waktu expired
     */
    public function listPendingBookings()
    {

        $apiKey = $this->request->getGet('key');
        if ($apiKey !== 'awanbarbershop_secret_key') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $bookingModel = new BookingModel();


        $pendingBookings = $bookingModel->where('status', 'pending')
            ->findAll();

        $formattedBookings = [];
        foreach ($pendingBookings as $booking) {
            $isExpired = !empty($booking['expired_at']) && strtotime($booking['expired_at']) < time();

            $formattedBookings[] = [
                'kdbooking' => $booking['kdbooking'],
                'status' => $booking['status'],
                'jenispembayaran' => $booking['jenispembayaran'],
                'expired_at' => $booking['expired_at'],
                'is_expired' => $isExpired,
                'current_time' => date('Y-m-d H:i:s'),
                'expired_time_diff' => !empty($booking['expired_at']) ?
                    (strtotime($booking['expired_at']) - time()) : null
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'count' => count($formattedBookings),
            'bookings' => $formattedBookings,
            'server_time' => date('Y-m-d H:i:s'),
            'timezone' => date_default_timezone_get()
        ]);
    }
}
