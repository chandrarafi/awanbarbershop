<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelangganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'idpelanggan' => [
                'type'           => 'CHAR',
                'constraint'     => 10,
                'null'           => false,
            ],
            'nama_lengkap' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => false,
            ],
            'jeniskelamin' => [
                'type'           => 'ENUM',
                'constraint'     => ['Laki-laki', 'Perempuan', '-'],
                'null'           => true,
            ],
            'alamat' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'no_hp' => [
                'type'           => 'VARCHAR',
                'constraint'     => 15,
                'null'           => true,
            ],
            'tanggal_lahir' => [
                'type'           => 'DATE',
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

        $this->forge->addKey('id', true);
        $this->forge->addKey('idpelanggan', false, true); // Unique key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('pelanggan');
    }

    public function down()
    {
        $this->forge->dropTable('pelanggan');
    }
}
