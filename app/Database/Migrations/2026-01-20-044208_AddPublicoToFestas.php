<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicoToFestas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('festas', [
            'publico_estimado' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'descricao'
            ],
            'publico_real' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'publico_estimado'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('festas', ['publico_estimado', 'publico_real']);
    }
}