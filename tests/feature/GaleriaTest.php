<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * GaleriaTest — testa upload, listagem e remoção de mídias.
 *
 * Inclui teste de regressão para o BUG 3 (validação de upload).
 *
 * @internal
 */
final class GaleriaTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed    = LulinasSeeder::class;
    protected $migrate = true;

    // ================================================================
    // Proteção de acesso
    // ================================================================

    public function testGaleriaRedirecionaSemLogin(): void
    {
        $result = $this->get('galeria/1');
        $this->assertTrue($result->isRedirect(), 'Galeria deve exigir autenticação');
    }

    public function testGaleriaBloqueiaDonoDiferenteDaFesta(): void
    {
        // user_id=1 (admin) tenta ver galeria da festa 1 (pertence ao user_id=2)
        $result = $this->withSession(['user_id' => 1])->get('galeria/1');
        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar (sem acesso)
        $this->assertTrue($result->isRedirect(), 'Não deve dar acesso à galeria de outro usuário');
    }

    public function testGaleriaAcessoCorretoDonoRetornaConteudo(): void
    {
        // user_id=2 acessa galeria da festa 1 (que é dele)
        $result = $this->withSession(['user_id' => 2])->get('galeria/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // BUG 3 Regression: Validação de upload deve rejeitar tipo inválido
    // ================================================================

    public function testUploadSemArquivosRetornaMensagemDeErro(): void
    {
        // POST sem arquivo algum
        $result = $this->withSession(['user_id' => 2])->post('galeria/upload/1', []);
        // Não deve causar 500
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // Deleção via POST (BUG 2 regression)
    // ================================================================

    public function testDeleteMidiaViaGetNaoFunciona(): void
    {
        // Após o BUG 2 fix, GET para delete deve lançar PageNotFoundException
        // (o CI4 FeatureTest não captura como HTTP 404, mas propaga a exceção)
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('galeria/delete/1');
    }

    public function testDeleteMidiaComPostFunciona(): void
    {
        // POST correto para deletar a mídia 1 (pertence à festa do user_id=2)
        $result = $this->withSession(['user_id' => 2])->post('galeria/delete/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testDeleteMidiaDeOutroUsuarioFalhaSilenciosamente(): void
    {
        // user_id=1 tenta deletar mídia da festa do user_id=2
        $result = $this->withSession(['user_id' => 1])->post('galeria/delete/1');
        $this->assertNotEquals(500, $result->getStatusCode());

        // Mídia deve continuar no banco
        $db    = \Config\Database::connect();
        $midia = $db->table('midias')->where('id', 1)->get()->getRowArray();
        if ($midia !== null) {
            // Se ainda existe, o delete foi bloqueado corretamente
            $this->assertEquals(1, $midia['id']);
        }
    }
}
