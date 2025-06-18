<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisToPembayaran extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembayaran', [
            'jenis' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
                'default'        => 'Lunas',
                'after'          => 'status'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran', 'jenis');
    }
}
