<nav class="navbar navbar-expand-lg navbar-dark navbar-lulina mb-0 shadow"
     style="background-color:#1565C0 !important; border-bottom:3px solid #C9971C;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url() ?>"
           style="font-family:'Bebas Neue',Impact,sans-serif; font-size:1.8rem; letter-spacing:0.05em; color:#fff;">
            Festas Lulinas
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="<?= base_url() ?>">Início</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">

                <?php if (auth()->loggedIn()): ?>

                    <li class="nav-item">
                        <a href="<?= base_url('perfil') ?>"
                           class="nav-link text-white fw-semibold d-flex align-items-center gap-1"
                           title="Meu Perfil de Festeiro">
                            <i class="bi bi-person-circle"></i>
                            <span class="d-lg-inline">Meu Perfil</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>"
                           class="btn btn-outline-warning btn-sm fw-bold d-flex align-items-center gap-1 text-white">
                            <i class="bi bi-grid-fill"></i> Minhas Festas
                        </a>
                    </li>

                    <?php if (auth()->user()->inGroup('admin')): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin') ?>"
                               class="btn btn-danger btn-sm d-flex align-items-center gap-1 text-white">
                                <i class="bi bi-shield-lock-fill"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('logout') ?>">Sair</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a href="<?= base_url('login') ?>" class="nav-link text-white fw-bold">Entrar</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>