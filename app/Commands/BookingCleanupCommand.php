<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\NotificationModel;
use App\Models\PelangganModel;

class BookingCleanupCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Booking';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'booking:cleanup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Membersihkan booking yang sudah expired secara otomatis';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'booking:cleanup [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $start = microtime(true);

        CLI::write('Memulai proses pembersihan booking expired...', 'green');

        // Set timezone Indonesia
        date_default_timezone_set('Asia/Jakarta');
        CLI::write('Timezone: ' . date_default_timezone_get(), 'yellow');
        CLI::write('Server time: ' . date('Y-m-d H:i:s'), 'yellow');

        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $notificationModel = new NotificationModel();
        $pelangganModel = new PelangganModel();

        // Ambil semua booking yang belum dibayar dan sudah expired
        $expiredBookings = $bookingModel->where('jenispembayaran', 'Belum Bayar')
            ->where('status', 'pending')
            ->where('expired_at <', date('Y-m-d H:i:s'))
            ->findAll();

        CLI::write('Ditemukan ' . count($expiredBookings) . ' booking yang perlu diupdate.', 'yellow');

        if (empty($expiredBookings)) {
            $end = microtime(true);
            $execution_time = ($end - $start);
            CLI::write('Tidak ada booking yang perlu diperbarui. Selesai dalam ' . number_format($execution_time, 4) . ' detik', 'green');
            return;
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $processed = 0;

            foreach ($expiredBookings as $booking) {
                CLI::write('Memproses booking: ' . $booking['kdbooking'], 'yellow');

                // Update status booking menjadi expired
                $result = $bookingModel->update($booking['kdbooking'], [
                    'status' => 'expired'
                ]);

                if (!$result) {
                    CLI::error('Gagal memperbarui booking ' . $booking['kdbooking'] . ': ' . json_encode($bookingModel->errors()));
                    continue;
                }

                // Ambil detail booking
                $details = $detailBookingModel->where('kdbooking', $booking['kdbooking'])->findAll();

                // Update status detail booking
                foreach ($details as $detail) {
                    $detailBookingModel->update($detail['iddetail'], [
                        'status' => '0' // 0 = Dibatalkan/Expired
                    ]);
                    CLI::write('  - Detail booking ' . $detail['iddetail'] . ' diperbarui menjadi dibatalkan.', 'white');
                }

                // Buat notifikasi untuk admin
                $pelanggan = $pelangganModel->find($booking['idpelanggan']);
                if ($pelanggan) {
                    $notificationModel->createNotification(
                        'booking_expired',
                        'Booking Expired',
                        'Booking ' . $booking['kdbooking'] . ' atas nama ' . ($pelanggan['nama_lengkap'] ?? 'Pelanggan') . ' telah expired karena tidak melakukan pembayaran dalam waktu yang ditentukan.',
                        $booking['kdbooking'],
                        null
                    );
                    CLI::write('  - Notifikasi untuk admin dibuat.', 'white');
                }

                $processed++;
                CLI::write('  √ Booking ' . $booking['kdbooking'] . ' berhasil diperbarui menjadi expired.', 'green');

                // Log ke file log sistem
                log_message('info', 'BookingCleanup: Booking ' . $booking['kdbooking'] . ' diupdate menjadi expired oleh sistem.');
            }

            $db->transCommit();

            $end = microtime(true);
            $execution_time = ($end - $start);

            CLI::write('Berhasil memproses ' . $processed . ' booking expired. Selesai dalam ' . number_format($execution_time, 4) . ' detik', 'green');

            // Tulis ke file log
            $logFile = WRITEPATH . 'logs/booking_cleanup_' . date('Y-m-d') . '.log';
            $logContent = date('Y-m-d H:i:s') . " - Processed {$processed} expired bookings in " . number_format($execution_time, 4) . " seconds\n";
            file_put_contents($logFile, $logContent, FILE_APPEND);
        } catch (\Exception $e) {
            $db->transRollback();
            CLI::error('Terjadi kesalahan: ' . $e->getMessage());
            log_message('error', 'BookingCleanup Error: ' . $e->getMessage());
        }
    }
}
