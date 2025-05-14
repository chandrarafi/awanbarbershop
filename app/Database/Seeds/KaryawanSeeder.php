<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'idkaryawan' => 'KRY001',
                'namakaryawan' => 'Budi Santoso',
                'alamat' => 'Jl. Mawar No. 10, Jakarta Selatan',
                'nohp' => '081234567890'
            ],
            [
                'idkaryawan' => 'KRY002',
                'namakaryawan' => 'Dewi Lestari',
                'alamat' => 'Jl. Melati No. 15, Jakarta Pusat',
                'nohp' => '082345678901'
            ],
            [
                'idkaryawan' => 'KRY003',
                'namakaryawan' => 'Ahmad Rizki',
                'alamat' => 'Jl. Anggrek No. 20, Jakarta Timur',
                'nohp' => '083456789012'
            ]
        ];

        foreach ($data as $record) {
            $this->db->table('karyawan')->insert($record);
        }
    }
}
