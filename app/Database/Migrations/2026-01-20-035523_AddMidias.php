<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMidias extends Migration
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
            'festa_id' => [ // Vínculo com a festa
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'arquivo' => [ // Nome do arquivo salvo no disco (hash)
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nome_original' => [ // Nome original para referência (ex: "foto_galera.jpg")
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tipo' => [ // foto ou video
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'foto',
            ],
            'status' => [ // O guarda-costas do sistema
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'pendente', // pendente, aprovado, rejeitado
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('festa_id', 'festas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('midias');
    }

    public function down()
    {
        $this->forge->dropTable('midias');
    }
}