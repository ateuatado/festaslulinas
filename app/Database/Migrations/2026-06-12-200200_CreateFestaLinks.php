<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateFestaLinks extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'festa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'titulo'   => ['type' => 'VARCHAR', 'constraint' => '150'],
            'url'      => ['type' => 'VARCHAR', 'constraint' => '512'],
            'status'   => [
                'type'       => 'ENUM',
                'constraint' => ['pendente', 'aprovado', 'rejeitado'],
                'default'    => 'pendente',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('festa_id');
        $this->forge->createTable('festa_links');
    }
    public function down(): void
    {
        $this->forge->dropTable('festa_links');
    }
}
