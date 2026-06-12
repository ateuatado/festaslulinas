<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVideoLinksToFestas extends Migration
{
    public function up(): void
    {
        // video_principal: link YouTube/Vimeo exibido no topo da página
        // video_secundario: link opcional exibido após a galeria
        $fields = [
            'video_principal' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'tamanho_festa',
            ],
            'video_secundario' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'video_principal',
            ],
        ];
        $this->forge->addColumn('festas', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('festas', ['video_principal', 'video_secundario']);
    }
}
