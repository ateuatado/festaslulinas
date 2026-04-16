<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Solicitar Materiais<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                
                <?php if (!empty($festa)): ?>
                    <h2 class="fw-bold text-danger mb-1">Solicitar Material</h2>
                    <div class="alert alert-warning py-2 mb-2">
                        <i class="bi bi-flag-fill"></i> Pedido vinculado à festa: <strong><?= esc($festa['nome_festa']) ?></strong>
                    </div>
                    
                    <p class="mb-0 text-muted">
                        <i class="bi bi-geo-alt-fill"></i> <?= esc($festa['cidade']) ?>/<?= esc($festa['uf']) ?> &bull; 
                        <i class="bi bi-calendar-event"></i> <?= date('d/m/Y H:i', strtotime($festa['data_hora'])) ?>
                    </p>

                <?php else: ?>
                    <h2 class="fw-bold text-danger mb-1">Banca Lulina</h2>
                    <div class="alert alert-info py-2 mb-2">
                        <i class="bi bi-shop"></i> Você está na <strong>Loja Oficial</strong>. Escolha seus produtos.
                    </div>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-truck"></i> Entregamos para todo o Brasil.
                    </p>
                <?php endif; ?>

            </div>
            
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    &larr; Voltar ao Painel
                </a>
            </div>
        </div>

        <?php 
            // Define para onde o formulário vai enviar os dados
            $formAction = !empty($festa) 
                ? base_url('loja/salvar/' . $festa['id'])  // Modo Pedido (vinculado à festa)
                : base_url('carrinho/adicionar');          // Modo Loja Geral → vai para o carrinho
        ?>
        
        <form action="<?= $formAction ?>" method="post">
            <?= csrf_field() ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($produtos as $produto): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="ratio ratio-16x9 bg-secondary text-white d-flex align-items-center justify-content-center fw-bold fs-5">
                            <?php if($produto['imagem'] && file_exists('uploads/produtos/' . $produto['imagem'])): ?>
                                <img src="<?= base_url('uploads/produtos/' . $produto['imagem']) ?>" class="card-img-top object-fit-cover" alt="<?= esc($produto['nome']) ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center w-100 h-100 bg-secondary bg-opacity-25 text-muted">
                                    <span class="fs-1">📷</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2"><?= esc($produto['tipo']) ?></span>
                            <h5 class="card-title fw-bold"><?= esc($produto['nome']) ?></h5>
                            <p class="card-text text-muted small"><?= esc($produto['descricao']) ?></p>
                        </div>
                        
                        <div class="card-footer bg-white border-top-0 pb-3">
                            <label class="form-label fw-bold small text-danger">Quantidade:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-box-seam"></i></span>
                                <input type="number" 
                                       name="quantidades[<?= $produto['id'] ?>]" 
                                       class="form-control" 
                                       min="0" 
                                       value="0"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-grid gap-2 col-md-6 mx-auto mt-5 mb-5">
            <?php if (!empty($festa)): ?>
                <button type="submit" class="btn btn-danger btn-lg shadow fw-bold p-3">
                    <i class="bi bi-cart-check"></i> Confirmar Solicitação
                </button>
                <p class="text-center text-muted small">Ao confirmar, o pedido será enviado para aprovação da administração.</p>
            <?php else: ?>
                <button type="submit" class="btn btn-danger btn-lg shadow fw-bold p-3">
                    <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                </button>
                <p class="text-center text-muted small">
                    Os produtos serão adicionados ao seu <a href="<?= base_url('carrinho') ?>">carrinho</a>. Finalize o pedido quando quiser.
                </p>
            <?php endif; ?>
        </div>

    </form>
</div>
<?= $this->endSection() ?>