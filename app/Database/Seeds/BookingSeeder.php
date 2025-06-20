<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $pelangganModel = new \App\Models\PelangganModel();
        $karyawanModel = new \App\Models\KaryawanModel();
        $paketModel = new \App\Models\PaketModel();

        // Ambil data pelanggan, karyawan, dan paket yang sudah ada
        $pelanggan = $pelangganModel->findAll();
        $karyawan = $karyawanModel->findAll();
        $paket = $paketModel->findAll();

        if (empty($pelanggan) || empty($karyawan) || empty($paket)) {
            echo "Data pelanggan, karyawan, atau paket tidak ditemukan. Silakan jalankan seeder lainnya terlebih dahulu.\n";
            return;
        }

        // Data booking
        $bookingData = [
            [
                'kdbooking' => 'BK-20250619-0001',
                'idpelanggan' => $pelanggan[0]['idpelanggan'],
                'tanggal_booking' => '2025-06-19',
                'status' => 'completed',
                'jenispembayaran' => 'Lunas',
                'jumlahbayar' => 50000,
                'total' => 50000,
                'created_at' => Time::now()->subDays(5)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(4)->toDateTimeString()
            ],
            [
                'kdbooking' => 'BK-20250619-0002',
                'idpelanggan' => $pelanggan[0]['idpelanggan'],
                'tanggal_booking' => '2025-06-20',
                'status' => 'confirmed',
                'jenispembayaran' => 'DP',
                'jumlahbayar' => 25000,
                'total' => 75000,
                'created_at' => Time::now()->subDays(3)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(2)->toDateTimeString()
            ],
            [
                'kdbooking' => 'BK-20250619-0003',
                'idpelanggan' => $pelanggan[0]['idpelanggan'],
                'tanggal_booking' => '2025-06-25',
                'status' => 'pending',
                'jenispembayaran' => 'DP',
                'jumlahbayar' => 0,
                'total' => 100000,
                'created_at' => Time::now()->subDays(1)->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString()
            ],
        ];

        // Simpan data booking
        $this->db->table('booking')->insertBatch($bookingData);

        // Data detail booking
        $detailBookingData = [
            [
                'iddetail' => 'DTL-20250619-0001',
                'tgl' => '2025-06-19',
                'kdbooking' => 'BK-20250619-0001',
                'kdpaket' => $paket[0]['idpaket'],
                'nama_paket' => $paket[0]['namapaket'],
                'deskripsi' => $paket[0]['deskripsi'],
                'harga' => $paket[0]['harga'],
                'jamstart' => '10:00',
                'jamend' => '11:00',
                'status' => '3', // Selesai
                'idkaryawan' => $karyawan[0]['idkaryawan'],
                'created_at' => Time::now()->subDays(5)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(4)->toDateTimeString()
            ],
            [
                'iddetail' => 'DTL-20250619-0002',
                'tgl' => '2025-06-20',
                'kdbooking' => 'BK-20250619-0002',
                'kdpaket' => $paket[1]['idpaket'],
                'nama_paket' => $paket[1]['namapaket'],
                'deskripsi' => $paket[1]['deskripsi'],
                'harga' => $paket[1]['harga'],
                'jamstart' => '14:00',
                'jamend' => '15:00',
                'status' => '2', // Terkonfirmasi
                'idkaryawan' => $karyawan[1]['idkaryawan'],
                'created_at' => Time::now()->subDays(3)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(2)->toDateTimeString()
            ],
            [
                'iddetail' => 'DTL-20250619-0003',
                'tgl' => '2025-06-25',
                'kdbooking' => 'BK-20250619-0003',
                'kdpaket' => $paket[2]['idpaket'],
                'nama_paket' => $paket[2]['namapaket'],
                'deskripsi' => $paket[2]['deskripsi'],
                'harga' => $paket[2]['harga'],
                'jamstart' => '16:00',
                'jamend' => '17:00',
                'status' => '1', // Pending
                'idkaryawan' => $karyawan[0]['idkaryawan'],
                'created_at' => Time::now()->subDays(1)->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString()
            ],
        ];

        // Simpan data detail booking
        $this->db->table('detail_booking')->insertBatch($detailBookingData);

        // Data pembayaran
        $pembayaranData = [
            [
                'fakturbooking' => 'BK-20250619-0001',
                'total_bayar' => 50000,
                'grandtotal' => 50000,
                'metode' => 'cash',
                'status' => 'paid',
                'jenis' => 'Lunas',
                'created_at' => Time::now()->subDays(4)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(4)->toDateTimeString()
            ],
            [
                'fakturbooking' => 'BK-20250619-0002',
                'total_bayar' => 25000,
                'grandtotal' => 75000,
                'metode' => 'transfer',
                'status' => 'paid',
                'jenis' => 'DP',
                'created_at' => Time::now()->subDays(2)->toDateTimeString(),
                'updated_at' => Time::now()->subDays(2)->toDateTimeString()
            ]
        ];

        // Simpan data pembayaran
        $this->db->table('pembayaran')->insertBatch($pembayaranData);

        echo "Booking seeder: Data booking berhasil ditambahkan\n";
    }
}
