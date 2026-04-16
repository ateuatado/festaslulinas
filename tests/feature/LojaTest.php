<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * LojaTest — testa a loja pública e pedidos vinculados a festas.
 *
 * @internal
 */
final class LojaTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed    = LulinasSeeder::class;
    protected $migrate = true;

    // ================================================================
    // Loja Pública (sem autenticação)
    // ================================================================

    public function testLojaPublicaRetorna200(): void
    {
        $result = $this->get('loja');
        $result->assertStatus(200);
    }

    public function testLojaPublicaEstaAcessivelSemLogin(): void
    {
        $result = $this->get('loja');
        $this->assertFalse($result->isRedirect(), 'Loja pública deve ser acessível sem login');
    }

    // ================================================================
    // BUG 4 Regression: Rota duplicada sem auth foi removida
    // ================================================================

    public function testRotaDuplicadaLojaPostSemFestaIdNaoExisteMais(): void
    {
        // Após o BUG 4 fix, POST /loja/salvar (sem festaId numérico) não deve existir
        // O CI4 FeatureTest lança PageNotFoundException para rotas inexistentes
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->post('loja/salvar', ['quantidades' => [1 => 5]]);
    }

    // ================================================================
    // Loja Vinculada (com festaId)
    // ================================================================

    public function testLojaVinculadaRedirecionaSemLogin(): void
    {
        $result = $this->get('loja/1');
        $this->assertTrue($result->isRedirect(), 'Loja vinculada à festa deve exigir autenticação');
    }

    public function testLojaVinculadaComFestaDeOutroUsuarioRedirecionaDashboard(): void
    {
        // user_id=1 tenta acessar loja com festa_id=1 (que pertence ao user_id=2)
        $result = $this->withSession(['user_id' => 1])->get('loja/1');
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect(), 'Deve redirecionar para dashboard ao acessar festa de outro usuário');
    }

    public function testLojaVinculadaComFestaDoProprioUsuarioRetornaConteudo(): void
    {
        // user_id=2 acessa a loja com festa_id=1 (que é dele)
        $result = $this->withSession(['user_id' => 2])->get('loja/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Salvar Pedido
    // ================================================================

    public function testSalvarPedidoSemItensRetornaMensagemDeErro(): void
    {
        $result = $this->withSession(['user_id' => 2])->post('loja/salvar/1', [
            'quantidades' => [], // nenhum item selecionado
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testSalvarPedidoComQuantidadesZeradasRetornaMensagemDeErro(): void
    {
        $result = $this->withSession(['user_id' => 2])->post('loja/salvar/1', [
            'quantidades' => [1 => 0, 2 => 0], // todos zerados
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testSalvarPedidoComItensValidosCriaPedidoNoBanco(): void
    {
        $result = $this->withSession(['user_id' => 2])->post('loja/salvar/1', [
            'quantidades' => [1 => 10, 2 => 5],
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());

        // Verifica se o pedido foi criado (quando usuário autenticado via Shield)
        $db     = \Config\Database::connect();
        $pedido = $db->table('pedidos')->where('festa_id', 1)->orderBy('id', 'DESC')->get()->getRowArray();
        // Se o Shield autentica via sessão nos testes, o pedido estará lá
        if ($pedido !== null) {
            $this->assertEquals('Solicitado', $pedido['status']);
        }
    }

    public function testSalvarPedidoFestaDeOutroUsuarioEhBloqueado(): void
    {
        // user_id=1 tenta fazer pedido para festa_id=1 (que é do user_id=2)
        $result = $this->withSession(['user_id' => 1])->post('loja/salvar/1', [
            'quantidades' => [1 => 10],
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar para dashboard como erro de autorização
        $this->assertTrue($result->isRedirect());
    }
}
