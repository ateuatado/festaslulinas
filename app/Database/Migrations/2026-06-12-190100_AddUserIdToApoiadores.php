<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToApoiadores extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('apoiadores', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'id',
                'comment'    => 'Vínculo com user_profiles (null = apoiador manual)',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('apoiadores', 'user_id');
    }
}
