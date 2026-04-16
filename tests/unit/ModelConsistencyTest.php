<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\FestaModel;
use App\Models\ProdutoModel;
use App\Models\ApoiadorModel;
use App\Models\MidiaModel;
use App\Models\PedidoModel;
use App\Models\PedidoItemModel;

/**
 * ModelConsistencyTest — verifica a configuração dos Models sem banco.
 *
 * Detecta regressões em allowedFields, softDeletes, timestamps e
 * validationRules sem precisar de conexão com banco de dados.
 *
 * @internal
 */
final class ModelConsistencyTest extends CIUnitTestCase
{
    // ================================================================
    // ProdutoModel
    // ================================================================

    /** BUG 1 (corrigido): garante que 'preco' está em allowedFields */
    public function testProdutoModelAllowsPreco(): void
    {
        $model = new ProdutoModel();
        $this->assertContains(
            'preco',
            $this->readProperty($model, 'allowedFields'),
            'ProdutoModel deve permitir o campo "preco" em allowedFields'
        );
    }

    public function testProdutoModelHasSoftDeletes(): void
    {
        $model = new ProdutoModel();
        $this->assertTrue(
            $this->readProperty($model, 'useSoftDeletes'),
            'ProdutoModel deve usar soft deletes'
        );
    }

    public function testProdutoModelAllowedFieldsComplete(): void
    {
        $model    = new ProdutoModel();
        $expected = ['nome', 'descricao', 'imagem', 'tipo', 'preco'];
        $actual   = $this->readProperty($model, 'allowedFields');
        foreach ($expected as $field) {
            $this->assertContains($field, $actual, "Campo '$field' ausente em ProdutoModel::allowedFields");
        }
    }

    // ================================================================
    // FestaModel
    // ================================================================

    public function testFestaModelHasSoftDeletes(): void
    {
        $model = new FestaModel();
        $this->assertTrue(
            $this->readProperty($model, 'useSoftDeletes'),
            'FestaModel deve usar soft deletes'
        );
    }

    public function testFestaModelAllowedFieldsIncludesUserId(): void
    {
        $model = new FestaModel();
        $this->assertContains(
            'user_id',
            $this->readProperty($model, 'allowedFields'),
            'FestaModel deve permitir user_id para criar festas'
        );
    }

    public function testFestaModelValidationRequiresNomeFesta(): void
    {
        $model = new FestaModel();
        $rules = $this->readProperty($model, 'validationRules');
        $this->assertArrayHasKey('nome_festa', $rules, 'FestaModel deve validar nome_festa');
        $this->assertStringContainsString('required', $rules['nome_festa']);
    }

    public function testFestaModelValidationRequiresMinLength3(): void
    {
        $model = new FestaModel();
        $rules = $this->readProperty($model, 'validationRules');
        $this->assertStringContainsString('min_length[3]', $rules['nome_festa']);
    }

    public function testFestaModelValidationRequiresCidade(): void
    {
        $model = new FestaModel();
        $rules = $this->readProperty($model, 'validationRules');
        $this->assertArrayHasKey('cidade', $rules);
        $this->assertStringContainsString('required', $rules['cidade']);
    }

    public function testFestaModelValidationRequiresDataHora(): void
    {
        $model = new FestaModel();
        $rules = $this->readProperty($model, 'validationRules');
        $this->assertArrayHasKey('data_hora', $rules);
        $this->assertStringContainsString('required', $rules['data_hora']);
    }

    public function testFestaModelValidationRequiresOrganizacao(): void
    {
        $model = new FestaModel();
        $rules = $this->readProperty($model, 'validationRules');
        $this->assertArrayHasKey('organizacao', $rules);
        $this->assertStringContainsString('required', $rules['organizacao']);
    }

    public function testFestaModelUsesTimestamps(): void
    {
        $model = new FestaModel();
        $this->assertTrue(
            $this->readProperty($model, 'useTimestamps'),
            'FestaModel deve usar timestamps automáticos'
        );
    }

    // ================================================================
    // ApoiadorModel
    // ================================================================

    public function testApoiadorModelHasSoftDeletes(): void
    {
        $model = new ApoiadorModel();
        $this->assertTrue(
            $this->readProperty($model, 'useSoftDeletes'),
            'ApoiadorModel deve usar soft deletes'
        );
    }

    public function testApoiadorModelAllowsPrioridade(): void
    {
        $model = new ApoiadorModel();
        $this->assertContains(
            'prioridade',
            $this->readProperty($model, 'allowedFields'),
            'ApoiadorModel deve permitir o campo prioridade'
        );
    }

    public function testApoiadorModelAllowsFrase(): void
    {
        $model = new ApoiadorModel();
        $this->assertContains(
            'frase',
            $this->readProperty($model, 'allowedFields'),
            'ApoiadorModel deve permitir o campo frase'
        );
    }

    // ================================================================
    // MidiaModel
    // ================================================================

    public function testMidiaModelAllowedFieldsHasStatus(): void
    {
        $model = new MidiaModel();
        $this->assertContains(
            'status',
            $this->readProperty($model, 'allowedFields'),
            'MidiaModel deve permitir o campo status (pendente/aprovado/rejeitado)'
        );
    }

    public function testMidiaModelUsesTimestamps(): void
    {
        $model = new MidiaModel();
        $this->assertTrue(
            $this->readProperty($model, 'useTimestamps'),
            'MidiaModel deve usar timestamps'
        );
    }

    // ================================================================
    // PedidoModel / PedidoItemModel
    // ================================================================

    public function testPedidoModelAllowedFields(): void
    {
        $model    = new PedidoModel();
        $expected = ['festa_id', 'user_id', 'status'];
        $actual   = $this->readProperty($model, 'allowedFields');
        foreach ($expected as $field) {
            $this->assertContains($field, $actual, "PedidoModel deve permitir '$field'");
        }
    }

    public function testPedidoItemModelAllowedFields(): void
    {
        $model    = new PedidoItemModel();
        $expected = ['pedido_id', 'produto_id', 'quantidade'];
        $actual   = $this->readProperty($model, 'allowedFields');
        foreach ($expected as $field) {
            $this->assertContains($field, $actual, "PedidoItemModel deve permitir '$field'");
        }
    }

    // ================================================================
    // Helper — lê propriedades privadas/protected
    // ================================================================

    private function readProperty(object $obj, string $prop): mixed
    {
        $ref = new \ReflectionClass($obj);
        while ($ref !== false) {
            if ($ref->hasProperty($prop)) {
                $property = $ref->getProperty($prop);
                $property->setAccessible(true);
                return $property->getValue($obj);
            }
            $ref = $ref->getParentClass();
        }
        $this->fail("Propriedade '$prop' não encontrada em " . get_class($obj));
    }
}
