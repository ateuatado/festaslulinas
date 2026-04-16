<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Meu Carrinho<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .cart-item-img {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
    }
    .cart-placeholder {
        width: 56px;
        height: 56px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #bbb;
        font-size: 1.4rem;
    }
    .cart-empty-icon {
        font-size: 5rem;
        color: #dee2e6;
    }
    .total-box {
        background: linear-gradient(135deg, #c62828 0%, #b71c1c 100%);
        border-radius: 12px;
        color: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">

    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="fw-bold text-danger mb-0"><i class="bi bi-cart3"></i> Seu Carrinho</h2>
        <?php $qtdTipos = count($itens); ?>
        <?php if ($qtdTipos > 0): ?>
            <span class="badge bg-danger fs-6"><?= $qtdTipos ?> tipo(s)</span>
        <?php endif; ?>
        <a href="<?= base_url('loja') ?>" class="btn btn-outline-secondary btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Continuar Comprando
        </a>
    </div>

    <?php if (empty($itens)): ?>
        <!-- Carrinho vazio -->
        <div class="text-center py-5">
            <div class="cart-empty-icon mb-3"><i class="bi bi-cart-x"></i></div>
            <h4 class="text-muted fw-bold">Seu carrinho está vazio</h4>
            <p class="text-muted">Acesse a loja e adicione produtos ao carrinho.</p>
            <a href="<?= base_url('loja') ?>" class="btn btn-danger btn-lg px-5 mt-2 fw-bold">
                <i class="bi bi-shop"></i> Ir à Banca Lulina
            </a>
        </div>

    <?php else: ?>
        <div class="row g-4">

            <!-- Tabela de itens -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Produto</th>
                                        <th class="text-center">Qtd</th>
                                        <th class="text-end">Preço Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-end pe-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($itens as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if (!empty($item['imagem'])): ?>
                                                        <img src="<?= base_url('uploads/produtos/' . $item['imagem']) ?>"
                                                             class="cart-item-img" alt="<?= esc($item['nome']) ?>">
                                                    <?php else: ?>
                                                        <div class="cart-placeholder"><i class="bi bi-box-seam"></i></div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?= esc($item['nome']) ?></div>
                                                        <small class="text-muted"><?= esc($item['tipo']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary fs-6 fw-normal px-3">
                                                    <?= $item['quantidade'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end text-muted">
                                                R$ <?= number_format($item['preco'], 2, ',', '.') ?>
                                            </td>
                                            <td class="text-end fw-bold text-danger">
                                                R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <form action="<?= base_url('carrinho/remover/' . $item['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Remover" onclick="return confirm('Remover este item?')">
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
                    <div class="card-footer bg-light d-flex justify-content-end gap-2">
                        <form action="<?= base_url('carrinho/limpar') ?>" method="post"
                              onsubmit="return confirm('Esvaziar o carrinho?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-trash2"></i> Esvaziar carrinho
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Resumo e finalização -->
            <div class="col-lg-4">
                <div class="total-box p-4 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fs-6 opacity-75">Itens selecionados</span>
                        <span class="fw-bold"><?= count($itens) ?> tipo(s)</span>
                    </div>
                    <hr class="border-white opacity-25">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 opacity-90">Total estimado</span>
                        <span class="fs-3 fw-bold">R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>
                </div>

                <!-- Formulário de finalização -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?= base_url('carrinho/finalizar') ?>" method="post">
                            <?= csrf_field() ?>

                            <?php if (auth()->loggedIn()): ?>

                                <?php if (!empty($minhasFestas)): ?>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">
                                            <i class="bi bi-flag-fill text-danger"></i>
                                            Vincular a uma Festa (opcional)
                                        </label>
                                        <select name="festa_id" class="form-select">
                                            <option value="">— Sem vínculo com festa —</option>
                                            <?php foreach ($minhasFestas as $f): ?>
                                                <option value="<?= $f['id'] ?>">
                                                    <?= esc($f['nome_festa']) ?> (<?= date('d/m/Y', strtotime($f['data_hora'])) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text small">
                                            Vincule o pedido a sua festa para facilitar a entrega.
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger btn-lg fw-bold py-3">
                                        <i class="bi bi-cart-check-fill"></i> Finalizar Pedido
                                    </button>
                                </div>
                                <p class="text-center text-muted small mt-2 mb-0">
                                    O pedido será analisado pela administração.
                                </p>

                            <?php else: ?>
                                <div class="alert alert-warning py-2 small mb-3">
                                    <i class="bi bi-lock-fill"></i>
                                    Faça login para finalizar o pedido.
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('login') ?>" class="btn btn-danger fw-bold py-3">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrar e Finalizar
                                    </a>
                                    <a href="<?= base_url('register') ?>" class="btn btn-outline-danger">
                                        <i class="bi bi-person-plus"></i> Criar Conta
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>
