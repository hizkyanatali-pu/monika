<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEmonTarikSisalelangSdaPaketpekerjaan extends Migration
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
            'satker_id' => [
                'type'       => 'INT',
                'constraint' => 255
            ],
            'kode'     => [
                'type'       => 'VARCHAR',
                'constraint' => 25
            ],
            'nama'     => [
                'type'       => 'VARCHAR',
                'constraint' => 255
            ],
            'jenis_kontrak'     => [
                'type'       => 'VARCHAR',
                'constraint' => 20
            ],
            'nomor_kontrak' => [
                'type'       => 'VARCHAR',
                'constraint' => 255
            ],
            'pagu_pengadaan' => [
                'type'       => 'DECIMAL',
                'constraint' => 20
            ],
            'pagu_dipa_2022' => [
                'type'       => 'DECIMAL',
                'constraint' => 20
            ],
            'nilai_kontrak_induk' => [
                'type'       => 'DECIMAL',
                'constraint' => 20
            ],
            'nilai_kontrak_anak' => [
                'type'       => 'DECIMAL',
                'constraint' => 20
            ],
            'sisa_lelang' => [
                'type'       => 'DECIMAL',
                'constraint' => 20
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('emon_tarik_sisalelang_sda_paketpekerjaan');
    }

    public function down()
    {
        $this->forge->dropTable('emon_tarik_sisalelang_sda_paketpekerjaan');
    }
}
