<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Galeria da Festa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-danger fw-bold">Fotos e Vídeos</h2>
            <p class="text-muted">Festa: <strong><?= esc($festa['nome_festa']) ?></strong></p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Voltar</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> Enviar Arquivos</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('galeria/upload/' . $festa['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Selecione fotos ou vídeos curtos</label>
                            <input type="file" name="arquivos[]" class="form-control" multiple accept="image/*,video/mp4" required>
                            <div class="form-text">Formatos: JPG, PNG, MP4. Max 10MB.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">Enviar para Aprovação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h5 class="mb-3 border-bottom pb-2">Galeria Atual</h5>
            
            <?php if (empty($midias)): ?>
                <div class="alert alert-light text-center border">
                    Nenhuma foto ou vídeo enviado ainda.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    <?php foreach ($midias as $item): ?>
                        <div class="col">
                            <div class="card h-100 position-relative">
                                <?php 
                                    $badgeClass = match($item['status']) {
                                        'aprovado' => 'bg-success',
                                        'rejeitado' => 'bg-danger',
                                        default => 'bg-warning text-dark'
                                    };
                                ?>
                                <span class="position-absolute top-0 start-0 badge <?= $badgeClass ?> m-2 shadow-sm">
                                    <?= ucfirst($item['status']) ?>
                                </span>

                                <div class="ratio ratio-1x1 bg-light">
                                    <?php if ($item['tipo'] == 'video'): ?>
                                        <video src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" controls class="object-fit-cover w-100 h-100"></video>
                                    <?php else: ?>
                                        <img src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" class="object-fit-cover w-100 h-100" alt="Midia">
                                    <?php endif; ?>
                                </div>

                                <div class="card-footer bg-white p-1 text-center">
                                    <form action="<?= base_url('galeria/delete/' . $item['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover este arquivo?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-link text-danger btn-sm text-decoration-none">
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