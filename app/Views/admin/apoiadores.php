<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Gerenciar Apoiadores<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <?= $this->include('partials/admin_menu') ?>
    <div class="card shadow-sm mb-5 border-0">
        <div class="card-header bg-lulina-nav fw-bold">
            <i class="bi bi-person-plus-fill"></i> Novo Apoiador
        </div>
        <div class="card-body bg-light">
            <form action="<?= base_url('admin/apoiadores/salvar') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nome</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: Gleisi Hoffmann" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Função/Cargo</label>
                        <input type="text" name="funcao" class="form-control" placeholder="Ex: Presidenta Nacional" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Destaque</label>
                        <select name="prioridade" class="form-select">
                            <option value="1">1 - VIP (Topo)</option>
                            <option value="2">2 - Alto</option>
                            <option value="3">3 - Médio</option>
                            <option value="4">4 - Normal</option>
                            <option value="5" selected>5 - Base</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-10">
                        <label class="form-label small fw-bold">Frase de Apoio (Opcional)</label>
                        <input type="text" name="frase" class="form-control" placeholder="Ex: A cultura é a alma de um povo livre.">
                        <div class="form-text small">Aparecerá apenas ao passar o mouse sobre a foto.</div>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-danger fw-bold">Adicionar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <h5 class="mb-3 text-muted">Cadastrados Atualmente</h5>
    
    <?php if (empty($apoiadores)): ?>
        <div class="alert alert-light border text-center">
            Nenhum apoiador cadastrado. Adicione o primeiro acima!
        </div>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
            <?php foreach ($apoiadores as $item): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm text-center py-3">
                        <div class="mx-auto mb-3 position-relative" style="width: 100px; height: 100px;">
                            <img src="<?= base_url('uploads/apoiadores/' . $item['foto']) ?>" 
                                 class="rounded-circle border border-3 border-danger object-fit-cover w-100 h-100" 
                                 alt="<?= esc($item['nome']) ?>">
                        </div>
                        <h6 class="fw-bold mb-0 text-danger"><?= esc($item['nome']) ?></h6>
                        <small class="text-muted d-block mb-3"><?= esc($item['funcao']) ?></small>
                        
                        <!-- BUG 2 FIX: era <a href> GET, agora é form POST -->
                        <form action="<?= base_url('admin/apoiadores/delete/' . $item['id']) ?>" method="post" onsubmit="return confirm('Remover este apoiador?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-trash"></i> Remover
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>