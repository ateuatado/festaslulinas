<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Editar Festa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold">Editar Festa</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= base_url('dashboard/atualizar/' . $festa['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="bg-light p-3 rounded border mb-4">
                            <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">
                                <i class="bi bi-people-fill"></i> Dados de Mobilização
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Público Estimado (Meta)</label>
                                    <input type="number" name="publico_estimado" class="form-control" value="<?= esc($festa['publico_estimado']) ?>">
                                    <div class="form-text small">Quantas pessoas você espera?</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Público Real (Pós-Festa)</label>
                                    <input type="number" name="publico_real" class="form-control" value="<?= esc($festa['publico_real']) ?>">
                                    <div class="form-text small">Quantas pessoas compareceram?</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nome da Festa</label>
                                <input type="text" name="nome_festa" class="form-control" value="<?= esc($festa['nome_festa']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Data e Hora</label>
                                <input type="datetime-local" name="data_hora" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($festa['data_hora'])) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organização / Grupo</label>
                                <input type="text" name="organizacao" class="form-control" value="<?= esc($festa['organizacao']) ?>" required>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="cidade" class="form-control" value="<?= esc($festa['cidade']) ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">UF</label>
                                <select name="uf" class="form-select" required>
                                    <option value="<?= esc($festa['uf']) ?>" selected><?= esc($festa['uf']) ?></option>
                                    <option value="SP">SP</option><option value="RJ">RJ</option><option value="MG">MG</option><option value="BA">BA</option>
                                    </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Local do Evento</label>
                                <input type="text" name="local_evento" class="form-control" value="<?= esc($festa['local_evento']) ?>" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Condições de Acesso</label>
                                <input type="text" name="condicoes_acesso" class="form-control" value="<?= esc($festa['condicoes_acesso']) ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Informações Adicionais</label>
                                <textarea name="descricao" class="form-control" rows="3"><?= esc($festa['descricao']) ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning fw-bold px-4">Salvar Alterações</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>