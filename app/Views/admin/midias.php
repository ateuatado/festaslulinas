<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Moderação de Mídia<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <?= $this->include('partials/admin_menu') ?>
    <h2 class="text-danger fw-bold mb-4">Moderação de Fotos/Vídeos</h2>

    <!-- ══ PENDENTES ══════════════════════════════════════════════════ -->
    <h5 class="fw-bold border-bottom pb-2 mb-3 text-warning">
        <i class="bi bi-hourglass-split me-2"></i>
        Aguardando Aprovação
        <?php if (!empty($pendentes)): ?>
            <span class="badge bg-warning text-dark ms-2"><?= count($pendentes) ?></span>
        <?php endif; ?>
    </h5>

    <?php if (empty($pendentes)): ?>
        <div class="alert alert-success text-center py-4 mb-5">
            <i class="bi bi-check-circle fs-1 d-block mb-2"></i>
            <strong>Tudo limpo!</strong> Não há mídias pendentes no momento.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            <?php foreach ($pendentes as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow border-warning">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <small class="fw-bold text-truncate"><?= esc($item['nome_festa']) ?></small>
                            <span class="badge bg-dark text-white"><?= esc($item['cidade']) ?>-<?= esc($item['uf']) ?></span>
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
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="<?= base_url('admin/midia/' . $item['id'] . '/rejeitado') ?>"
                                   class="btn btn-outline-secondary flex-fill"
                                   onclick="return confirm('Rejeitar esta mídia?')">
                                    <i class="bi bi-x-lg"></i> Rejeitar
                                </a>
                                <a href="<?= base_url('admin/midia/' . $item['id'] . '/aprovado') ?>"
                                   class="btn btn-success flex-fill">
                                    <i class="bi bi-check-lg"></i> Aprovar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ══ APROVADAS ══════════════════════════════════════════════════ -->
    <h5 class="fw-bold border-bottom pb-2 mb-3 text-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        Já Aprovadas
        <?php if (!empty($aprovadas)): ?>
            <span class="badge bg-success ms-2"><?= count($aprovadas) ?></span>
        <?php endif; ?>
    </h5>

    <?php if (empty($aprovadas)): ?>
        <p class="text-muted">Nenhuma mídia aprovada ainda.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            <?php foreach ($aprovadas as $item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-1">
                            <small class="fw-bold text-truncate" style="max-width:160px;"><?= esc($item['nome_festa']) ?></small>
                            <span class="badge bg-white text-success"><?= esc($item['uf']) ?></span>
                        </div>
                        <div class="ratio ratio-4x3 bg-light">
                            <?php if ($item['tipo'] == 'video'): ?>
                                <video src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" controls class="object-fit-contain w-100 h-100"></video>
                            <?php else: ?>
                                <a href="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" target="_blank">
                                    <img src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" class="object-fit-contain w-100 h-100" alt="Mídia aprovada">
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center p-2 bg-light">
                            <a href="<?= base_url('admin/midia/' . $item['id'] . '/pendente') ?>"
                               class="btn btn-sm btn-outline-warning w-100"
                               onclick="return confirm('Retirar aprovação? A mídia voltará para pendente.')">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Retirar Aprovação
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>