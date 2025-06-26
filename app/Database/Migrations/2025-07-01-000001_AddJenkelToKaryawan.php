<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenkelToKaryawan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('karyawan', [
            'jenkel' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => true,
                'after'      => 'namakaryawan'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('karyawan', 'jenkel');
    }
}
