<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengeluaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idpengeluaran' => 'PG-' . date('Ymd') . '-0001',
                'tgl' => date('Y-m-d'),
                'keterangan' => 'Pembayaran listrik',
                'jumlah' => 500000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idpengeluaran' => 'PG-' . date('Ymd') . '-0002',
                'tgl' => date('Y-m-d', strtotime('-1 day')),
                'keterangan' => 'Pembelian alat cukur',
                'jumlah' => 750000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idpengeluaran' => 'PG-' . date('Ymd') . '-0003',
                'tgl' => date('Y-m-d', strtotime('-2 day')),
                'keterangan' => 'Pembayaran sewa tempat',
                'jumlah' => 2000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('pengeluaran')->insertBatch($data);
    }
}
