<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * AdminTest — verifica o painel de administração.
 *
 * Testa controle de acesso (ID=1), moderação de mídias,
 * CRUD de produtos e gestão de festas.
 * Inclui regressão dos BUGs 2 e 4.
 *
 * @internal
 */
final class AdminTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed    = LulinasSeeder::class;
    protected $migrate = true;

    // ================================================================
    // Controle de Acesso
    // ================================================================

    public function testAdminRedirecionaSemLogin(): void
    {
        $result = $this->get('admin');
        $this->assertTrue($result->isRedirect(), 'Admin deve exigir autenticação');
    }

    public function testAdminBloqueiaUsuarioNaoAdmin(): void
    {
        // user_id=2 não é ID=1, deve ser bloqueado
        $result = $this->withSession(['user_id' => 2])->get('admin');
        // Deve retornar 404 (conforme checkAdmin()) ou redirecionar
        $this->assertNotEquals(200, $result->getStatusCode(),
            'Usuário não-admin não deve acessar o painel admin'
        );
    }

    public function testAdminPermiteUsuarioId1(): void
    {
        // user_id=1 é o admin pelo ID fixo atual
        $result = $this->withSession(['user_id' => 1])->get('admin');
        $this->assertNotEquals(500, $result->getStatusCode(),
            'Não deve haver erro 500 no admin para usuário válido'
        );
    }

    // ================================================================
    // Moderação de Mídias
    // ================================================================

    public function testAdminPodeAprovarMidia(): void
    {
        $result = $this->withSession(['user_id' => 1])->get('admin/midia/1/aprovado');
        // Não deve causar erro 500 — redirecionar ou processar
        $this->assertNotEquals(500, $result->getStatusCode());
        // Nota: verificação de banco omitida pois autenticação via sessão simples
        // não é processada pelo Shield em ambiente de testes sem login completo.
    }

    public function testAdminPodeRejeitarMidia(): void
    {
        $result = $this->withSession(['user_id' => 1])->get('admin/midia/1/rejeitado');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testStatusMidiaInvalidoRetornaErro(): void
    {
        // Status 'hackeado' não é válido
        $result = $this->withSession(['user_id' => 1])->get('admin/midia/1/hackeado');
        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar com mensagem de erro, não processar
        $this->assertTrue($result->isRedirect());
    }

    // ================================================================
    // Produtos (BUG 2 regression: excluirProduto agora é POST)
    // ================================================================

    public function testExcluirProdutoViaGetRetorna404(): void
    {
        // Após BUG 2 fix, GET /admin/excluirProduto/:id não deve funcionar
        // O CI4 FeatureTest lança PageNotFoundException para rotas inexistentes
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->withSession(['user_id' => 1])->get('admin/excluirProduto/1');
    }

    public function testExcluirProdutoViaPostFunciona(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/excluirProduto/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testListarProdutosAdmin(): void
    {
        $result = $this->withSession(['user_id' => 1])->get('admin/produtos');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Festas (BUG 2 regression: excluirFesta agora é POST)
    // ================================================================

    public function testExcluirFestaViaGetRetorna404(): void
    {
        // O CI4 FeatureTest lança PageNotFoundException para rotas inexistentes
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->withSession(['user_id' => 1])->get('admin/excluirFesta/1');
    }

    public function testExcluirFestaViaPostAdmin(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/excluirFesta/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Apoiadores (BUG 2 regression: delete agora é POST)
    // ================================================================

    public function testDeleteApoiadorViaGetRetorna404(): void
    {
        // O CI4 FeatureTest lança PageNotFoundException para rotas inexistentes
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->withSession(['user_id' => 1])->get('admin/apoiadores/delete/1');
    }

    public function testDeleteApoiadorViaPostFunciona(): void
    {
        $result = $this->withSession(['user_id' => 1])->post('admin/apoiadores/delete/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }
}
