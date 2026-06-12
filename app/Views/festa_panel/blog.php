<?php $secaoAtiva = 'blog'; ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Blog — <?= esc($festa['nome_festa']) ?><?= $this->endSection() ?>

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
                &bull; <a href="<?= base_url('festa/' . $festa['slug']) ?>" target="_blank" class="text-primary small">
                    <i class="bi bi-box-arrow-up-right"></i> Ver página pública
                </a>
                <?php endif; ?>
            </small>
        </div>
    </div>

    <!-- Sub-navegação -->
    <nav class="nav nav-pills nav-fill border rounded p-1 mb-4 bg-light">
        <a class="nav-link active" href="<?= base_url('festa-panel/' . $festa['id'] . '/blog') ?>">
            <i class="bi bi-pencil-square me-1"></i> Blog
        </a>
        <a class="nav-link" href="<?= base_url('festa-panel/' . $festa['id'] . '/links') ?>">
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

    <?php
        $statusLabel = [
            'rascunho'  => ['class' => 'bg-secondary', 'icon' => 'bi-file-earmark', 'txt' => 'Rascunho'],
            'pendente'  => ['class' => 'bg-warning text-dark', 'icon' => 'bi-clock-history', 'txt' => 'Aguardando aprovação'],
            'aprovado'  => ['class' => 'bg-success', 'icon' => 'bi-check-circle-fill', 'txt' => 'Aprovado — Publicado!'],
            'rejeitado' => ['class' => 'bg-danger', 'icon' => 'bi-x-circle-fill', 'txt' => 'Rejeitado pelo Admin — Reescreva e reenvie'],
        ];
        $statusAtual = $post['status'] ?? 'rascunho';
        $sl = $statusLabel[$statusAtual];
    ?>

    <!-- Status Badge -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <span class="badge fs-6 px-3 py-2 <?= $sl['class'] ?>">
            <i class="bi <?= $sl['icon'] ?> me-1"></i> <?= $sl['txt'] ?>
        </span>
        <?php if ($statusAtual === 'aprovado'): ?>
            <span class="text-muted small">O texto abaixo é o que aparece na página pública.</span>
        <?php elseif ($statusAtual === 'pendente'): ?>
            <span class="text-muted small">Aguardando o administrador. Você pode salvar um novo rascunho sem perder o enviado.</span>
        <?php endif; ?>
    </div>

    <!-- Editor -->
    <form action="<?= base_url('festa-panel/' . $festa['id'] . '/blog/salvar') ?>" method="post" id="formBlog">
        <?= csrf_field() ?>

        <!-- Toolbar do Quill -->
        <div id="quill-toolbar" class="border border-bottom-0 rounded-top bg-white">
            <span class="ql-formats">
                <select class="ql-header">
                    <option value="2">Título</option>
                    <option value="3">Sub-título</option>
                    <option selected></option>
                </select>
            </span>
            <span class="ql-formats">
                <button class="ql-bold"></button>
                <button class="ql-italic"></button>
                <button class="ql-underline"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-blockquote"></button>
                <button class="ql-link"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-clean"></button>
            </span>
        </div>

        <!-- Área do Editor -->
        <div id="quill-editor"
             style="min-height:350px;border:1px solid #dee2e6;border-top:0;border-radius:0 0 0.375rem 0.375rem;
                    background:#fff;font-size:1.05rem;font-family:'Barlow',sans-serif;"
        ><?= $post['conteudo'] ?? '' ?></div>

        <!-- Campo hidden que recebe o HTML do Quill -->
        <input type="hidden" name="conteudo" id="conteudo-hidden">

        <!-- Botões de ação -->
        <div class="d-flex gap-2 mt-3 flex-wrap">
            <button type="submit" name="acao" value="rascunho" class="btn btn-outline-secondary">
                <i class="bi bi-floppy me-1"></i> Salvar Rascunho
            </button>
            <button type="submit" name="acao" value="publicar" class="btn btn-danger fw-bold px-4"
                    onclick="return confirm('Enviar para aprovação do administrador?')">
                <i class="bi bi-send-fill me-1"></i> Enviar para Aprovação
            </button>
        </div>

        <p class="form-text mt-2">
            <i class="bi bi-info-circle"></i>
            O texto só aparece na página pública após aprovação do administrador.
        </p>
    </form>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Quill.js CDN -->
<link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<script>
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: { toolbar: '#quill-toolbar' },
        placeholder: 'Conte a história da sua festa — a motivação, o local, quem vai participar, o que esperar...',
    });

    // Injeta o HTML no campo hidden antes do submit
    document.getElementById('formBlog').addEventListener('submit', function () {
        document.getElementById('conteudo-hidden').value = quill.getSemanticHTML();
    });
</script>
<?= $this->endSection() ?>
