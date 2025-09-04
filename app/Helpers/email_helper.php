<?php

use CodeIgniter\Email\Email;

if (!function_exists('send_otp_email')) {
    function send_otp_email($to, $otp)
    {
        $email = \Config\Services::email();

        $email->setFrom('noreply@awanbarbershop.com', 'Awan Barbershop');
        $email->setTo($to);
        $email->setSubject('Kode OTP Verifikasi - Awan Barbershop');

        $message = "
        <h2>Verifikasi Email Anda</h2>
        <p>Terima kasih telah mendaftar di Awan Barbershop. Berikut adalah kode OTP Anda:</p>
        <h1 style='font-size: 32px; letter-spacing: 5px; background: #f1f1f1; padding: 10px; text-align: center;'>{$otp}</h1>
        <p>Kode OTP ini akan kadaluarsa dalam 15 menit.</p>
        <p>Jika Anda tidak merasa mendaftar di Awan Barbershop, abaikan email ini.</p>
        ";

        $email->setMessage($message);
        $email->setMailType('html');

        return $email->send();
    }
}

if (!function_exists('send_booking_status_email')) {
    function send_booking_status_email($to, $nama, $kdbooking, $status, $tanggal, $jam)
    {
        $email = \Config\Services::email();

        $email->setFrom('noreply@awanbarbershop.com', 'Awan Barbershop');
        $email->setTo($to);

        if ($status === 'confirmed') {
            $email->setSubject('Booking Anda Telah Dikonfirmasi - Awan Barbershop');
            $message = "
            <h2>Booking Anda Telah Dikonfirmasi</h2>
            <p>Halo {$nama},</p>
            <p>Booking Anda dengan kode <strong>{$kdbooking}</strong> telah dikonfirmasi.</p>
            <p>Detail booking:</p>
            <ul>
                <li>Tanggal: {$tanggal}</li>
                <li>Jam: {$jam}</li>
            </ul>
            <p>Mohon datang tepat waktu sesuai jadwal yang telah ditentukan.</p>
            <p>Terima kasih telah memilih Awan Barbershop.</p>
            ";
        } else if ($status === 'rejected') {
            $email->setSubject('Booking Anda Ditolak - Awan Barbershop');
            $message = "
            <h2>Booking Anda Tidak Dapat Diproses</h2>
            <p>Halo {$nama},</p>
            <p>Mohon maaf, booking Anda dengan kode <strong>{$kdbooking}</strong> tidak dapat kami proses saat ini.</p>
            <p>Detail booking:</p>
            <ul>
                <li>Tanggal: {$tanggal}</li>
                <li>Jam: {$jam}</li>
            </ul>
            <p>Silakan melakukan booking ulang di waktu yang berbeda atau hubungi kami untuk informasi lebih lanjut.</p>
            <p>Terima kasih atas pengertian Anda.</p>
            ";
        }

        $email->setMessage($message);
        $email->setMailType('html');

        return $email->send();
    }
}
