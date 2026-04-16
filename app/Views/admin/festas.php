<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Admin - Todas as Festas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <?= $this->include('partials/admin_menu') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-danger fw-bold">Gestão Geral de Festas</h2>
        <span class="badge bg-secondary"><?= count($festas) ?> cadastradas</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Festa</th>
                            <th>Organizador</th>
                            <th>Cidade/UF</th>
                            <th>Data</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($festas as $festa): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-danger"><?= esc($festa['nome_festa']) ?></div>
                                    <small class="text-muted">ID: <?= $festa['id'] ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">User ID: <?= $festa['user_id'] ?></span>
                                </td>
                                <td><?= esc($festa['cidade']) ?> - <?= esc($festa['uf']) ?></td>
                                <td><?= date('d/m/Y', strtotime($festa['data_hora'])) ?></td>
                                <td class="text-end">
                                    <a href="<?= base_url('festa/' . $festa['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Ver Página Pública">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="<?= base_url('admin/excluirFesta/' . $festa['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('ATENÇÃO ADMIN: Isso apagará a festa definitivamente. Continuar?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>