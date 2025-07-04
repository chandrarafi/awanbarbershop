<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDurasiToPaket extends Migration
{
    public function up()
    {
        $this->forge->addColumn('paket', [
            'durasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 60,
                'comment'    => 'Durasi layanan dalam menit'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('paket', 'durasi');
    }
}
