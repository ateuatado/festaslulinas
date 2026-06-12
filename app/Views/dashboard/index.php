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
                            <th>Nome da Festa</th>
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
                                    <a href="<?= base_url('galeria/' . $festa['id']) ?>" class="btn btn-sm btn-outline-warning text-dark me-1" title="Fotos e Vídeos">
                                        <i class="bi bi-camera-fill"></i>
                                    </a>
                                
                                    <a href="<?= base_url('dashboard/editar/' . $festa['id']) ?>" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                    <a href="<?= base_url('loja/' . $festa['id']) ?>" class="btn btn-sm btn-outline-secondary">Material</a>    
                                    <a href="<?= base_url('dashboard/excluir/' . $festa['id']) ?>" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Tem certeza que deseja cancelar esta festa?');">
                                    <i class="bi bi-trash"></i>
                                    </a>                      
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