<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApoiadores extends Migration
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
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'funcao' => [ // Ex: Presidente do PT, Ator, Idealizador
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'foto' => [ // Nome do arquivo
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('apoiadores');
    }

    public function down()
    {
        $this->forge->dropTable('apoiadores');
    }
}