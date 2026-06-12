<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($festa['nome_festa']) ?> — Festas Lulinas<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ══════════════════════════════════════════════════════ -->
<!-- HERO                                                   -->
<!-- ══════════════════════════════════════════════════════ -->
<div class="text-white py-5 mb-0 shadow-sm"
     style="background:linear-gradient(135deg,#b71c1c,#c62828,#7f0000);">
    <div class="container text-center">
        <h6 class="text-uppercase fw-bold mb-2" style="color:#C9971C;letter-spacing:.15em;">
            <i class="bi bi-star-fill me-1"></i> Festa Lulina Registrada
        </h6>
        <h1 class="display-4 fw-bold mb-3" style="font-family:'Bebas Neue',Impact,sans-serif;">
            <?= esc($festa['nome_festa']) ?>
        </h1>

        <div class="d-flex justify-content-center gap-4 flex-wrap fs-5 mb-3" style="color:rgba(255,255,255,.8);">
            <span><i class="bi bi-calendar-event me-1"></i>
                <?= date('d/m/Y', strtotime($festa['data_hora'])) ?>
                às <?= date('H:i', strtotime($festa['data_hora'])) ?>
            </span>
            <span><i class="bi bi-geo-alt-fill me-1"></i>
                <?= esc($festa['cidade']) ?> — <?= esc($festa['uf']) ?>
            </span>
            <?php if (!empty($festa['local_evento'])): ?>
            <span><i class="bi bi-building me-1"></i> <?= esc($festa['local_evento']) ?></span>
            <?php endif; ?>
        </div>

        <?php if (!empty($festa['organizacao'])): ?>
            <span class="badge px-3 py-2 fs-6" style="background:#C9971C;color:#000;">
                <?= esc($festa['organizacao']) ?>
            </span>
        <?php endif; ?>
        <?php if (!empty($festa['tamanho_festa'])): ?>
            <span class="badge bg-light text-dark px-3 py-2 fs-6 ms-2">
                <i class="bi bi-people-fill me-1"></i><?= esc($festa['tamanho_festa']) ?>
            </span>
        <?php endif; ?>
    </div>
</div>

<div class="container pb-5 mt-4">

    <!-- ══════════════════════════════════════════════════ -->
    <!-- FESTEIRO — Perfil do organizador                  -->
    <!-- ══════════════════════════════════════════════════ -->
    <?php if (!empty($perfil)): ?>
    <section class="mb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">
                    <i class="bi bi-person-circle me-2"></i>Sobre o Festeiro
                </h5>
                <div class="d-flex gap-4 align-items-start flex-wrap">
                    <!-- Foto -->
                    <?php if (!empty($perfil['foto'])): ?>
                    <div class="flex-shrink-0">
                        <img src="<?= base_url('uploads/perfil/' . $perfil['foto']) ?>"
                             alt="<?= esc($perfil['nome_completo']) ?>"
                             class="rounded-circle"
                             style="width:110px;height:110px;object-fit:cover;border:4px solid #C9971C;">
                    </div>
                    <?php endif; ?>

                    <!-- Dados -->
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-1"><?= esc($perfil['nome_completo']) ?></h4>
                        <?php if (!empty($perfil['profissao'])): ?>
                            <p class="text-muted mb-1"><i class="bi bi-briefcase me-1"></i><?= esc($perfil['profissao']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($perfil['filiacao'])): ?>
                            <p class="mb-1"><i class="bi bi-flag me-1 text-danger"></i><?= esc($perfil['filiacao']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($perfil['representa_entidade'])): ?>
                            <p class="mb-1 small"><i class="bi bi-building me-1"></i><?= esc($perfil['representa_entidade']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($perfil['historico_cargos'])): ?>
                            <details class="mt-2">
                                <summary class="fw-semibold text-primary" style="cursor:pointer;">
                                    <i class="bi bi-journal-text me-1"></i>Trajetória
                                </summary>
                                <div class="mt-2 text-muted small" style="white-space:pre-line;border-left:3px solid #C9971C;padding-left:12px;">
                                    <?= esc($perfil['historico_cargos']) ?>
                                </div>
                            </details>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- BLOG — Texto aprovado                             -->
    <!-- ══════════════════════════════════════════════════ -->
    <?php if (!empty($post)): ?>
    <section class="mb-5">
        <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">
            <i class="bi bi-newspaper me-2"></i>Sobre a Festa
        </h5>
        <div class="blog-content lh-lg" style="font-size:1.05rem;">
            <?= $post['conteudo'] /* HTML sanitizado do Quill */ ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- GALERIA                                           -->
    <!-- ══════════════════════════════════════════════════ -->
    <section class="mb-5">
        <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">
            <i class="bi bi-images me-2"></i>Galeria
        </h5>
        <?php if (empty($midias)): ?>
            <div class="text-center py-5 text-muted bg-light rounded border">
                <i class="bi bi-images fs-1 d-block mb-3"></i>
                <h5>Galeria em breve</h5>
                <p>As fotos deste evento ainda estão sendo selecionadas.</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                <?php foreach ($midias as $item): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <div class="ratio ratio-4x3 bg-light">
                            <?php if ($item['tipo'] === 'video'): ?>
                                <video src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>"
                                       controls class="object-fit-cover w-100 h-100"></video>
                            <?php else: ?>
                                <a href="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" target="_blank">
                                    <img src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>"
                                         class="object-fit-cover w-100 h-100 hover-zoom"
                                         alt="Registro da Festa">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- LINKS INTERESSANTES                               -->
    <!-- ══════════════════════════════════════════════════ -->
    <?php if (!empty($links)): ?>
    <section class="mb-5">
        <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">
            <i class="bi bi-link-45deg me-2"></i>Links Interessantes
        </h5>
        <div class="row row-cols-1 row-cols-md-2 g-2">
            <?php foreach ($links as $link): ?>
            <div class="col">
                <a href="<?= esc($link['url']) ?>" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 text-decoration-none p-3 border rounded bg-light hover-card">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:40px;height:40px;background:#1565C0;color:#fff;">
                        <i class="bi bi-link-45deg fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark"><?= esc($link['titulo']) ?></div>
                        <small class="text-muted text-truncate d-block" style="max-width:300px;">
                            <?= esc(parse_url($link['url'], PHP_URL_HOST)) ?>
                        </small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- HOMENAGEADOS                                      -->
    <!-- ══════════════════════════════════════════════════ -->
    <?php if (!empty($homenageados)): ?>
    <section class="mb-5">
        <h5 class="fw-bold text-danger border-bottom pb-2 mb-4">
            <i class="bi bi-stars me-2"></i>Homenageados da Festa
        </h5>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($homenageados as $hom): ?>
            <div class="col text-center">
                <?php if (!empty($hom['foto'])): ?>
                    <img src="<?= base_url('uploads/homenageados/' . $hom['foto']) ?>"
                         alt="<?= esc($hom['nome']) ?>"
                         class="rounded-circle mb-2"
                         style="width:90px;height:90px;object-fit:cover;border:3px solid #C9971C;">
                <?php else: ?>
                    <div class="rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center bg-light"
                         style="width:90px;height:90px;border:3px solid #C9971C;">
                        <i class="bi bi-person-fill text-secondary fs-2"></i>
                    </div>
                <?php endif; ?>
                <div class="fw-bold"><?= esc($hom['nome']) ?></div>
                <?php if (!empty($hom['titulo'])): ?>
                    <div class="text-muted small"><?= esc($hom['titulo']) ?></div>
                <?php endif; ?>
                <?php if (!empty($hom['frase'])): ?>
                    <p class="text-muted small mt-1 fst-italic">"<?= esc($hom['frase']) ?>"</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Voltar -->
    <div class="text-center mt-4">
        <a href="<?= base_url() ?>" class="btn btn-outline-danger btn-sm">
            &larr; Ver outras Festas Lulinas
        </a>
    </div>

</div>

<style>
.hover-zoom { transition: transform .3s ease; }
.hover-zoom:hover { transform: scale(1.05); }
.hover-card { transition: box-shadow .2s; }
.hover-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.15) !important; }
.blog-content h2 { font-size: 1.5rem; font-weight: 700; color: #b71c1c; margin-top: 1.5rem; }
.blog-content h3 { font-size: 1.2rem; font-weight: 600; color: #333; margin-top: 1rem; }
.blog-content blockquote { border-left: 4px solid #C9971C; padding-left: 1rem; color: #555; font-style: italic; margin: 1rem 0; }
</style>

<?= $this->endSection() ?>