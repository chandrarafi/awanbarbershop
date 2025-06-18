<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToKaryawan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('karyawan', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'nonaktif'],
                'default'    => 'aktif',
                'after'      => 'nohp'
            ],
        ]);

        // Update semua karyawan yang ada menjadi aktif
        $this->db->query("UPDATE karyawan SET status = 'aktif'");
    }

    public function down()
    {
        $this->forge->dropColumn('karyawan', 'status');
    }
}
