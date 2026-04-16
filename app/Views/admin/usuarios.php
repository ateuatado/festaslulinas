<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Gerenciar Usuários<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <?= $this->include('partials/admin_menu') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-danger fw-bold mb-0"><i class="bi bi-people-fill"></i> Usuários do Sistema</h2>
            <p class="text-muted small mb-0">Crie, promova e gerencie os acessos dos organizadores.</p>
        </div>
        <button class="btn btn-danger fw-bold shadow-sm" data-bs-toggle="collapse" data-bs-target="#formNovoUsuario">
            <i class="bi bi-person-plus-fill"></i> Novo Usuário
        </button>
    </div>

    <!-- Formulário de novo usuário (colapsável) -->
    <div class="collapse mb-4" id="formNovoUsuario">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-person-plus-fill"></i> Criar Novo Usuário
            </div>
            <div class="card-body bg-light p-4">
                <form action="<?= base_url('admin/usuarios/criar') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Usuário</label>
                            <input type="text" name="username" class="form-control"
                                   placeholder="ex: joaosilva" required minlength="3" maxlength="30">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="joao@exemplo.com" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Senha</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="mínimo 8 chars" required minlength="8">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Perfil</label>
                            <select name="grupo" class="form-select" required>
                                <option value="user">Organizador</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-danger w-100 fw-bold">Criar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabela de usuários -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Usuário</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Status</th>
                            <th>Cadastro</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <?php
                                $uGroups  = $groupsByUser[$u->id] ?? [];
                                $isAdmin  = in_array('admin', $uGroups, true);
                                $isMe     = $u->id === auth()->id();
                                $inativo  = $u->deletedAt !== null;
                            ?>
                            <tr class="<?= $inativo ? 'table-secondary opacity-75' : '' ?>">

                                <!-- Usuário -->
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-<?= $isAdmin ? 'danger' : 'secondary' ?> text-white d-flex align-items-center justify-content-center fw-bold"
                                             style="width:36px;height:36px;font-size:.85rem;">
                                            <?= mb_strtoupper(mb_substr($u->username ?? '?', 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">
                                                <?= esc($u->username) ?>
                                                <?php if ($isMe): ?><span class="badge bg-warning text-dark ms-1 small">você</span><?php endif; ?>
                                            </div>
                                            <small class="text-muted">ID: <?= $u->id ?></small>
                                        </div>
                                    </div>
                                </td>

                                <!-- E-mail -->
                                <td>
                                    <?php $identity = $u->getEmailIdentity(); ?>
                                    <small class="text-muted"><?= esc($identity?->secret ?? '—') ?></small>
                                </td>

                                <!-- Grupos -->
                                <td>
                                    <?php foreach ($uGroups as $g): ?>
                                        <span class="badge <?= $g === 'admin' ? 'bg-danger' : 'bg-secondary' ?> me-1">
                                            <?= esc($g) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (empty($uGroups)): ?>
                                        <span class="text-muted small">sem grupo</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status -->
                                <td>
                                    <?php if ($inativo): ?>
                                        <span class="badge bg-secondary">Inativo</span>
                                    <?php elseif ($u->active): ?>
                                        <span class="badge bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Data de cadastro -->
                                <td>
                                    <small class="text-muted">
                                        <?= $u->createdAt ? date('d/m/Y', strtotime($u->createdAt)) : '—' ?>
                                    </small>
                                </td>

                                <!-- Ações -->
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">

                                        <?php if (! $isMe): ?>
                                            <!-- Toggle Admin -->
                                            <form action="<?= base_url('admin/usuarios/grupo/' . $u->id . '/admin') ?>" method="post">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                        class="btn btn-sm <?= $isAdmin ? 'btn-danger' : 'btn-outline-danger' ?>"
                                                        title="<?= $isAdmin ? 'Remover Admin' : 'Tornar Admin' ?>"
                                                        onclick="return confirm('<?= $isAdmin ? 'Remover acesso admin?' : 'Promover a admin?' ?>')">
                                                    <i class="bi bi-shield-<?= $isAdmin ? 'fill' : 'plus' ?>"></i>
                                                </button>
                                            </form>

                                            <!-- Ativar / Desativar -->
                                            <form action="<?= base_url('admin/usuarios/desativar/' . $u->id) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                        class="btn btn-sm <?= $inativo ? 'btn-success' : 'btn-outline-secondary' ?>"
                                                        title="<?= $inativo ? 'Reativar' : 'Desativar' ?>"
                                                        onclick="return confirm('<?= $inativo ? 'Reativar este usuário?' : 'Desativar este usuário?' ?>')">
                                                    <i class="bi bi-<?= $inativo ? 'person-check' : 'person-dash' ?>"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-muted small bg-light">
            Total: <?= count($usuarios) ?> usuário(s) cadastrado(s)
        </div>
    </div>

</div>
<?= $this->endSection() ?>
