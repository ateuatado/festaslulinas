<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Festas <?= $ano ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero compacto -->
<div class="py-4" style="background:linear-gradient(135deg,#b71c1c,#c62828,#7f0000); border-bottom:4px solid #C9971C;">
    <div class="container text-center text-white">
        <h6 class="text-uppercase fw-bold mb-2" style="color:#C9971C;letter-spacing:.15em;font-size:.8rem;">
            <i class="bi bi-star-fill me-1"></i> Brasil Lulino · 13 Jul – 13 Ago
        </h6>

        <!-- Título + Combo de ano na mesma linha -->
        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap mb-2">
            <h1 class="fw-bold mb-0" style="font-family:'Bebas Neue',Impact,sans-serif;font-size:2.8rem;letter-spacing:.05em;">
                Festas
            </h1>
            <!-- Combo de ano -->
            <form method="get" action="<?= base_url('festas') ?>" id="form-ano" class="mb-0">
                <select name="ano" id="combo-ano"
                        class="form-select form-select-lg fw-bold text-center"
                        style="font-family:'Bebas Neue',Impact,sans-serif;font-size:2rem;
                               background:#C9971C;color:#111;border:3px solid #111;
                               border-radius:8px;padding:.2rem .8rem;cursor:pointer;
                               appearance:none;-webkit-appearance:none;min-width:110px;"
                        onchange="document.getElementById('form-ano').submit()">
                    <?php foreach ($anos as $a): ?>
                        <option value="<?= $a ?>" <?= $a === $ano ? 'selected' : '' ?>>
                            <?= $a ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <p class="mb-0" style="color:rgba(255,255,255,.8);font-size:1rem;">
            Encontre uma Festa Lulina perto de você e celebre o Brasil Lulino!
        </p>
    </div>
</div>

<div class="container py-5">

    <?php if (empty($festas)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            <h4>Nenhuma festa encontrada em <?= $ano ?>.</h4>
            <?php if ($ano == date('Y')): ?>
                <p>As festas de <?= $ano ?> serão divulgadas em breve!</p>
                <?php if (!auth()->loggedIn()): ?>
                <a href="<?= base_url('register') ?>" class="btn btn-danger btn-lg fw-bold mt-2">
                    <i class="bi bi-star-fill me-1"></i> Cadastre a sua!
                </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    <?php else: ?>

        <!-- Contagem + CTA -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-danger">
                <i class="bi bi-geo-alt-fill me-2"></i>
                <?= count($festas) ?> festa<?= count($festas) > 1 ? 's' : '' ?>
                encontrada<?= count($festas) > 1 ? 's' : '' ?> em <?= $ano ?>
            </h5>
            <?php if (!auth()->loggedIn()): ?>
            <a href="<?= base_url('register') ?>" class="btn btn-outline-danger fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Cadastre a sua!
            </a>
            <?php endif; ?>
        </div>

        <!-- Grid de cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($festas as $festa): ?>
            <div class="col">
                <a href="<?= base_url('festa/' . ($festa['slug'] ?: $festa['id'])) ?>"
                   class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-card"
                         style="border-radius:12px;overflow:hidden;">

                        <!-- Faixa de data -->
                        <div class="d-flex align-items-center justify-content-between px-3 py-2"
                             style="background:#b71c1c;color:#fff;">
                            <span class="fw-bold" style="font-size:.85rem;">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('d/m/Y', strtotime($festa['data_hora'])) ?>
                                às <?= date('H:i', strtotime($festa['data_hora'])) ?>
                            </span>
                            <span class="badge" style="background:#C9971C;color:#000;font-size:.75rem;">
                                <?= esc($festa['uf']) ?>
                            </span>
                        </div>

                        <div class="card-body p-3">
                            <h5 class="fw-bold text-danger mb-1" style="font-size:1.05rem;">
                                <?= esc($festa['nome_festa']) ?>
                            </h5>
                            <?php if (!empty($festa['organizacao'])): ?>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-people-fill me-1"></i><?= esc($festa['organizacao']) ?>
                            </div>
                            <?php endif; ?>

                            <div class="small text-muted">
                                <i class="bi bi-geo-alt me-1 text-danger"></i>
                                <?php
                                    $end = '';
                                    if (!empty($festa['logradouro'])) {
                                        $end .= $festa['logradouro'];
                                        if (!empty($festa['numero'])) $end .= ', ' . $festa['numero'];
                                        $end .= ' — ';
                                    }
                                    $end .= $festa['cidade'] . ' / ' . $festa['uf'];
                                    echo esc($end);
                                ?>
                            </div>

                            <?php if (!empty($festa['local_evento'])): ?>
                            <div class="small text-muted mt-1">
                                <i class="bi bi-building me-1"></i><?= esc($festa['local_evento']) ?>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($festa['tamanho_festa'])): ?>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark border" style="font-size:.75rem;">
                                    <i class="bi bi-people me-1"></i><?= esc($festa['tamanho_festa']) ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer bg-white border-0 px-3 pb-3 pt-0">
                            <span class="btn btn-sm w-100 fw-bold"
                                  style="background:#b71c1c;color:#fff;">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Ver página da festa
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <!-- Voltar -->
    <div class="text-center mt-5">
        <a href="<?= base_url() ?>" class="btn btn-outline-secondary btn-sm">
            &larr; Voltar à página inicial
        </a>
    </div>

</div>

<style>
.hover-card { transition: transform .2s, box-shadow .2s; }
.hover-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.15) !important; }
</style>

<?= $this->endSection() ?>
