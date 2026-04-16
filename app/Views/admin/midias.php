<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Moderação de Mídia<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('partials/admin_menu') ?>
<div class="container py-4">
    <h2 class="text-danger fw-bold mb-4">Moderação de Fotos/Vídeos</h2>

    <?php if (empty($pendentes)): ?>
        <div class="alert alert-success text-center py-5 shadow-sm">
            <i class="bi bi-check-circle fs-1"></i>
            <h4 class="mt-3">Tudo limpo!</h4>
            <p>Não há mídias pendentes de aprovação no momento.</p>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Voltar ao Dashboard</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($pendentes as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow border-danger">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                            <small class="fw-bold text-truncate"><?= esc($item['nome_festa']) ?></small>
                            <span class="badge bg-white text-danger"><?= esc($item['cidade']) ?>-<?= esc($item['uf']) ?></span>
                        </div>

                        <div class="ratio ratio-4x3 bg-light">
                            <?php if ($item['tipo'] == 'video'): ?>
                                <video src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" controls class="object-fit-contain w-100 h-100"></video>
                            <?php else: ?>
                                <a href="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" target="_blank">
                                    <img src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" class="object-fit-contain w-100 h-100" alt="Mídia">
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="card-body text-center bg-light">
                            <div class="d-grid gap-2 d-flex justify-content-center">
                                <a href="<?= base_url('admin/midia/' . $item['id'] . '/rejeitado') ?>" class="btn btn-outline-secondary w-50" onclick="return confirm('Rejeitar esta mídia?')">
                                    <i class="bi bi-x-lg"></i> Rejeitar
                                </a>
                                <a href="<?= base_url('admin/midia/' . $item['id'] . '/aprovado') ?>" class="btn btn-success w-50">
                                    <i class="bi bi-check-lg"></i> Aprovar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>