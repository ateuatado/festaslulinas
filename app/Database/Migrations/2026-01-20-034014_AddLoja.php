<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLoja extends Migration
{
    public function up()
    {
        // 1. Tabela de Produtos
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'imagem' => [ // Nome do arquivo da foto
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tipo' => [ // Ex: Kit, Avulso, Digital
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Material',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('produtos');

        // 2. Tabela de Pedidos
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
            'user_id' => [ // Vínculo com quem pediu
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status' => [ // Solicitado, Em Análise, Enviado, Entregue
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Solicitado',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('festa_id', 'festas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pedidos');

        // 3. Tabela de Itens do Pedido (Pivô)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pedido_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'produto_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantidade' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produto_id', 'produtos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pedidos_itens');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos_itens');
        $this->forge->dropTable('pedidos');
        $this->forge->dropTable('produtos');
    }
}