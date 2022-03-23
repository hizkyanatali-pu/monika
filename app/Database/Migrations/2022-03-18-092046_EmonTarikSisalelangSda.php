<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmonTarikSisalelangSda extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => [
                'type'           => 'INT',
                'constraint'     => 255,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'data_tanggal' => [
                'type'      => 'VARCHAR',
                'constraint' => 20
            ],
            'data_waktu'   => [
                'type'       => 'VARCHAR',
                'constraint' => 9
            ],
            'created_at datetime default current_timestamp'
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('emon_tarik_sisalelang_sda');
    }

    public function down()
    {
        $this->forge->dropTable('emon_tarik_sisalelang_sda');
    }
}
