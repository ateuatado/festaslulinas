<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Database\Seeds\LulinasSeeder;

/**
 * HomeTest â€” testa a pÃ¡gina pÃºblica inicial.
 *
 * @internal
 */
final class HomeTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed      = LulinasSeeder::class;
    protected $basePath   = ROOTPATH . 'tests/_support/Database';
    protected $namespace  = null;
    protected $migrate    = true;
    protected $refresh    = true;

    public function testHomepageRetorna200(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testHomepageEstaAcessivelSemLogin(): void
    {
        // NÃ£o autentica ninguÃ©m â€” deve funcionar normalmente
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testHomepageNaoRedirecionaParaLogin(): void
    {
        $result = $this->get('/');
        // NÃ£o deve redirecionar para /login
        $this->assertFalse($result->isRedirect(), 'A homepage nÃ£o deve exigir autenticaÃ§Ã£o');
    }
}

