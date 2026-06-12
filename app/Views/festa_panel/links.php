<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Links — <?= esc($festa['nome_festa']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <!-- Cabeçalho -->
    <div class="d-flex align-items-center gap-3 mb-1">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold text-danger"><?= esc($festa['nome_festa']) ?></h4>
            <small class="text-muted">
                <?= date('d/m/Y', strtotime($festa['data_hora'])) ?> &bull; <?= esc($festa['cidade']) ?>/<?= esc($festa['uf']) ?>
            </small>
        </div>
    </div>

    <!-- Sub-navegação -->
    <nav class="nav nav-pills nav-fill border rounded p-1 mb-4 bg-light">
        <a class="nav-link" href="<?= base_url('festa-panel/' . $festa['id'] . '/blog') ?>">
            <i class="bi bi-pencil-square me-1"></i> Blog
        </a>
        <a class="nav-link active" href="<?= base_url('festa-panel/' . $festa['id'] . '/links') ?>">
            <i class="bi bi-link-45deg me-1"></i> Links
        </a>
        <a class="nav-link" href="<?= base_url('festa-panel/' . $festa['id'] . '/homenageados') ?>">
            <i class="bi bi-star-fill me-1"></i> Homenageados
        </a>
        <a class="nav-link" href="<?= base_url('galeria/' . $festa['id']) ?>">
            <i class="bi bi-camera-fill me-1"></i> Fotos
        </a>
        <a class="nav-link" href="<?= base_url('dashboard/editar/' . $festa['id']) ?>">
            <i class="bi bi-gear me-1"></i> Dados
        </a>
    </nav>

    <div class="row g-4">

        <!-- Formulário de Adicionar -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header fw-bold" style="background:#1565C0;color:#fff;">
                    <i class="bi bi-plus-circle me-1"></i> Adicionar Link
                </div>
                <div class="card-body">
                    <form action="<?= base_url('festa-panel/' . $festa['id'] . '/links/adicionar') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título do Link</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ex: Grupo do WhatsApp, Transmissão ao Vivo..." required maxlength="150">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL</label>
                            <input type="url" name="url" class="form-control"
                                   placeholder="https://..." required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-send me-1"></i> Enviar para Aprovação
                            </button>
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle"></i> Links são publicados após aprovação do administrador.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Links -->
        <div class="col-md-7">
            <h5 class="fw-bold mb-3">Seus Links</h5>
            <?php if (empty($links)): ?>
                <div class="alert alert-light border text-center">
                    <i class="bi bi-link-45deg fs-2 d-block mb-2 text-muted"></i>
                    Nenhum link cadastrado ainda.
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($links as $link):
                        $badgeMap = ['pendente' => 'bg-warning text-dark', 'aprovado' => 'bg-success', 'rejeitado' => 'bg-danger'];
                        $badgeTxt = ['pendente' => 'Aguardando', 'aprovado' => 'Publicado', 'rejeitado' => 'Rejeitado'];
                    ?>
                    <div class="list-group-item d-flex align-items-center gap-3">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold"><?= esc($link['titulo']) ?></div>
                            <a href="<?= esc($link['url']) ?>" target="_blank"
                               class="small text-truncate d-block text-primary" style="max-width:300px;">
                                <?= esc($link['url']) ?>
                            </a>
                        </div>
                        <span class="badge <?= $badgeMap[$link['status']] ?> flex-shrink-0">
                            <?= $badgeTxt[$link['status']] ?>
                        </span>
                        <form action="<?= base_url('festa-panel/' . $festa['id'] . '/links/remover/' . $link['id']) ?>"
                              method="post" onsubmit="return confirm('Remover este link?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
