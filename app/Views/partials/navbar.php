<nav class="navbar navbar-expand-lg navbar-light bg-lulina-nav mb-0 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="<?= base_url() ?>">
            Festas Lulinas
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url() ?>">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url_is('loja*') ? 'active fw-bold text-danger' : '' ?>" href="<?= base_url('loja') ?>">
                        <i class="bi bi-shop me-1"></i> Banca Lulina
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">

                <!-- Ícone do carrinho (sempre visível) -->
                <?php $cartCount = count(session()->get('carrinho') ?? []); ?>
                <li class="nav-item">
                    <a href="<?= base_url('carrinho') ?>"
                       class="btn btn-outline-danger btn-sm position-relative"
                       title="Meu Carrinho">
                        <i class="bi bi-cart3"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size:.6rem;">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if (auth()->loggedIn()): ?>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-dark btn-sm fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-grid-fill"></i> Minhas Festas
                        </a>
                    </li>
                    
                    <?php if (auth()->user()->inGroup('admin')): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin') ?>" class="btn btn-danger btn-sm d-flex align-items-center gap-2 text-white">
                                <i class="bi bi-shield-lock-fill"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= base_url('logout') ?>">Sair</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= base_url('login') ?>" class="nav-link fw-bold text-dark">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('register') ?>" class="btn btn-danger btn-sm text-white">Criar Conta</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>