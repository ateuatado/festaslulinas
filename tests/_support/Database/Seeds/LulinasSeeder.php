<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * LulinasSeeder — popula o banco de teste MySQL com dados mínimos para todos os testes.
 * Inclui controle de grupo Shield (auth_groups_users) para testes de admin.
 */
class LulinasSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // ----------------------------------------------------------------
        // 0. Limpa o banco de forma idempotente (compatível com MySQL e SQLite)
        //    SET FOREIGN_KEY_CHECKS é exclusivo do MySQL — ignorado no SQLite.
        // ----------------------------------------------------------------
        $isMysql = $this->db->DBDriver === 'MySQLi';
        if ($isMysql) {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        }
        foreach (['pedidos_itens', 'pedidos', 'midias', 'festas', 'apoiadores', 'produtos', 'auth_identities', 'auth_groups_users', 'users'] as $table) {
            $this->db->table($table)->truncate();
        }
        if ($isMysql) {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        }

        // ----------------------------------------------------------------
        // 1. Usuários (tabela gerenciada pelo Shield)
        // ----------------------------------------------------------------
        $this->db->table('users')->insertBatch([
            [
                'id'         => 1,
                'username'   => 'admin_test',
                'active'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'username'   => 'user_test',
                'active'     => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);

        // ----------------------------------------------------------------
        // 2. Identidades (e-mail + senha hash) para o Shield
        //    Senha: 'password123' — hash bcrypt pré-gerado
        // ----------------------------------------------------------------
        $hash = '$2y$12$abcdefghijklmnopqrstuuVGZzJ3jL0OnXc2LVUv/E8t6L0JHZM0q';
        $this->db->table('auth_identities')->insertBatch([
            [
                'user_id'    => 1,
                'type'       => 'email_password',
                'secret'     => 'admin@lulinas.test',
                'secret2'    => $hash,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id'    => 2,
                'type'       => 'email_password',
                'secret'     => 'user@lulinas.test',
                'secret2'    => $hash,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // ----------------------------------------------------------------
        // 2b. Grupos Shield — user_id=1 é admin, user_id=2 é user
        // ----------------------------------------------------------------
        $this->db->table('auth_groups_users')->insertBatch([
            ['user_id' => 1, 'group' => 'admin', 'created_at' => $now],
            ['user_id' => 2, 'group' => 'user',  'created_at' => $now],
        ]);

        // ----------------------------------------------------------------
        // 3. Festas
        // ----------------------------------------------------------------
        $this->db->table('festas')->insertBatch([
            [
                'id'               => 1,
                'user_id'          => 2,
                'nome_festa'       => 'Festa Lulina de Teste',
                'cidade'           => 'São Paulo',
                'uf'               => 'SP',
                'local_evento'     => 'Praça da Sé',
                'data_hora'        => '2026-06-15 16:00:00',
                'organizacao'      => 'Diretório Municipal PT',
                'condicoes_acesso' => 'Gratuito',
                'descricao'        => 'Festa de teste para PHPUnit',
                'created_at'       => $now,
                'updated_at'       => $now,
                'deleted_at'       => null,
            ],
        ]);

        // ----------------------------------------------------------------
        // 4. Produtos
        // ----------------------------------------------------------------
        $this->db->table('produtos')->insertBatch([
            [
                'id'         => 1,
                'nome'       => 'Camiseta Teste',
                'descricao'  => 'Camiseta para testes',
                'tipo'       => 'Vestuário',
                'preco'      => '50.00',
                'imagem'     => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'nome'       => 'Adesivo Teste',
                'descricao'  => 'Adesivo para testes',
                'tipo'       => 'Impresso',
                'preco'      => '10.00',
                'imagem'     => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);

        // ----------------------------------------------------------------
        // 5. Apoiadores
        // ----------------------------------------------------------------
        $this->db->table('apoiadores')->insertBatch([
            [
                'id'         => 1,
                'nome'       => 'Apoiador Teste',
                'funcao'     => 'Testador',
                'prioridade' => 1,
                'frase'      => 'Testando desde sempre',
                'foto'       => 'apoiador_test.jpg',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);

        // ----------------------------------------------------------------
        // 6. Mídias
        // ----------------------------------------------------------------
        $this->db->table('midias')->insertBatch([
            [
                'id'            => 1,
                'festa_id'      => 1,
                'arquivo'       => 'foto_test.jpg',
                'nome_original' => 'foto_original.jpg',
                'tipo'          => 'foto',
                'status'        => 'pendente',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 2,
                'festa_id'      => 1,
                'arquivo'       => 'foto_aprovada.jpg',
                'nome_original' => 'foto_aprovada_original.jpg',
                'tipo'          => 'foto',
                'status'        => 'aprovado',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);
    }
}
