<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrioridadeToApoiadores extends Migration
{
    public function up()
    {
        $this->forge->addColumn('apoiadores', [
            'prioridade' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 5, // 1 = VIP Máximo, 5 = Base
                'after'      => 'funcao'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('apoiadores', 'prioridade');
    }
}