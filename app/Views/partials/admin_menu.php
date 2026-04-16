<div class="d-flex flex-wrap gap-2 mb-4 border-bottom pb-3">
    
    <a href="<?= base_url('admin/midias') ?>" 
       class="btn <?= (url_is('admin/midias*') || url_is('admin')) ? 'btn-danger fw-bold' : 'btn-outline-secondary' ?>">
        <i class="bi bi-images"></i> Moderação
    </a>

    <a href="<?= base_url('admin/apoiadores') ?>" 
       class="btn <?= url_is('admin/apoiadores*') ? 'btn-danger fw-bold' : 'btn-outline-secondary' ?>">
        <i class="bi bi-people-fill"></i> Apoiadores
    </a>

    <a href="<?= base_url('admin/festas') ?>" 
       class="btn <?= url_is('admin/festas*') ? 'btn-danger fw-bold' : 'btn-outline-secondary' ?>">
        <i class="bi bi-calendar-event"></i> Todas as Festas
    </a>

    <a href="<?= base_url('admin/produtos') ?>" 
       class="btn <?= url_is('admin/produtos*') ? 'btn-danger fw-bold' : 'btn-outline-secondary' ?>">
        <i class="bi bi-box-seam"></i> Gerenciar Loja
    </a>

    <a href="<?= base_url('admin/usuarios') ?>" 
       class="btn <?= url_is('admin/usuarios*') ? 'btn-danger fw-bold' : 'btn-outline-secondary' ?>">
        <i class="bi bi-person-gear"></i> Usuários
    </a>

</div>