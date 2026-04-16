<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

/**
 * AdminSeeder — cria o usuário administrador do sistema.
 *
 * Execute uma vez após as migrations:
 *   php spark db:seed App\Database\Seeds\AdminSeeder
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $users = model(\CodeIgniter\Shield\Models\UserModel::class);

        // Idempotente — verifica por email (campo único no Shield)
        $db = \Config\Database::connect();
        if ($db->table('auth_identities')
               ->where('type', 'email_password')
               ->where('secret', 'marcosantofoto@gmail.com')
               ->countAllResults() > 0) {
            echo "Usuário 'marcosantofo' já existe. Nada a fazer.\n";
            return;
        }

        $user = new User([
            'username' => 'marcosantofo',
            'email'    => 'marcosantofoto@gmail.com',
            'password' => 'Lula#Eleito26',
            'active'   => 1,
        ]);

        $users->save($user);

        /** @var \CodeIgniter\Shield\Entities\User $user */
        $user = $users->findById($users->getInsertID());
        $user->activate();
        $user->addGroup('admin');

        echo "✅ Usuário admin 'marcosantofo' criado com sucesso!\n";
        echo "   Email : marcosantofoto@gmail.com\n";
        echo "   Senha : Lula#Eleito26\n";
        echo "   Grupo : admin\n";
    }
}
