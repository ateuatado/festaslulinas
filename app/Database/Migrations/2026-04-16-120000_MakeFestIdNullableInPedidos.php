<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Torna festa_id nullable em pedidos para suportar
 * pedidos gerados pelo carrinho geral (sem vinculo a uma festa).
 */
class MakeFestIdNullableInPedidos extends Migration
{
    public function up(): void
    {
        $this->forge->modifyColumn('pedidos', [
            'festa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->modifyColumn('pedidos', [
            'festa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);
    }
}
