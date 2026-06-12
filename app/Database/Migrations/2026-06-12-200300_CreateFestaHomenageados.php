<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateFestaHomenageados extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'festa_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nome'     => ['type' => 'VARCHAR', 'constraint' => '150'],
            'titulo'   => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true, 'comment' => 'Ex: Madrinha da Festa, DJ Convidado'],
            'foto'     => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'frase'    => ['type' => 'TEXT', 'null' => true],
            'ordem'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('festa_id');
        $this->forge->createTable('festa_homenageados');
    }
    public function down(): void
    {
        $this->forge->dropTable('festa_homenageados');
    }
}
