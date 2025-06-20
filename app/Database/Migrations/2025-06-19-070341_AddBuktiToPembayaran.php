<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBuktiToPembayaran extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembayaran', [
            'bukti' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'jenis'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran', 'bukti');
    }
}
