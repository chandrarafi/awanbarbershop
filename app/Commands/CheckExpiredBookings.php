<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\NotificationModel;
use App\Models\PelangganModel;

/**
 * Command untuk memeriksa dan memperbarui booking yang sudah expired
 */
class CheckExpiredBookings extends BaseCommand
{
    /**
     * Command group
     *
     * @var string
     */
    protected $group = 'Booking';

    /**
     * Command name
     *
     * @var string
     */
    protected $name = 'booking:check-expired';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Memeriksa dan memperbarui booking yang sudah expired';

    /**
     * Command usage
     *
     * @var string
     */
    protected $usage = 'booking:check-expired [options]';

    /**
     * Command arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * Command options
     *
     * @var array
     */
    protected $options = [
        '--force' => 'Memaksa pembaruan status meskipun tidak melewati waktu expired',
        '--booking' => 'Kode booking spesifik yang akan diperiksa'
    ];

    /**
     * Run the command
     *
     * @param array $params
     * @return void
     */
    public function run(array $params)
    {
        $force = array_key_exists('force', $params) || array_key_exists('f', $params);
        $specificBooking = $params['booking'] ?? null;

        CLI::write('Memeriksa booking yang expired...', 'green');
        CLI::write('Force mode: ' . ($force ? 'Ya' : 'Tidak'), 'yellow');
        CLI::write('Specific booking: ' . ($specificBooking ?: 'Tidak ada'), 'yellow');

        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();
        $notificationModel = new NotificationModel();
        $pelangganModel = new PelangganModel();
        $db = \Config\Database::connect();

        // Buat query untuk booking yang belum dibayar
        $builder = $db->table('booking')
            ->where('jenispembayaran', 'Belum Bayar')
            ->where('status', 'pending');

        // Jika tidak dipaksa, tambahkan filter waktu expired
        if (!$force) {
            $builder->where('expired_at <', date('Y-m-d H:i:s'));
        }

        // Jika ada kode booking spesifik
        if ($specificBooking) {
            $builder->where('kdbooking', $specificBooking);

            // Tambahkan debugging untuk booking spesifik
            $specificBookingData = $bookingModel->find($specificBooking);
            if ($specificBookingData) {
                CLI::write('===== DATA BOOKING SPESIFIK =====', 'green');
                CLI::write('KD Booking: ' . $specificBookingData['kdbooking'], 'white');
                CLI::write('Status: ' . $specificBookingData['status'], 'white');
                CLI::write('Jenis Pembayaran: ' . $specificBookingData['jenispembayaran'], 'white');
                CLI::write('Expired At: ' . ($specificBookingData['expired_at'] ?? 'Tidak ada'), 'white');
                CLI::write('Current Time: ' . date('Y-m-d H:i:s'), 'white');
                CLI::write('================================', 'green');
            } else {
                CLI::write('Booking spesifik tidak ditemukan: ' . $specificBooking, 'red');
            }
        }

        // Tampilkan informasi kondisi query
        $conditions = 'WHERE: ';

        // Dapatkan informasi query dari builder
        $queryString = $builder->getCompiledSelect(false);
        CLI::write('SQL Query: ' . $queryString, 'cyan');

        // Ambil booking yang expired
        $expiredBookings = $builder->get()->getResultArray();

        CLI::write('Query result count: ' . count($expiredBookings), 'yellow');
        if (!empty($expiredBookings)) {
            CLI::write('First booking in result: ' . json_encode($expiredBookings[0]), 'yellow');
        }

        if (empty($expiredBookings)) {
            CLI::write('Tidak ada booking yang perlu diperbarui.', 'yellow');
            return;
        }

        CLI::write('Ditemukan ' . count($expiredBookings) . ' booking yang perlu diperbarui:', 'yellow');

        $db->transBegin();
        $processedCount = 0;

        try {
            foreach ($expiredBookings as $booking) {
                CLI::write('  - ' . $booking['kdbooking'] . ' (expired pada: ' . $booking['expired_at'] . ')', 'yellow');

                // Debug informasi
                CLI::write('    Status: ' . $booking['status'], 'white');
                CLI::write('    Jenis Pembayaran: ' . $booking['jenispembayaran'], 'white');
                CLI::write('    Expired At: ' . $booking['expired_at'], 'white');
                CLI::write('    Current Time: ' . date('Y-m-d H:i:s'), 'white');

                if (strtotime($booking['expired_at']) > time() && !$force) {
                    CLI::write('    Booking belum expired, dilewati.', 'cyan');
                    continue;
                }

                // Update status booking menjadi expired
                $result = $bookingModel->update($booking['kdbooking'], [
                    'status' => 'expired'
                ]);

                // Debug informasi update
                if ($result) {
                    CLI::write('    Status booking berhasil diperbarui menjadi expired.', 'green');
                } else {
                    CLI::write('    Gagal memperbarui status booking: ' . json_encode($bookingModel->errors()), 'red');
                    continue;
                }

                // Ambil detail booking
                $details = $detailBookingModel->where('kdbooking', $booking['kdbooking'])->findAll();

                // Update status detail booking
                foreach ($details as $detail) {
                    $detailBookingModel->update($detail['iddetail'], [
                        'status' => '0' // 0 = Dibatalkan/Expired
                    ]);
                    CLI::write('    Detail booking ' . $detail['iddetail'] . ' diperbarui menjadi dibatalkan.', 'green');
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
                    CLI::write('    Notifikasi untuk admin dibuat.', 'green');
                }

                $processedCount++;
            }

            $db->transCommit();
            CLI::write('Berhasil memproses ' . $processedCount . ' booking expired.', 'green');
        } catch (\Exception $e) {
            $db->transRollback();
            CLI::error('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
