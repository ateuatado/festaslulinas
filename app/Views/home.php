<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Início<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ============================================================
     CARROSSEL — 2 banners horizontais
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
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carrosselLulinas" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carrosselLulinas" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Proximo</span>
        </button>
    </div>
</div>

<!-- ============================================================
     FAIXA CTA
     ============================================================ -->
<div class="faixa-cta" style="background-color:#C9971C; border-bottom:3px solid #111; padding:1rem 0;">
    <div class="container">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-lg-7 text-center text-lg-start">
                <p class="cta-text mb-0 fw-bold" style="text-transform:uppercase; letter-spacing:0.03em;">
                    Organize seu comite, chame a comunidade e faca parte dessa historia.
                </p>
            </div>
            <div class="col-lg-5 d-flex gap-3 justify-content-center justify-content-lg-end flex-wrap">
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
    </div>
</div>

<!-- ============================================================
     POSTER VERTICAL + CHAMADA
     ============================================================ -->
<section class="secao-poster" style="background-color:#111111; padding:4rem 0; border-top:4px solid #C9971C; border-bottom:4px solid #C9971C;">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">

            <!-- Poster -->
            <div class="col-12 col-md-4 text-center">
                <img src="<?= base_url('assets/img/poster_vertical.png') ?>"
                     class="poster-img img-fluid"
                     style="max-width:300px; width:100%; border:4px solid #C9971C; border-radius:8px;"
                     alt="Festas Lulinas - O Festival de Festas Esta Chegando">
            </div>

            <!-- Texto -->
            <div class="col-12 col-md-8 text-center text-md-start">
                <h2 style="font-family:'Bebas Neue',Impact,sans-serif; font-size:3rem; color:#C9971C; line-height:1.05; letter-spacing:0.05em;">
                    O MUNDO<br>CELEBRA LULA
                </h2>
                <div class="datas" style="font-weight:700; color:#F0B429; text-transform:uppercase; letter-spacing:0.06em; border-left:4px solid #C8102E; padding-left:1rem; margin:1rem 0;">
                    De 13 de julho a 13 de agosto de 2026
                </div>
                <p style="color:#cccccc; font-size:1rem; line-height:1.6;">
                    As Festas Lulinas sao um movimento cultural popular que transforma o legado
                    do presidente Lula em alegria, arte e resistencia — como o Bumba Meu Boi
                    e o Sao Joao, mas do nosso tempo e da nossa luta.
                </p>
                <p style="color:#cccccc; font-size:1rem; line-height:1.6;">
                    Cadastre sua festa, envie fotos e videos, e receba materiais de apoio
                    para celebrar o Brasil Lulino em todo o territorio nacional.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4 justify-content-center justify-content-md-start">
                    <?php if (auth()->loggedIn()): ?>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-warning btn-lg fw-bold text-dark">
                            <i class="bi bi-grid-fill me-1"></i> Minhas Festas
                        </a>
                        <a href="<?= base_url('loja') ?>" class="btn btn-outline-warning btn-lg fw-bold">
                            <i class="bi bi-shop me-1"></i> Banca Lulina
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('register') ?>" class="btn btn-danger btn-lg fw-bold px-4">
                            <i class="bi bi-star-fill me-1"></i> Cadastrar Minha Festa
                        </a>
                        <a href="<?= base_url('loja') ?>" class="btn btn-outline-warning btn-lg fw-bold">
                            <i class="bi bi-shop me-1"></i> Ver a Banca
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     APOIADORES
     ============================================================ -->
<section class="secao-apoiadores" style="background-color:#F5F0E8; padding:4rem 0; border-top:4px solid #C9971C;">
    <div class="container text-center">

        <h2 class="mb-5" style="font-family:'Bebas Neue',Impact,sans-serif; font-size:2.5rem; color:#111; letter-spacing:0.04em;">
            Quem Apoia Essa Ideia
        </h2>

        <?php if (empty($apoiadores)): ?>
            <p class="text-muted">Galeria em atualizacao.</p>
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
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });
</script>
<?= $this->endSection() ?>