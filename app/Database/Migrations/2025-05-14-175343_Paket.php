<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idpaket' => [
                'type'           => 'CHAR',
                'constraint'     => 10,
            ],
            'namapaket' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ],
            'deskripsi' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'harga' => [
                'type'           => 'DECIMAL',
                'constraint'     => '10,2',
            ],
            'image' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('idpaket', true);
        $this->forge->createTable('paket');
    }

    public function down()
    {
        $this->forge->dropTable('paket');
    }
}
