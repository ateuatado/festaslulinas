<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

/**
 * TestUsersSeeder — cria dois usuários de teste no VPS.
 *
 * Execute no VPS após as migrations:
 *   php spark db:seed App\Database\Seeds\TestUsersSeeder
 *
 * Usuários criados:
 *   [admin] festaslulinas@gmail.com.br  senha: Lula#Tetra26
 *   [user]  eiouaueaio@gmail.com        senha: Lula#Tetra26
 */
class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = model(\CodeIgniter\Shield\Models\UserModel::class);
        $db    = \Config\Database::connect();

        $contas = [
            [
                'username' => 'festaslulinas',
                'email'    => 'festaslulinas@gmail.com.br',
                'password' => 'Lula#Tetra26',
                'grupo'    => 'admin',
            ],
            [
                'username' => 'eiouaueaio',
                'email'    => 'eiouaueaio@gmail.com',
                'password' => 'Lula#Tetra26',
                'grupo'    => 'user',
            ],
        ];

        foreach ($contas as $conta) {

            // Idempotente: não recria se já existir
            $jaExiste = $db->table('auth_identities')
                           ->where('type', 'email_password')
                           ->where('secret', $conta['email'])
                           ->countAllResults();

            if ($jaExiste > 0) {
                echo "⚠️  Usuário '{$conta['email']}' já existe. Pulando.\n";
                continue;
            }

            $user = new User([
                'username' => $conta['username'],
                'email'    => $conta['email'],
                'password' => $conta['password'],
                'active'   => 1,
            ]);

            $users->save($user);

            /** @var \CodeIgniter\Shield\Entities\User $user */
            $user = $users->findById($users->getInsertID());
            $user->activate();
            $user->addGroup($conta['grupo']);

            echo "✅ Usuário criado com sucesso!\n";
            echo "   E-mail : {$conta['email']}\n";
            echo "   Senha  : {$conta['password']}\n";
            echo "   Grupo  : {$conta['grupo']}\n\n";
        }

        echo "Concluído.\n";
    }
}
