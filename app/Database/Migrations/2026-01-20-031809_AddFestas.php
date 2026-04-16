<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFestas extends Migration
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
            'user_id' => [ // Vínculo com o usuário que cadastrou (do Shield)
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nome_festa' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'cidade' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'uf' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
            ],
            'local_evento' => [ // Nome do espaço/endereço
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'data_hora' => [
                'type' => 'DATETIME',
            ],
            'organizacao' => [ // Diretório, Associação, Grupo Político, etc.
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'condicoes_acesso' => [ // Gratuito, Pago, 1kg de alimento, etc.
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'descricao' => [ // Um campo extra para detalhes gerais
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [ // Para Soft Deletes (lixeira)
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE'); // Garante integridade
        $this->forge->createTable('festas');
    }

    public function down()
    {
        $this->forge->dropTable('festas');
    }
}