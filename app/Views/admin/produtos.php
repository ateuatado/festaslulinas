<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Gerenciar Loja - Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <?= $this->include('partials/admin_menu') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-danger fw-bold mb-0">Itens da Loja</h2>
            <p class="text-muted small mb-0">Gerencie os produtos, preços e estoques.</p>
        </div>
        <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProduto" onclick="limparForm()">
            <i class="bi bi-plus-lg"></i> Novo Item
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Foto</th>
                            <th>Nome do Item</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Descrição</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produtos)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                    Nenhum produto cadastrado ainda.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produtos as $p): ?>
                                <tr>
                                    <td class="ps-4">
                                        <?php if(!empty($p['imagem'])): ?>
                                            <img src="<?= base_url('uploads/produtos/' . $p['imagem']) ?>" 
                                                 class="rounded border shadow-sm" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="fw-bold text-dark"><?= esc($p['nome']) ?></td>
                                    
                                    <td><span class="badge bg-light text-dark border"><?= esc($p['tipo']) ?></span></td>
                                    
                                    <td class="text-success fw-bold">
                                        R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                                    </td>

                                    <td class="small text-muted text-truncate" style="max-width: 200px;">
                                        <?= esc($p['descricao']) ?>
                                    </td>
                                    
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1" 
                                                onclick='editar(<?= json_encode($p) ?>)'
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalProduto"
                                                title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        
                                        <form action="<?= base_url('admin/excluirProduto/' . $p['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover este item?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProduto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <form action="<?= base_url('admin/salvarProduto') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="prodId">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="modalTitle">Item da Loja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nome do Produto</label>
                        <input type="text" name="nome" id="prodNome" class="form-control" placeholder="Ex: Camiseta Oficial" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Preço Unitário (R$)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" name="preco" id="prodPreco" class="form-control" step="0.01" min="0" placeholder="0,00" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Categoria</label>
                        <select name="tipo" id="prodTipo" class="form-select" required>
                            <option value="Material de Festa" class="fw-bold">🎉 Material de Festa (Kit)</option>
                            <option disabled>──────────</option>
                            <option value="Material">Material de Campanha</option>
                            <option value="Vestuário">Vestuário</option>
                            <option value="Impresso">Impresso</option>
                            <option value="Digital">Digital</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Foto do Produto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text small">Deixe vazio para manter a foto atual.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small">Descrição</label>
                        <textarea name="descricao" id="prodDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    function limparForm() {
        document.getElementById('modalTitle').innerText = 'Novo Item';
        document.getElementById('prodId').value = '';
        document.getElementById('prodNome').value = '';
        document.getElementById('prodPreco').value = '';
        document.getElementById('prodDesc').value = '';
        document.getElementById('prodTipo').value = 'Material de Festa';
    }

    function editar(item) {
        document.getElementById('modalTitle').innerText = 'Editar Item';
        
        document.getElementById('prodId').value = item.id;
        document.getElementById('prodNome').value = item.nome;
        document.getElementById('prodPreco').value = item.preco; // Preenche o preço
        document.getElementById('prodTipo').value = item.tipo;
        document.getElementById('prodDesc').value = item.descricao;
    }
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>