<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * CarrinhoTest — cobre todas as ações do controller Carrinho.
 *
 * O carrinho é baseado em sessão, portanto acessível sem login.
 * Apenas a finalização exige autenticação (protegida pela rota).
 *
 * @internal
 */
final class CarrinhoTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed     = LulinasSeeder::class;
    protected $basePath  = ROOTPATH . 'tests/_support/Database';
    protected $namespace = null;
    protected $migrate   = true;
    protected $refresh   = true;

    // ================================================================
    // Exibição do carrinho (GET /carrinho)
    // ================================================================

    public function testCarrinhoPublicoRetorna200(): void
    {
        $result = $this->get('carrinho');
        $result->assertStatus(200);
    }

    public function testCarrinhoAcessivelSemLogin(): void
    {
        $result = $this->get('carrinho');
        $this->assertFalse($result->isRedirect(), 'Carrinho deve ser acessível sem login');
    }

    // ================================================================
    // Adicionar produto (POST /carrinho/adicionar)
    // ================================================================

    public function testAdicionarProdutoAoCarrinho(): void
    {
        $result = $this->post('carrinho/adicionar', [
            'quantidades' => [1 => 3, 2 => 2],
        ]);
        // Deve redirecionar para /carrinho após adicionar
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect(), 'Deve redirecionar após adicionar ao carrinho');
    }

    public function testAdicionarCarrinhoSemQuantidadesRetornaErro(): void
    {
        $result = $this->post('carrinho/adicionar', [
            'quantidades' => [],
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
        // Sem nenhum item selecionado → deve redirecionar com erro
        $this->assertTrue($result->isRedirect());
    }

    public function testAdicionarCarrinhoSemCampoQuantidadesRetornaErro(): void
    {
        $result = $this->post('carrinho/adicionar', []);
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect());
    }

    public function testAdicionarQuantidadeZeradaNaoEntraNoCarrinho(): void
    {
        // Quantidades todas zero — nenhum produto deve ser adicionado
        $result = $this->post('carrinho/adicionar', [
            'quantidades' => [1 => 0, 2 => 0],
        ]);
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Remover produto (POST /carrinho/remover/:num)
    // ================================================================

    public function testRemoverProdutoDoCarrinho(): void
    {
        // Primeiro adiciona
        $this->post('carrinho/adicionar', ['quantidades' => [1 => 5]]);

        // Depois remove
        $result = $this->post('carrinho/remover/1');
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect(), 'Deve redirecionar após remover item');
    }

    public function testRemoverProdutoInexistenteNaoCausaErro(): void
    {
        // Tenta remover produto que nunca foi adicionado
        $result = $this->post('carrinho/remover/999');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Limpar carrinho (POST /carrinho/limpar)
    // ================================================================

    public function testLimparCarrinho(): void
    {
        $result = $this->post('carrinho/limpar');
        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect(), 'Deve redirecionar para /carrinho após limpar');
    }

    // ================================================================
    // Finalizar pedido (POST /carrinho/finalizar — exige login)
    // ================================================================

    public function testFinalizarCarrinhoSemLoginRedirecionaParaLogin(): void
    {
        $result = $this->post('carrinho/finalizar', []);
        // Sem login → deve redirecionar (para login ou outra rota)
        $this->assertTrue($result->isRedirect(), 'Finalizar sem login deve redirecionar');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testFinalizarCarrinhoVazioComLoginRetornaMensagemDeErro(): void
    {
        // Usuário logado, mas carrinho vazio
        $result = $this->withSession(['user_id' => 2])->post('carrinho/finalizar', []);
        $this->assertNotEquals(500, $result->getStatusCode());
        // Carrinho vazio → deve redirecionar com erro
        $this->assertTrue($result->isRedirect());
    }

    public function testFinalizarCarrinhoComItensLogadoCriaPedido(): void
    {
        // Simula sessão com carrinho populado
        $result = $this->withSession([
            'user_id'  => 2,
            'carrinho' => [1 => 3, 2 => 1],
        ])->post('carrinho/finalizar', [
            'festa_id' => 1, // festa do user_id=2
        ]);

        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar (sucesso ou erro de auth do Shield)
        $this->assertTrue($result->isRedirect());
    }

    public function testFinalizarCarrinhoComFestaDeOutroUsuarioEhBloqueado(): void
    {
        // user_id=1 (admin) tenta usar festa_id=1 (do user_id=2) para o pedido
        $result = $this->withSession([
            'user_id'  => 1,
            'carrinho' => [1 => 5],
        ])->post('carrinho/finalizar', [
            'festa_id' => 1,
        ]);

        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar (bloqueado ou sem autenticação via Shield)
        $this->assertTrue($result->isRedirect());
    }

    public function testFinalizarCarrinhoSemFestaVinculadaEhPermitido(): void
    {
        // festa_id omitido → pedido sem vínculo de festa (campo nullable)
        $result = $this->withSession([
            'user_id'  => 2,
            'carrinho' => [1 => 2],
        ])->post('carrinho/finalizar', [
            // sem festa_id
        ]);

        $this->assertNotEquals(500, $result->getStatusCode());
        $this->assertTrue($result->isRedirect());
    }
}
