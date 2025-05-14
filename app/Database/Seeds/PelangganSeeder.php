<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        // Data nama-nama Indonesia
        $namaDepanLaki = [
            'Ahmad',
            'Muhammad',
            'Budi',
            'Dedi',
            'Eko',
            'Firman',
            'Hendra',
            'Irfan',
            'Joko',
            'Kurniawan',
            'Lutfi',
            'Nanda',
            'Putra',
            'Rudi',
            'Surya',
            'Taufik',
            'Wahyu',
            'Yusuf',
            'Zaki',
            'Agus',
            'Bambang',
            'Candra',
            'Dimas',
            'Edi',
            'Fajar'
        ];

        $namaDepanPerempuan = [
            'Ani',
            'Bella',
            'Citra',
            'Dewi',
            'Eka',
            'Fitri',
            'Gita',
            'Hani',
            'Indah',
            'Julia',
            'Kartika',
            'Lina',
            'Maya',
            'Nita',
            'Putri',
            'Rina',
            'Sari',
            'Tari',
            'Utami',
            'Vina',
            'Wati',
            'Yanti',
            'Zahra',
            'Amelia',
            'Dina'
        ];

        $namaBelakang = [
            'Wijaya',
            'Kusuma',
            'Susanto',
            'Saputra',
            'Pratama',
            'Nugraha',
            'Hidayat',
            'Purnama',
            'Sari',
            'Permata',
            'Dewi',
            'Lestari',
            'Santoso',
            'Putra',
            'Utama',
            'Setiawan',
            'Perdana',
            'Suryadi',
            'Ramadhan',
            'Gunawan',
            'Wibowo',
            'Sulistyo',
            'Hartono',
            'Winata',
            'Budiman'
        ];

        // Buat 50 user account terlebih dahulu
        $users = [];
        for ($i = 1; $i <= 50; $i++) {
            $jenisKelamin = rand(0, 1) == 0 ? 'Laki-laki' : 'Perempuan';
            $namaDepan = $jenisKelamin == 'Laki-laki' ?
                $namaDepanLaki[array_rand($namaDepanLaki)] :
                $namaDepanPerempuan[array_rand($namaDepanPerempuan)];
            $namaBelakangPilihan = $namaBelakang[array_rand($namaBelakang)];
            $namaLengkap = $namaDepan . ' ' . $namaBelakangPilihan;

            $userData = [
                'username' => strtolower(str_replace(' ', '', $namaDepan)) . sprintf('%03d', $i),
                'email' => strtolower(str_replace(' ', '', $namaDepan)) . sprintf('%03d', $i) . '@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'name' => $namaLengkap,
                'role' => 'pelanggan',
                'status' => 'active'
            ];

            $this->db->table('users')->insert($userData);
            $users[] = [
                'id' => $this->db->insertID(),
                'nama' => $namaLengkap,
                'jenisKelamin' => $jenisKelamin
            ];
        }

        // Generate 100 pelanggan (50 dengan user account, 50 tanpa)
        for ($i = 1; $i <= 100; $i++) {
            $jenisKelamin = rand(0, 1) == 0 ? 'Laki-laki' : 'Perempuan';
            $namaDepan = $jenisKelamin == 'Laki-laki' ?
                $namaDepanLaki[array_rand($namaDepanLaki)] :
                $namaDepanPerempuan[array_rand($namaDepanPerempuan)];
            $namaBelakangPilihan = $namaBelakang[array_rand($namaBelakang)];
            $namaLengkap = $i <= 50 ? $users[$i - 1]['nama'] : $namaDepan . ' ' . $namaBelakangPilihan;

            $pelangganData = [
                'idpelanggan' => 'PLG' . sprintf('%03d', $i),
                'user_id' => ($i <= 50) ? $users[$i - 1]['id'] : null,
                'nama_lengkap' => $namaLengkap,
                'jeniskelamin' => $i <= 50 ? $users[$i - 1]['jenisKelamin'] : $jenisKelamin,
                'no_hp' => '08' . rand(1000000000, 9999999999),
                'tanggal_lahir' => date('Y-m-d', strtotime('-' . rand(18, 60) . ' years')),
                'alamat' => 'Jl. ' . ['Mawar', 'Melati', 'Anggrek', 'Dahlia', 'Kenanga', 'Cempaka', 'Kamboja', 'Tulip', 'Teratai', 'Flamboyan'][rand(0, 9)] .
                    ' No. ' . rand(1, 100) . ', Jakarta ' .
                    ['Utara', 'Selatan', 'Timur', 'Barat', 'Pusat'][rand(0, 4)]
            ];

            $this->db->table('pelanggan')->insert($pelangganData);
        }
    }
}
