<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEmonTarikSisalelangSdaSatker extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => [
                'type'           => 'INT',
                'constraint'     => 255,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'tarik_id' => [
                'type'       => 'INT',
                'constraint' => 255
            ],
            'kode'     => [
                'type'       => 'VARCHAR',
                'constraint' => 20
            ],
            'nama'     => [
                'type'       => 'VARCHAR',
                'constraint' => 255
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('emon_tarik_sisalelang_sda_satker');
    }

    public function down()
    {
        $this->forge->dropTable('emon_tarik_sisalelang_sda_satker');
    }
}
