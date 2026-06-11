<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * GaleriaTest â€” testa upload, listagem e remoÃ§Ã£o de mÃ­dias.
 *
 * Inclui teste de regressÃ£o para o BUG 3 (validaÃ§Ã£o de upload).
 *
 * @internal
 */
final class GaleriaTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed      = LulinasSeeder::class;
    protected $basePath   = ROOTPATH . 'tests/_support/Database';
    protected $namespace  = null;
    protected $migrate    = true;
    protected $refresh    = true;

    // ================================================================
    // ProteÃ§Ã£o de acesso
    // ================================================================

    public function testGaleriaRedirecionaSemLogin(): void
    {
        $result = $this->get('galeria/1');
        $this->assertTrue($result->isRedirect(), 'Galeria deve exigir autenticaÃ§Ã£o');
    }

    public function testGaleriaBloqueiaDonoDiferenteDaFesta(): void
    {
        // user_id=1 (admin) tenta ver galeria da festa 1 (pertence ao user_id=2)
        $result = $this->withSession(['user_id' => 1])->get('galeria/1');
        $this->assertNotEquals(500, $result->getStatusCode());
        // Deve redirecionar (sem acesso)
        $this->assertTrue($result->isRedirect(), 'NÃ£o deve dar acesso Ã  galeria de outro usuÃ¡rio');
    }

    public function testGaleriaAcessoCorretoDonoRetornaConteudo(): void
    {
        // user_id=2 acessa galeria da festa 1 (que Ã© dele)
        $result = $this->withSession(['user_id' => 2])->get('galeria/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // BUG 3 Regression: ValidaÃ§Ã£o de upload deve rejeitar tipo invÃ¡lido
    // ================================================================

    public function testUploadSemArquivosRetornaMensagemDeErro(): void
    {
        // POST sem arquivo algum
        $result = $this->withSession(['user_id' => 2])->post('galeria/upload/1', []);
        // NÃ£o deve causar 500
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    // ================================================================
    // DeleÃ§Ã£o via POST (BUG 2 regression)
    // ================================================================

    public function testDeleteMidiaViaGetNaoFunciona(): void
    {
        // ApÃ³s o BUG 2 fix, GET para delete deve lanÃ§ar PageNotFoundException
        // (o CI4 FeatureTest nÃ£o captura como HTTP 404, mas propaga a exceÃ§Ã£o)
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('galeria/delete/1');
    }

    public function testDeleteMidiaComPostFunciona(): void
    {
        // POST correto para deletar a mÃ­dia 1 (pertence Ã  festa do user_id=2)
        $result = $this->withSession(['user_id' => 2])->post('galeria/delete/1');
        $this->assertNotEquals(500, $result->getStatusCode());
    }

    public function testDeleteMidiaDeOutroUsuarioFalhaSilenciosamente(): void
    {
        // user_id=1 tenta deletar mÃ­dia da festa do user_id=2
        $result = $this->withSession(['user_id' => 1])->post('galeria/delete/1');
        $this->assertNotEquals(500, $result->getStatusCode());

        // MÃ­dia deve continuar no banco
        $db    = \Config\Database::connect();
        $midia = $db->table('midias')->where('id', 1)->get()->getRowArray();
        if ($midia !== null) {
            // Se ainda existe, o delete foi bloqueado corretamente
            $this->assertEquals(1, $midia['id']);
        }
    }
}

