<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Hapus data notifikasi yang ada
        $this->db->table('notifications')->truncate();

        // Data notifikasi
        $data = [
            [
                'type' => 'booking_baru',
                'title' => 'Booking Baru',
                'message' => 'Booking baru oleh Pelanggan Test dengan kode BK-TEST-001',
                'reference_id' => 'BK-TEST-001',
                'idpelanggan' => 'PLG0001',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            ],
            [
                'type' => 'booking_baru',
                'title' => 'Booking Baru',
                'message' => 'Booking baru oleh Pelanggan Test dengan kode BK-TEST-002',
                'reference_id' => 'BK-TEST-002',
                'idpelanggan' => 'PLG0002',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'type' => 'pembayaran',
                'title' => 'Pembayaran Diterima',
                'message' => 'Pembayaran untuk booking BK-TEST-003 telah diterima',
                'reference_id' => 'BK-TEST-003',
                'idpelanggan' => 'PLG0001',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
        ];

        // Insert data
        $this->db->table('notifications')->insertBatch($data);
    }
}
