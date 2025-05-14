<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idpaket' => 'PKT001',
                'namapaket' => 'Basic Haircut',
                'deskripsi' => 'Potong rambut dasar dengan styling sederhana',
                'harga' => 50000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT002',
                'namapaket' => 'Premium Package',
                'deskripsi' => 'Potong rambut, keramas, pijat kepala, dan styling',
                'harga' => 100000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'idpaket' => 'PKT003',
                'namapaket' => 'Complete Treatment',
                'deskripsi' => 'Potong rambut, keramas, facial, pijat kepala, dan styling premium',
                'harga' => 150000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('paket')->insertBatch($data);
    }
}
