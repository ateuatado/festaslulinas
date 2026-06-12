<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin — Gestão de Festas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <?= $this->include('partials/admin_menu') ?>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- POSTS PENDENTES                                   -->
    <!-- ══════════════════════════════════════════════════ -->
    <?php if (!empty($postsPend)): ?>
    <div class="card border-0 shadow-sm mb-4 border-start border-4 border-warning">
        <div class="card-header d-flex align-items-center gap-2"
             style="background:#fff8e1;">
            <i class="bi bi-pencil-square text-warning fs-5"></i>
            <strong>Blog — Posts Aguardando Aprovação</strong>
            <span class="badge bg-warning text-dark ms-auto"><?= count($postsPend) ?></span>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($postsPend as $post): ?>
            <div class="list-group-item">
                <div class="d-flex align-items-start gap-3 flex-wrap">
                    <div class="flex-grow-1">
                        <div class="fw-bold">
                            <?= esc($post['nome_festa']) ?>
                            <small class="text-muted fw-normal ms-2">
                                <?= date('d/m/Y H:i', strtotime($post['updated_at'])) ?>
                            </small>
                        </div>
                        <!-- Prévia do texto (sem HTML) -->
                        <p class="text-muted mb-0 small mt-1"
                           style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= esc(strip_tags($post['conteudo'])) ?>
                        </p>
                        <?php if (!empty($post['slug'])): ?>
                        <a href="<?= base_url('festa/' . $post['slug']) ?>" target="_blank"
                           class="small text-primary">
                            <i class="bi bi-box-arrow-up-right"></i> Ver página pública
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0 align-items-center">
                        <a href="<?= base_url('admin/post/' . $post['id'] . '/aprovado') ?>"
                           class="btn btn-sm btn-success fw-bold"
                           onclick="return confirm('Aprovar este texto? Ele ficará visível publicamente.')">
                            <i class="bi bi-check-circle-fill me-1"></i>Aprovar
                        </a>
                        <a href="<?= base_url('admin/post/' . $post['id'] . '/rejeitado') ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Rejeitar este texto?')">
                            <i class="bi bi-x-circle me-1"></i>Rejeitar
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- LINKS PENDENTES                                   -->
    <!-- ══════════════════════════════════════════════════ -->
    <?php if (!empty($linksPend)): ?>
    <div class="card border-0 shadow-sm mb-4 border-start border-4 border-info">
        <div class="card-header d-flex align-items-center gap-2"
             style="background:#e3f2fd;">
            <i class="bi bi-link-45deg text-info fs-5"></i>
            <strong>Links Aguardando Aprovação</strong>
            <span class="badge bg-info text-dark ms-auto"><?= count($linksPend) ?></span>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($linksPend as $link): ?>
            <div class="list-group-item">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= esc($link['titulo']) ?></div>
                        <a href="<?= esc($link['url']) ?>" target="_blank" rel="noopener"
                           class="small text-primary">
                            <?= esc($link['url']) ?>
                        </a>
                        <small class="text-muted d-block">
                            Festa: <?= esc($link['nome_festa']) ?>
                        </small>
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0">
                        <a href="<?= base_url('admin/link/' . $link['id'] . '/aprovado') ?>"
                           class="btn btn-sm btn-success fw-bold"
                           onclick="return confirm('Aprovar este link?')">
                            <i class="bi bi-check-circle-fill me-1"></i>Aprovar
                        </a>
                        <a href="<?= base_url('admin/link/' . $link['id'] . '/rejeitado') ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Rejeitar este link?')">
                            <i class="bi bi-x-circle me-1"></i>Rejeitar
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($postsPend) && empty($linksPend)): ?>
    <div class="alert alert-success border-0 mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>Nenhum post ou link aguardando aprovação. Tudo em dia! ✅</span>
    </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════ -->
    <!-- LISTA GERAL DE FESTAS                             -->
    <!-- ══════════════════════════════════════════════════ -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-danger fw-bold mb-0">Gestão Geral de Festas</h2>
        <span class="badge bg-secondary"><?= count($festas) ?> cadastradas</span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Coletivo / Slug</th>
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
                                <?php if (!empty($festa['slug'])): ?>
                                <code class="small text-muted">/festa/<?= esc($festa['slug']) ?></code>
                                <?php else: ?>
                                <span class="badge bg-light text-danger border small">Sem slug</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">User #<?= $festa['user_id'] ?></span>
                            </td>
                            <td><?= esc($festa['cidade']) ?> - <?= esc($festa['uf']) ?></td>
                            <td><?= date('d/m/Y', strtotime($festa['data_hora'])) ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('festa/' . ($festa['slug'] ?: $festa['id'])) ?>"
                                   target="_blank" class="btn btn-sm btn-outline-primary me-1" title="Ver Página Pública">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= base_url('festa-panel/' . $festa['id'] . '/blog') ?>"
                                   class="btn btn-sm btn-outline-warning text-dark me-1" title="Gerenciar Painel">
                                    <i class="bi bi-grid-fill"></i>
                                </a>
                                <form action="<?= base_url('admin/excluirFesta/' . $festa['id']) ?>"
                                      method="post" style="display:inline;"
                                      onsubmit="return confirm('ATENÇÃO: Isso apagará a festa. Continuar?')">
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