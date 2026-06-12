<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateFestaPosts extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'festa_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'conteudo'  => ['type' => 'LONGTEXT'],
            'status'    => [
                'type'       => 'ENUM',
                'constraint' => ['rascunho', 'pendente', 'aprovado', 'rejeitado'],
                'default'    => 'rascunho',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('festa_id');
        $this->forge->createTable('festa_posts');
    }
    public function down(): void
    {
        $this->forge->dropTable('festa_posts');
    }
}
