<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Início<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ============================================================
     CARROSSEL — mantém full-width (é o hero)
     ============================================================ -->
<div class="hero-carousel" style="background-color:#ffffff; border-bottom:4px solid #C9971C;">
    <div id="carrosselLulinas" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carrosselLulinas" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carrosselLulinas" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?= base_url('assets/img/carousel_1.png') ?>"
                     class="d-block w-100"
                     alt="Brasil Lulino - Nacao Soberana">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('assets/img/carousel_2.png') ?>"
                     class="d-block w-100"
                     alt="Festas Lulinas - De 13 de julho a 13 de agosto">
            </div>
        </div><!-- /carousel-inner -->

    </div>
</div>

<!-- ============================================================
     CORPO DA PÁGINA — tudo dentro de um container
     ============================================================ -->
<div class="container py-5">

    <!-- FAIXA CTA ──────────────────────────────────────────── -->
    <div class="rounded-4 mb-4 px-4 py-3"
         style="background-color:#C9971C; border:3px solid #111;">

        <!-- Linha 1: texto + botões -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <p class="cta-text mb-0 fw-bold" style="text-transform:uppercase; letter-spacing:0.03em; color:#111;">
                Organize seu comitê, chame a comunidade e faça parte dessa história!
            </p>
            <div class="d-flex gap-3 flex-wrap">
                <?php if (auth()->loggedIn()): ?>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-dark fw-bold text-warning">
                        <i class="bi bi-grid-fill me-1"></i> Acessar Painel
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('register') ?>" class="btn btn-danger fw-bold px-4">
                        <i class="bi bi-star-fill me-1"></i> QUERO ORGANIZAR
                    </a>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-dark fw-bold px-4">
                        Entrar
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Linha 2: link para a listagem de festas -->
        <div class="mt-2 pt-2" style="border-top:1px solid rgba(0,0,0,.2);">
            <a href="<?= base_url('festas') ?>"
               class="fw-semibold text-decoration-none"
               style="color:#111; font-size:.95rem;">
                <i class="bi bi-map-fill me-1"></i>
                Confira as Festas de 2026 &rarr;
            </a>
        </div>

    </div>

    <!-- POSTER VERTICAL + CHAMADA ─────────────────────────── -->
    <div class="rounded-4 overflow-hidden mb-5"
         style="background-color:#111111; border:3px solid #C9971C;">
        <div class="row align-items-center g-0">

            <!-- Poster -->
            <div class="col-12 col-md-4 text-center p-4 p-md-5"
                 style="border-right:3px solid #C9971C;">
                <img src="<?= base_url('assets/img/poster_vertical.png') ?>"
                     class="img-fluid"
                     style="max-width:260px; width:100%; border:4px solid #C9971C; border-radius:8px;"
                     alt="Festas Lulinas - O Festival de Festas Esta Chegando">
            </div>

            <!-- Texto -->
            <div class="col-12 col-md-8 p-4 p-md-5 text-center text-md-start">
                <h2 style="font-family:'Bebas Neue',Impact,sans-serif; font-size:3rem; color:#C9971C; line-height:1.05; letter-spacing:0.05em;">
                    O MUNDO<br>CELEBRA LULA
                </h2>
                <div style="font-weight:700; color:#F0B429; text-transform:uppercase; letter-spacing:0.06em; border-left:4px solid #C8102E; padding-left:1rem; margin:1rem 0;">
                    De 13 de julho a 13 de agosto de 2026
                </div>
                <p style="color:#cccccc; font-size:1rem; line-height:1.7;">
                    As Festas Lulinas, movimento cultural popular que referenda o legado do presidente Lula com alegria, arte e resistência — do Boi Mamão aos bois de Parintins passando por São João, balançando no samba, coco, tecnobrega enfim todas as manifestações culturais que ele sempre apoiou e deu amplitude em seus governos, nosso tempo e cultura vista com soberania e apoiando nossas luta.
                </p>
                <p style="color:#cccccc; font-size:1rem; line-height:1.7;">
                    Cadastre sua festa, envie fotos e vídeos, e receba materiais de apoio
                    para celebrar o Brasil Lulino em todo o território nacional.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4 justify-content-center justify-content-md-start">
                    <?php if (auth()->loggedIn()): ?>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-warning btn-lg fw-bold text-dark">
                            <i class="bi bi-grid-fill me-1"></i> Minhas Festas
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('register') ?>" class="btn btn-danger btn-lg fw-bold px-4">
                            <i class="bi bi-star-fill me-1"></i> Cadastrar Minha Festa
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- APOIADORES ──────────────────────────────────────────── -->
    <div class="rounded-4 p-4 p-md-5" style="background-color:#F5F0E8; border:3px solid #C9971C;">
        <h2 class="text-center mb-5"
            style="font-family:'Bebas Neue',Impact,sans-serif; font-size:2.5rem; color:#111; letter-spacing:0.04em;">
            Quem Apoia Essa Ideia
        </h2>

        <?php if (empty($apoiadores)): ?>
            <p class="text-muted text-center">Galeria em atualizacao.</p>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center">
                <?php foreach ($apoiadores as $item): ?>
                    <div class="col">
                        <div class="apoiador-card text-center">
                            <div class="d-flex justify-content-center"
                                <?php if (!empty($item['frase'])): ?>
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="<?= esc($item['frase']) ?>"
                                <?php endif; ?>>
                                <img src="<?= base_url('uploads/apoiadores/' . $item['foto']) ?>"
                                     class="apoiador-foto"
                                     style="width:110px; height:110px; object-fit:cover; border-radius:50%; border:4px solid #C9971C; display:block;"
                                     alt="<?= esc($item['nome']) ?>"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=<?= urlencode($item['nome']) ?>&size=110&background=1565C0&color=fff&bold=true&rounded=true'">
                            </div>
                            <h5 class="mt-2 mb-0 fw-bold" style="font-size:0.95rem;">
                                <?= esc($item['nome']) ?>
                            </h5>
                            <small class="text-muted"><?= esc($item['funcao']) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div><!-- /container -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
</script>
<?= $this->endSection() ?>