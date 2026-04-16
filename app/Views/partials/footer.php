<footer class="bg-dark text-white pt-5 pb-3 mt-auto border-top border-4 border-warning">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-lg-4 col-md-6">
                <h5 class="text-warning fw-bold mb-3">Festas Lulinas</h5>
                <p class="small text-white-50">
                    O registro histórico da nossa luta e alegria. Um movimento popular para unir o Brasil através da cultura, da festa e da esperança.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white text-decoration-none"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none"><i class="bi bi-twitter-x fs-5"></i></a>
                    <a href="#" class="text-white text-decoration-none"><i class="bi bi-youtube fs-5"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="text-uppercase fw-bold mb-3 text-white-50">Navegação</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url() ?>" class="text-white text-decoration-none hover-link">Página Inicial</a></li>
                    <?php if (auth()->loggedIn()): ?>
                        <li class="mb-2"><a href="<?= base_url('dashboard') ?>" class="text-white text-decoration-none hover-link">Minhas Festas</a></li>
                    <?php else: ?>
                        <li class="mb-2"><a href="<?= base_url('register') ?>" class="text-white text-decoration-none hover-link">Cadastrar Festa</a></li>
                        <li class="mb-2"><a href="<?= base_url('login') ?>" class="text-white text-decoration-none hover-link">Entrar no Sistema</a></li>
                    <?php endif; ?>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none hover-link">Termos de Uso</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h6 class="text-uppercase fw-bold mb-3 text-white-50">Realização</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-danger text-white rounded d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;">
                        PT
                    </div>
                    <div>
                        <p class="mb-0 fw-bold small">Comitê Popular de Luta</p>
                        <p class="mb-0 small text-white-50">Brasil da Esperança</p>
                    </div>
                </div>
                <p class="small text-white-50">
                    Dúvidas? <br>
                    <a href="mailto:contato@festaslulinas.com.br" class="text-warning text-decoration-none">contato@festaslulinas.com.br</a>
                </p>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small text-white-50">
                    &copy; <?= date('Y') ?> Festas Lulinas. Todos os direitos reservados.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small text-white-50">
                    Feito com <i class="bi bi-heart-fill text-danger"></i> pela militância digital.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    .hover-link:hover {
        color: #FFB127 !important; /* Amarelo Lulina */
        text-decoration: underline !important;
    }
</style>