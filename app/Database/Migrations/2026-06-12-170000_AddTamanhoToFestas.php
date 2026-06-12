<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTamanhoToFestas extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('festas', [
            'tamanho_festa' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'condicoes_acesso',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('festas', 'tamanho_festa');
    }
}
