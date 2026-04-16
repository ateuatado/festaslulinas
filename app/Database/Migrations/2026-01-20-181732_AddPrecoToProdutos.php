<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPrecoToProdutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produtos', [
            'preco' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2', // Suporta até 99 milhões (10 dígitos, 2 decimais)
                'default'    => 0.00,
                'after'      => 'tipo'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produtos', 'preco');
    }
}