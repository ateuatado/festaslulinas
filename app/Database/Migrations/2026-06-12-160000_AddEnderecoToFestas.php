<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnderecoToFestas extends Migration
{
    public function up(): void
    {
        // Adiciona campos de endereço completo via CEP
        $fields = [
            'cep' => [
                'type'       => 'VARCHAR',
                'constraint' => '9',
                'null'       => true,
                'after'      => 'uf',
            ],
            'logradouro' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'cep',
            ],
            'bairro' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'logradouro',
            ],
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'bairro',
            ],
            'complemento' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'numero',
            ],
        ];

        $this->forge->addColumn('festas', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('festas', ['cep', 'logradouro', 'bairro', 'numero', 'complemento']);
    }
}
