<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddSlugToFestas extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('festas', [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'nome_festa',
            ],
        ]);
        // Índice único para garantir slugs únicos
        $this->db->query('ALTER TABLE festas ADD UNIQUE INDEX idx_festas_slug (slug)');
    }
    public function down(): void
    {
        $this->forge->dropColumn('festas', 'slug');
    }
}
