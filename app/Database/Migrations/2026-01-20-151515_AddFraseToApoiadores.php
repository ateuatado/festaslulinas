<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFraseToApoiadores extends Migration
{
    public function up()
    {
        $this->forge->addColumn('apoiadores', [
            'frase' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, // Pode ficar vazio
                'after'      => 'prioridade'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('apoiadores', 'frase');
    }
}