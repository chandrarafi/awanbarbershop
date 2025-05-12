<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'name' => 'Administrator',
                'role' => 'admin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'pimpinan',
                'email' => 'pimpinan@example.com',
                'password' => password_hash('pimpinan', PASSWORD_DEFAULT),
                'name' => 'Pimpinan',
                'role' => 'pimpinan',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($data);
    }
}
