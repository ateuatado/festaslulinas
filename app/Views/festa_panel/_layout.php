<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Blog da Festa — <?= esc($festa['nome_festa']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <!-- Cabeçalho da Festa -->
    <div class="d-flex align-items-center gap-3 mb-1">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold text-danger"><?= esc($festa['nome_festa']) ?></h4>
            <small class="text-muted">
                <?= date('d/m/Y', strtotime($festa['data_hora'])) ?> &bull;
                <?= esc($festa['cidade']) ?>/<?= esc($festa['uf']) ?>
                <?php if (!empty($festa['slug'])): ?>
                &bull; <a href="<?= base_url('festa/' . $festa['slug']) ?>" target="_blank" class="text-primary">
                    <i class="bi bi-box-arrow-up-right"></i> Ver página pública
                </a>
                <?php endif; ?>
            </small>
        </div>
    </div>

    <!-- Sub-navegação -->
    <nav class="nav nav-pills nav-fill border rounded p-1 mb-4 bg-light">
        <a class="nav-link <?= ($secaoAtiva ?? '') === 'blog' ? 'active' : '' ?>"
           href="<?= base_url('festa-panel/' . $festa['id'] . '/blog') ?>">
            <i class="bi bi-pencil-square me-1"></i> Blog
        </a>
        <a class="nav-link <?= ($secaoAtiva ?? '') === 'links' ? 'active' : '' ?>"
           href="<?= base_url('festa-panel/' . $festa['id'] . '/links') ?>">
            <i class="bi bi-link-45deg me-1"></i> Links
        </a>
        <a class="nav-link <?= ($secaoAtiva ?? '') === 'homenageados' ? 'active' : '' ?>"
           href="<?= base_url('festa-panel/' . $festa['id'] . '/homenageados') ?>">
            <i class="bi bi-star-fill me-1"></i> Homenageados
        </a>
        <a class="nav-link" href="<?= base_url('galeria/' . $festa['id']) ?>">
            <i class="bi bi-camera-fill me-1"></i> Fotos
        </a>
        <a class="nav-link" href="<?= base_url('dashboard/editar/' . $festa['id']) ?>">
            <i class="bi bi-gear me-1"></i> Dados
        </a>
    </nav>

    <!-- Conteúdo injetado pela view filha -->
    <?= $this->renderSection('painel_content') ?>

</div>
<?= $this->endSection() ?>
