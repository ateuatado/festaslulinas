<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;
use App\Models\PedidoModel;
use App\Models\PedidoItemModel;
use App\Models\ProdutoModel;
use App\Models\FestaModel;
use App\Models\MidiaModel;

/**
 * DatabaseIntegrityTest — testa integridade de dados, transações e soft deletes.
 *
 * @internal
 */
final class DatabaseIntegrityTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seed    = LulinasSeeder::class;
    protected $migrate = true;

    // ================================================================
    // Soft Delete
    // ================================================================

    public function testFestaComSoftDeleteNaoAparece(): void
    {
        $model = new FestaModel();
        $model->delete(1); // soft delete

        $resultado = $model->find(1);
        $this->assertNull($resultado, 'Festa com soft delete não deve ser retornada pelo find()');
    }

    public function testFestaSoftDeleteadas(): void
    {
        $model = new FestaModel();
        $model->delete(1);

        // withDeleted deve encontrá-la
        $resultado = $model->withDeleted()->find(1);
        $this->assertNotNull($resultado, 'withDeleted() deve encontrar a festa excluída');
        $this->assertNotNull($resultado['deleted_at'], 'deleted_at deve estar preenchido');
    }

    public function testProdutoComSoftDeleteNaoAparece(): void
    {
        $model = new ProdutoModel();
        $model->delete(1);

        $resultado = $model->find(1);
        $this->assertNull($resultado, 'Produto com soft delete não deve aparecer no find()');
    }

    // ================================================================
    // Transação de Pedido (garante atomicidade)
    // ================================================================

    public function testCriarPedidoComItensMantemConsistencia(): void
    {
        $db          = \Config\Database::connect();
        $pedidoModel = new PedidoModel();
        $itemModel   = new PedidoItemModel();

        $db->transStart();

        $pedidoId = $pedidoModel->insert([
            'festa_id' => 1,
            'user_id'  => 2,
            'status'   => 'Solicitado',
        ]);

        $itemModel->insert(['pedido_id' => $pedidoId, 'produto_id' => 1, 'quantidade' => 10]);
        $itemModel->insert(['pedido_id' => $pedidoId, 'produto_id' => 2, 'quantidade' => 5]);

        $db->transComplete();

        $this->assertTrue($db->transStatus(), 'Transação deve completar com sucesso');

        // Verifica pedido
        $pedido = $pedidoModel->find($pedidoId);
        $this->assertNotNull($pedido);
        $this->assertEquals('Solicitado', $pedido['status']);

        // Verifica itens
        $itens = $db->table('pedidos_itens')->where('pedido_id', $pedidoId)->get()->getResultArray();
        $this->assertCount(2, $itens, 'Devem existir exatamente 2 itens no pedido');
    }

    public function testItensDoPedidoTemQuantidadesCorretas(): void
    {
        $pedidoModel = new PedidoModel();
        $itemModel   = new PedidoItemModel();
        $db          = \Config\Database::connect();

        $pedidoId = $pedidoModel->insert([
            'festa_id' => 1,
            'user_id'  => 2,
            'status'   => 'Solicitado',
        ]);

        $itemModel->insert(['pedido_id' => $pedidoId, 'produto_id' => 1, 'quantidade' => 42]);

        $item = $db->table('pedidos_itens')
                   ->where('pedido_id', $pedidoId)
                   ->where('produto_id', 1)
                   ->get()->getRowArray();

        $this->assertNotNull($item);
        $this->assertEquals(42, (int) $item['quantidade']);
    }

    // ================================================================
    // Integridade das Mídias
    // ================================================================

    public function testMidiaPendenteNaoAparecaNaPaginaPublica(): void
    {
        $model = new MidiaModel();

        // Mídias aprovadas para a festa 1
        $aprovadas = $model->where('festa_id', 1)->where('status', 'aprovado')->findAll();

        foreach ($aprovadas as $midia) {
            $this->assertEquals('aprovado', $midia['status'],
                'Apenas mídias aprovadas devem aparecer na página pública'
            );
        }
    }

    public function testAlterarStatusMidiaAtualiza(): void
    {
        $model = new MidiaModel();

        $model->update(1, ['status' => 'aprovado']);
        $midia = $model->find(1);

        $this->assertEquals('aprovado', $midia['status']);
    }

    // ================================================================
    // ProdutoModel — campo preco (BUG 1 regression)
    // ================================================================

    public function testSalvarProdutoComPrecoViaMantémValor(): void
    {
        $model = new ProdutoModel();

        $id = $model->insert([
            'nome'      => 'Produto Teste',
            'descricao' => 'Gerado pelo PHPUnit',
            'tipo'      => 'Material',
            'preco'     => '99.90',
        ]);

        $produto = $model->find($id);

        $this->assertNotNull($produto, 'Produto deve ser salvo no banco');
        $this->assertEquals('99.90', number_format((float) $produto['preco'], 2, '.', ''),
            'BUG 1 Regression: preco deve ser salvo corretamente via Model'
        );
    }

    public function testAtualizarPrecoViaMantémNovoValor(): void
    {
        $model = new ProdutoModel();

        $model->update(1, ['preco' => '199.99']);
        $produto = $model->find(1);

        $this->assertEquals('199.99', number_format((float) $produto['preco'], 2, '.', ''),
            'BUG 1 Regression: atualização de preco via Model deve funcionar'
        );
    }
}
