<footer class="footer-lulina pt-5 pb-3 mt-auto"
        style="background-color:#111111 !important; border-top:4px solid #C9971C; color:#aaaaaa;">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3" style="color:#C9971C;">Festas Lulinas</h5>
                <p class="small" style="color:#aaaaaa;">
                    O registro histórico da nossa luta e alegria. Um movimento popular para unir o Brasil através da cultura, da festa e da esperança.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" style="color:#ffffff; text-decoration:none;"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" style="color:#ffffff; text-decoration:none;"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" style="color:#ffffff; text-decoration:none;"><i class="bi bi-twitter-x fs-5"></i></a>
                    <a href="#" style="color:#ffffff; text-decoration:none;"><i class="bi bi-youtube fs-5"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="text-uppercase fw-bold mb-3" style="color:#888888;">Navegação</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url() ?>" class="hover-link" style="color:#cccccc; text-decoration:none;">Página Inicial</a></li>
                    <?php if (auth()->loggedIn()): ?>
                        <li class="mb-2"><a href="<?= base_url('dashboard') ?>" class="hover-link" style="color:#cccccc; text-decoration:none;">Minhas Festas</a></li>
                    <?php else: ?>
                        <li class="mb-2"><a href="<?= base_url('register') ?>" class="hover-link" style="color:#cccccc; text-decoration:none;">Cadastrar Festa</a></li>
                        <li class="mb-2"><a href="<?= base_url('login') ?>" class="hover-link" style="color:#cccccc; text-decoration:none;">Entrar no Sistema</a></li>
                    <?php endif; ?>
                    <li class="mb-2"><a href="#" class="hover-link" style="color:#cccccc; text-decoration:none;">Termos de Uso</a></li>
                </ul>
            </div>
        </div><!-- /row g-4 -->

        <hr style="border-color:#333333; margin:1.5rem 0;">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small" style="color:#666666;">
                    &copy; <?= date('Y') ?> Festas Lulinas. Todos os direitos reservados.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small" style="color:#666666;">
                    Feito com <i class="bi bi-heart-fill" style="color:#C8102E;"></i> pela militância digital.
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