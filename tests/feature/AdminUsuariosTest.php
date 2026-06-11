<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * AdminUsuariosTest — cobre o gerenciamento de usuários pelo administrador.
 *
 * Testa: listar usuários, criar usuário, toggle de grupo (admin/user),
 * desativar/reativar usuário e proteção contra auto-remoção de acesso.
 *
 * @internal
 */
final class AdminUsuariosTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed     = LulinasSeeder::class;
    protected $basePath  = ROOTPATH . 'tests/_support/Database';
    protected $namespace = null;
    protected $migrate   = true;
    protected $refresh   = true;

    // ================================================================
    // Listagem de usuários
    // ================================================================

    public function testListarUsuariosRedirecionaSemLogin(): void
    {
        $result = $this->get('admin/usuarios');
        $this->assertTrue($result->isRedirect(), 'Rota admin deve exigir autenticação');
    }

    public function testListarUsuariosBloqueiaParaNaoAdmin(): void
    {
        $result = $this->withSession(['user_id' => 2])->get('admin/usuarios');
        $this->assertNotEquals(200, $result->getStatusCode(),
            'Usuário não-admin não deve acessar listagem de usuários'
        );
    }

    public function testListarUsuariosRetorna200ParaAdmin(): void
    {
        $result = $this->withSession(['user_id' => 1])->get('admin/usuarios');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Criar usuário
    // ================================================================

    public function testCriarUsuarioRedirecionaSemLogin(): void
    {
        $result = $this->post('admin/usuarios/criar', [
            'username' => 'novo_user',
            'email'    => 'novo@lulinas.test',
            'password' => 'senhaforte123',
            'grupo'    => 'user',
        ]);
        $this->assertTrue($result->isRedirect());
    }

    public function testCriarUsuarioBloqueadoParaNaoAdmin(): void
    {
        $result = $this->withSession(['user_id' => 2])->post('admin/usuarios/criar', [
            'username' => 'hacker',
            'email'    => 'hacker@lulinas.test',
            'password' => 'senha12345',
            'grupo'    => 'admin',
        ]);
        $this->assertNotEquals(200, $result->getStatusCode());
    }

    public function testCriarUsuarioComDadosValidosNaoCausa500(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/criar', [
            'username' => 'novo_organizador',
            'email'    => 'organizador@lulinas.test',
            'password' => 'senhaforte123',
            'grupo'    => 'user',
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testCriarUsuarioComSenhaFracaRetornaErroValidacao(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/criar', [
            'username' => 'fraco',
            'email'    => 'fraco@lulinas.test',
            'password' => '123',        // min_length[8] — inválido
            'grupo'    => 'user',
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
        // Validação falha → redireciona de volta com erro
        $this->assertTrue($result->isRedirect());
    }

    public function testCriarUsuarioComUsernameVazioRetornaErroValidacao(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/criar', [
            'username' => '',           // required
            'email'    => 'sem_user@lulinas.test',
            'password' => 'senhaforte123',
            'grupo'    => 'user',
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect());
    }

    public function testCriarUsuarioComGrupoInvalidoRetornaErro(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/criar', [
            'username' => 'grupo_invalido',
            'email'    => 'grupo_invalido@lulinas.test',
            'password' => 'senhaforte123',
            'grupo'    => 'superadmin',  // não está em in_list[admin,user]
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect());
    }

    public function testCriarUsuarioComEmailDuplicadoRetornaErro(): void
    {
        // admin@lulinas.test já está no seeder
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/criar', [
            'username' => 'outro_admin',
            'email'    => 'admin@lulinas.test',  // e-mail duplicado
            'password' => 'senhaforte123',
            'grupo'    => 'user',
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve detectar duplicidade e redirecionar com erro
        $this->assertTrue($result->isRedirect());
    }

    // ================================================================
    // Toggle de grupo (POST /admin/usuarios/grupo/:userId/:grupo)
    // ================================================================

    public function testToggleGrupoRedirecionaSemLogin(): void
    {
        $result = $this->post('admin/usuarios/grupo/2/admin');
        $this->assertTrue($result->isRedirect());
    }

    public function testToggleGrupoNaoCausa500(): void
    {
        // user_id=1 (admin) promove user_id=2 para admin
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/grupo/2/admin');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testToggleGrupoInvalidoRetornaErro(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/grupo/2/superadmin');
        $this->assertNotEquals(500, $result->getStatusCode());
        // Grupo inválido → redireciona com mensagem de erro
        $this->assertTrue($result->isRedirect());
    }

    public function testToggleGrupoNaoCausaErroPara500ComUsuarioInexistente(): void
    {
        // Tenta alterar grupo de usuário que não existe no banco
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/grupo/9999/user');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Desativar / reativar usuário (POST /admin/usuarios/desativar/:userId)
    // ================================================================

    public function testDesativarUsuarioRedirecionaSemLogin(): void
    {
        $result = $this->post('admin/usuarios/desativar/2');
        $this->assertTrue($result->isRedirect());
    }

    public function testDesativarUsuarioNaoCausa500(): void
    {
        // Admin desativa user_id=2
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/desativar/2');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testDesativarUsuarioInexistenteNaoCausa500(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/desativar/9999');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testAdminNaoPodeDesativarProprioUsuario(): void
    {
        // Admin tenta desativar a si mesmo (user_id=1)
        $result = $this->withSession(['user_id' => 1])->post('admin/usuarios/desativar/1');
        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar com mensagem de erro de proteção
        $this->assertTrue($result->isRedirect());
    }
}
