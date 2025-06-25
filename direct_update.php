<?php

/**
 * update_expired.php
 * 
 * Script untuk mengupdate status booking yang sudah expired secara otomatis
 * Dapat dijalankan sebagai cronjob atau dipanggil langsung
 * 
 * Penggunaan:
 * - Melalui PHP CLI: php update_expired.php [--force] [--booking=KODE]
 * - Melalui cron: * * * * * php /path/to/awanbarbershop/update_expired.php
 */

// Output untuk debug
echo "Update Expired script started at " . date('Y-m-d H:i:s') . "\n";

// Parse command line arguments
$force = false;
$specificBooking = null;
$debug = false;

// Check for command line arguments
if ($argc > 1) {
    for ($i = 1; $i < $argc; $i++) {
        if ($argv[$i] === '--force') {
            $force = true;
        } elseif ($argv[$i] === '--debug') {
            $debug = true;
        } elseif (strpos($argv[$i], '--booking=') === 0) {
            $specificBooking = substr($argv[$i], 10);
        }
    }
}

// Connect to database
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'db_awanbarbershop';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

if ($debug) {
    echo "Connected to database successfully\n";
    echo "Force mode: " . ($force ? "Yes" : "No") . "\n";
    echo "Specific booking: " . ($specificBooking ?: "None") . "\n";
    echo "Current time: " . date('Y-m-d H:i:s') . "\n";
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Query dasar untuk booking
$query = "SELECT * FROM booking WHERE status = 'pending' AND jenispembayaran = 'Belum Bayar'";

// Tambahkan filter waktu expired jika tidak dipaksa
if (!$force) {
    $query .= " AND expired_at < '" . date('Y-m-d H:i:s') . "'";
}

// Tambahkan filter kode booking jika ada
if ($specificBooking) {
    $query .= " AND kdbooking = '" . $specificBooking . "'";
}

// Jalankan query
$result = $conn->query($query);

if ($debug) {
    echo "SQL Query: $query\n";
    echo "Found: " . $result->num_rows . " bookings\n";
}

if ($result->num_rows == 0) {
    if ($debug) {
        echo "No booking needs to be updated.\n";
    }
    $conn->close();
    exit(0);
}

// Mulai transaksi
$conn->begin_transaction();

try {
    $processed = 0;
    $failedBookings = [];

    while ($booking = $result->fetch_assoc()) {
        if ($debug) {
            echo "\nProcessing booking: " . $booking['kdbooking'] . "\n";
            echo "Status: " . $booking['status'] . "\n";
            echo "Jenis Pembayaran: " . ($booking['jenispembayaran'] ?: 'Belum Bayar') . "\n";
            echo "Expired At: " . $booking['expired_at'] . "\n";
        }

        // Update status booking menjadi expired
        $updateQuery = "UPDATE booking SET status = 'expired' WHERE kdbooking = '" . $booking['kdbooking'] . "'";
        if ($conn->query($updateQuery) === TRUE) {
            // Update detail booking
            $detailQuery = "UPDATE detail_booking SET status = '0' WHERE kdbooking = '" . $booking['kdbooking'] . "'";
            if ($conn->query($detailQuery) === TRUE) {
                $processed++;
                if ($debug) {
                    echo "✓ Booking " . $booking['kdbooking'] . " status updated to expired\n";
                }
            } else {
                $failedBookings[] = [
                    'kdbooking' => $booking['kdbooking'],
                    'error' => 'Failed to update detail booking: ' . $conn->error
                ];
                if ($debug) {
                    echo "✗ Error updating detail booking: " . $conn->error . "\n";
                }
            }
        } else {
            $failedBookings[] = [
                'kdbooking' => $booking['kdbooking'],
                'error' => 'Failed to update booking: ' . $conn->error
            ];
            if ($debug) {
                echo "✗ Error updating booking: " . $conn->error . "\n";
            }
        }
    }

    // Commit transaksi jika semua berhasil
    $conn->commit();

    if ($debug) {
        echo "\nSummary:\n";
        echo "- Total processed: $processed\n";
        echo "- Total failed: " . count($failedBookings) . "\n";

        if (!empty($failedBookings)) {
            echo "\nFailed bookings:\n";
            foreach ($failedBookings as $failed) {
                echo "- " . $failed['kdbooking'] . ": " . $failed['error'] . "\n";
            }
        }
    }

    // Tulis ke log file
    $logFile = __DIR__ . '/writable/logs/booking_expired_' . date('Y-m-d') . '.log';
    $logContent = date('Y-m-d H:i:s') . " - Processed: $processed, Failed: " . count($failedBookings) . "\n";
    file_put_contents($logFile, $logContent, FILE_APPEND);
} catch (Exception $e) {
    // Rollback transaksi jika ada error
    $conn->rollback();

    if ($debug) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }

    // Tulis error ke log file
    $logFile = __DIR__ . '/writable/logs/booking_expired_' . date('Y-m-d') . '.log';
    $logContent = date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $logContent, FILE_APPEND);
}

// Tutup koneksi
$conn->close();

if ($debug) {
    echo "\nConnection closed\n";
}
