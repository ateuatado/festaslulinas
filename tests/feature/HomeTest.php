<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * HomeTest — testa a página pública inicial.
 *
 * @internal
 */
final class HomeTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed    = LulinasSeeder::class;
    protected $migrate = true;

    public function testHomepageRetorna200(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testHomepageEstaAcessivelSemLogin(): void
    {
        // Não autentica ninguém — deve funcionar normalmente
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testHomepageNaoRedirecionaParaLogin(): void
    {
        $result = $this->get('/');
        // Não deve redirecionar para /login
        $this->assertFalse($result->isRedirect(), 'A homepage não deve exigir autenticação');
    }
}
