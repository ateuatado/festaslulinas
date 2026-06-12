<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Homenageados — <?= esc($festa['nome_festa']) ?><?= $this->endSection() ?>

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
        <a class="nav-link" href="<?= base_url('festa-panel/' . $festa['id'] . '/links') ?>">
            <i class="bi bi-link-45deg me-1"></i> Links
        </a>
        <a class="nav-link active" href="<?= base_url('festa-panel/' . $festa['id'] . '/homenageados') ?>">
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

        <!-- Formulário Adicionar -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header fw-bold" style="background:#C9971C;color:#fff;">
                    <i class="bi bi-person-plus-fill me-1"></i> Adicionar Homenageado
                </div>
                <div class="card-body">
                    <form action="<?= base_url('festa-panel/' . $festa['id'] . '/homenageados/adicionar') ?>"
                          method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Preview de foto -->
                        <div class="text-center mb-3">
                            <div id="foto-preview-wrap"
                                 style="width:90px;height:90px;border-radius:50%;border:3px solid #C9971C;
                                        overflow:hidden;margin:0 auto 8px;background:#f0f0f0;
                                        display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-circle" style="font-size:2.5rem;color:#aaa;" id="foto-icon"></i>
                                <img id="foto-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;" alt="">
                            </div>
                            <label for="foto" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-camera me-1"></i> Foto (opcional)
                            </label>
                            <input type="file" name="foto" id="foto" accept="image/*"
                                   class="d-none" onchange="previewFoto(this)">
                            <div class="form-text">JPG/PNG até 5MB</div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control"
                                   placeholder="Nome completo" required maxlength="150">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Título / Papel</label>
                            <input type="text" name="titulo" class="form-control"
                                   placeholder="Ex: Madrinha, DJ, Palestrante..." maxlength="150">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Frase ou Homenagem</label>
                            <textarea name="frase" class="form-control" rows="2"
                                      placeholder="Uma frase de homenagem ou apresentação..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ordem de Exibição</label>
                            <input type="number" name="ordem" class="form-control" value="0" min="0">
                            <div class="form-text">Menor número = aparece primeiro.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn fw-bold" style="background:#C9971C;color:#fff;">
                                <i class="bi bi-check-circle me-1"></i> Adicionar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Homenageados -->
        <div class="col-md-8">
            <h5 class="fw-bold mb-3">
                Homenageados (<?= count($homenageados) ?>)
            </h5>
            <?php if (empty($homenageados)): ?>
                <div class="alert alert-light border text-center py-5">
                    <i class="bi bi-stars fs-2 d-block mb-2 text-warning"></i>
                    <p class="mb-0">Nenhum homenageado adicionado ainda.<br>
                    <small class="text-muted">Eles aparecerão no carrossel da página pública da festa.</small></p>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    <?php foreach ($homenageados as $hom): ?>
                    <div class="col">
                        <div class="card h-100 text-center border-0 shadow-sm">
                            <div class="card-body p-3">
                                <?php if (!empty($hom['foto'])): ?>
                                    <img src="<?= base_url('uploads/homenageados/' . $hom['foto']) ?>"
                                         class="rounded-circle mb-2"
                                         style="width:70px;height:70px;object-fit:cover;border:3px solid #C9971C;"
                                         alt="<?= esc($hom['nome']) ?>">
                                <?php else: ?>
                                    <div class="rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center bg-light"
                                         style="width:70px;height:70px;border:3px solid #C9971C;">
                                        <i class="bi bi-person-fill text-secondary fs-3"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="fw-bold small"><?= esc($hom['nome']) ?></div>
                                <?php if (!empty($hom['titulo'])): ?>
                                    <div class="text-muted" style="font-size:.78rem;"><?= esc($hom['titulo']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($hom['frase'])): ?>
                                    <p class="text-muted mt-1 mb-0" style="font-size:.75rem;font-style:italic;">
                                        "<?= esc($hom['frase']) ?>"
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white border-0 p-1">
                                <form action="<?= base_url('festa-panel/' . $festa['id'] . '/homenageados/remover/' . $hom['id']) ?>"
                                      method="post" onsubmit="return confirm('Remover este homenageado?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i> Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('foto-icon').style.display = 'none';
            const img = document.getElementById('foto-preview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?= $this->endSection() ?>
