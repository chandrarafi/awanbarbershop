<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\NotificationModel;
use App\Models\PelangganModel;

/**
 * Booking WebSocket Server
 * Kelas ini menangani koneksi WebSocket untuk pemantauan status booking
 */
class BookingWebSocket implements MessageComponentInterface
{
    protected $clients;
    protected $bookingModel;
    protected $detailBookingModel;
    protected $notificationModel;
    protected $pelangganModel;
    protected $db;
    protected $bookingClients = []; // Mapping booking codes to clients

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->bookingModel = new BookingModel();
        $this->detailBookingModel = new DetailBookingModel();
        $this->notificationModel = new NotificationModel();
        $this->pelangganModel = new PelangganModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * When a new connection is opened
     * @param ConnectionInterface $conn The socket/connection that just connected to the server
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * When a message is received from a client
     * @param ConnectionInterface $from The socket/connection that sent the message
     * @param string $msg The message received
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $data = json_decode($msg, true);

            if (!is_array($data) || !isset($data['type'])) {
                echo "Invalid message format\n";
                return;
            }

            echo "Received message: " . $msg . "\n";

            switch ($data['type']) {
                case 'register':
                    $this->handleRegister($from, $data);
                    break;

                case 'check_booking':
                    $this->handleCheckBooking($from, $data);
                    break;

                case 'unregister':
                    $this->handleUnregister($from, $data);
                    break;

                default:
                    echo "Unknown message type: {$data['type']}\n";
                    break;
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    /**
     * When a connection is closed
     * @param ConnectionInterface $conn The socket/connection that is closing
     */
    public function onClose(ConnectionInterface $conn)
    {
        // Remove the connection
        $this->clients->detach($conn);

        // Remove from booking clients if exists
        foreach ($this->bookingClients as $bookingCode => $clients) {
            if (($key = array_search($conn, $clients)) !== false) {
                unset($this->bookingClients[$bookingCode][$key]);

                // If no more clients for this booking, remove the booking code
                if (empty($this->bookingClients[$bookingCode])) {
                    unset($this->bookingClients[$bookingCode]);
                }
            }
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * When an error occurs
     * @param ConnectionInterface $conn The socket/connection that experienced the error
     * @param \Exception $e The exception that was thrown
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Handle register message
     * @param ConnectionInterface $conn The connection that sent the message
     * @param array $data The message data
     */
    protected function handleRegister(ConnectionInterface $conn, array $data)
    {
        if (!isset($data['booking_code'])) {
            echo "Missing booking_code in register message\n";
            return;
        }

        $bookingCode = $data['booking_code'];

        // Register the client for this booking code
        if (!isset($this->bookingClients[$bookingCode])) {
            $this->bookingClients[$bookingCode] = [];
        }

        $this->bookingClients[$bookingCode][] = $conn;

        echo "Client {$conn->resourceId} registered for booking {$bookingCode}\n";

        // Check if the booking is already expired
        $booking = $this->bookingModel->find($bookingCode);

        if ($booking) {
            if ($booking['status'] === 'expired') {
                $this->notifyBookingExpired($bookingCode);
            } else if (
                $booking['jenispembayaran'] === 'Belum Bayar' &&
                $booking['status'] === 'pending' &&
                !empty($booking['expired_at']) &&
                strtotime($booking['expired_at']) < time()
            ) {
                // The booking is expired but not marked as such
                $this->expireBooking($bookingCode);
            }
        }
    }

    /**
     * Handle check booking message
     * @param ConnectionInterface $conn The connection that sent the message
     * @param array $data The message data
     */
    protected function handleCheckBooking(ConnectionInterface $conn, array $data)
    {
        if (!isset($data['booking_code'])) {
            echo "Missing booking_code in check_booking message\n";
            return;
        }

        $bookingCode = $data['booking_code'];
        $booking = $this->bookingModel->find($bookingCode);

        if (!$booking) {
            $response = [
                'type' => 'booking_not_found',
                'booking_code' => $bookingCode
            ];
            $conn->send(json_encode($response));
            return;
        }

        // Check if the booking has expired
        if (
            $booking['jenispembayaran'] === 'Belum Bayar' &&
            $booking['status'] === 'pending' &&
            !empty($booking['expired_at']) &&
            strtotime($booking['expired_at']) < time()
        ) {
            // Expire the booking
            $this->expireBooking($bookingCode);
        } else {
            // Send current booking status
            $response = [
                'type' => 'booking_status',
                'booking_code' => $bookingCode,
                'status' => $booking['status'],
                'expired_at' => $booking['expired_at'] ?? null
            ];
            $conn->send(json_encode($response));
        }
    }

    /**
     * Handle unregister message
     * @param ConnectionInterface $conn The connection that sent the message
     * @param array $data The message data
     */
    protected function handleUnregister(ConnectionInterface $conn, array $data)
    {
        if (!isset($data['booking_code'])) {
            echo "Missing booking_code in unregister message\n";
            return;
        }

        $bookingCode = $data['booking_code'];

        // Unregister the client for this booking code
        if (isset($this->bookingClients[$bookingCode])) {
            if (($key = array_search($conn, $this->bookingClients[$bookingCode])) !== false) {
                unset($this->bookingClients[$bookingCode][$key]);

                // If no more clients for this booking, remove the booking code
                if (empty($this->bookingClients[$bookingCode])) {
                    unset($this->bookingClients[$bookingCode]);
                }

                echo "Client {$conn->resourceId} unregistered from booking {$bookingCode}\n";
            }
        }
    }

    /**
     * Check for expired bookings and notify clients
     */
    public function checkExpiredBookings()
    {
        try {
            // Get all pending bookings that have expired
            $expiredBookings = $this->bookingModel->where('jenispembayaran', 'Belum Bayar')
                ->where('status', 'pending')
                ->where('expired_at <', date('Y-m-d H:i:s'))
                ->findAll();

            echo "Found " . count($expiredBookings) . " expired bookings\n";

            foreach ($expiredBookings as $booking) {
                $this->expireBooking($booking['kdbooking']);
            }
        } catch (\Exception $e) {
            echo "Error checking expired bookings: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Expire a booking and notify clients
     * @param string $bookingCode The booking code to expire
     */
    protected function expireBooking($bookingCode)
    {
        $this->db->transBegin();

        try {
            // Update status booking menjadi expired
            $this->bookingModel->update($bookingCode, [
                'status' => 'expired'
            ]);

            // Ambil detail booking
            $details = $this->detailBookingModel->where('kdbooking', $bookingCode)->findAll();

            // Update status detail booking dan bebaskan karyawan
            foreach ($details as $detail) {
                // Update status detail booking menjadi dibatalkan
                $this->detailBookingModel->update($detail['iddetail'], [
                    'status' => '0' // 0 = Dibatalkan/Expired
                ]);
            }

            // Buat notifikasi untuk admin tentang booking yang expired
            $booking = $this->bookingModel->find($bookingCode);
            if ($booking) {
                $pelanggan = $this->pelangganModel->find($booking['idpelanggan']);
                if ($pelanggan) {
                    $this->notificationModel->createNotification(
                        'booking_expired',
                        'Booking Expired',
                        'Booking ' . $bookingCode . ' atas nama ' . ($pelanggan['nama_lengkap'] ?? 'Pelanggan') . ' telah expired karena tidak melakukan pembayaran dalam waktu yang ditentukan.',
                        $bookingCode,
                        null
                    );
                }
            }

            $this->db->transCommit();

            // Notify all clients for this booking
            $this->notifyBookingExpired($bookingCode);

            echo "Booking {$bookingCode} expired successfully\n";
        } catch (\Exception $e) {
            $this->db->transRollback();
            echo "Error expiring booking {$bookingCode}: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Notify clients that a booking has expired
     * @param string $bookingCode The booking code that expired
     */
    protected function notifyBookingExpired($bookingCode)
    {
        $message = [
            'type' => 'booking_expired',
            'booking_code' => $bookingCode
        ];

        $encodedMessage = json_encode($message);

        // Notify specific clients for this booking
        if (isset($this->bookingClients[$bookingCode])) {
            foreach ($this->bookingClients[$bookingCode] as $client) {
                $client->send($encodedMessage);
            }
        }

        echo "Notified clients about expired booking {$bookingCode}\n";
    }
}
