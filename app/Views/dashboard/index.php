<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Painel do Responsável<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <?php
        // Banner para completar o perfil
        use App\Models\UserProfileModel;
        $perfilModel   = new UserProfileModel();
        $perfilExiste  = $perfilModel->findByUserId(auth()->id());
        $perfilCompleto = $perfilExiste && !empty($perfilExiste['nome_completo']);
    ?>

    <?php if (!$perfilCompleto): ?>
    <div class="alert border-0 mb-4 d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#1565C0,#0d47a1);color:#fff;border-radius:12px;">
        <div style="font-size:2.5rem;">🎉</div>
        <div class="flex-grow-1">
            <strong>Complete seu Perfil de Festeiro!</strong><br>
            <span style="opacity:.9;font-size:.9rem;">
                Adicione sua foto e dados — você aparecerá no carrossel de apoiadores da página inicial.
            </span>
        </div>
        <a href="<?= base_url('perfil') ?>" class="btn btn-warning fw-bold text-dark flex-shrink-0">
            <i class="bi bi-person-fill me-1"></i> Completar Perfil
        </a>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-danger fw-bold">Minhas Festas</h2>
        <a href="<?= base_url('dashboard/nova') ?>" class="btn btn-danger">
            <i class="bi bi-plus-circle"></i> + Nova Festa
        </a>
    </div>

    <?php if (empty($festas)): ?>
        <div class="alert alert-light border text-center py-5">
            <h4>Nenhuma festa cadastrada ainda.</h4>
            <p class="text-muted">Comece cadastrando sua primeira Festa Lulina para solicitar materiais.</p>
            <a href="<?= base_url('dashboard/nova') ?>" class="btn btn-outline-danger mt-2">Cadastrar Agora</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome do Coletivo</th>
                            <th>Data/Hora</th>
                            <th>Cidade/UF</th>
                            <th>Local</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($festas as $festa): ?>
                            <tr>
                                <td class="fw-bold"><?= esc($festa['nome_festa']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($festa['data_hora'])) ?></td>
                                <td><?= esc($festa['cidade']) ?> - <?= esc($festa['uf']) ?></td>
                                <td><?= esc($festa['local_evento']) ?></td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-grid-fill"></i> Gerenciar
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('festa-panel/' . $festa['id'] . '/blog') ?>">
                                                    <i class="bi bi-pencil-square me-2 text-danger"></i>Blog da Festa
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('festa-panel/' . $festa['id'] . '/links') ?>">
                                                    <i class="bi bi-link-45deg me-2 text-primary"></i>Links
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('festa-panel/' . $festa['id'] . '/homenageados') ?>">
                                                    <i class="bi bi-stars me-2 text-warning"></i>Homenageados
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('galeria/' . $festa['id']) ?>">
                                                    <i class="bi bi-camera-fill me-2"></i>Fotos
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('dashboard/editar/' . $festa['id']) ?>">
                                                    <i class="bi bi-gear me-2"></i>Editar Dados
                                                </a>
                                            </li>
                                            <?php if (!empty($festa['slug'])): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('festa/' . $festa['slug']) ?>" target="_blank">
                                                    <i class="bi bi-box-arrow-up-right me-2 text-success"></i>Ver Página Pública
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                   href="<?= base_url('dashboard/excluir/' . $festa['id']) ?>"
                                                   onclick="return confirm('Tem certeza que deseja cancelar esta festa?')">
                                                    <i class="bi bi-trash me-2"></i>Cancelar Festa
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>