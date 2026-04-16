<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Cadastrar Festa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Cadastrar Nova Festa</h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= base_url('dashboard/salvar') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nome da Festa</label>
                                <input type="text" name="nome_festa" class="form-control" placeholder="Ex: Arraiá da Democracia" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Data e Hora</label>
                                <input type="datetime-local" name="data_hora" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Organização / Grupo</label>
                                <input type="text" name="organizacao" class="form-control" placeholder="Ex: Diretório Municipal, Associação..." required>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="cidade" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">UF</label>
                                <select name="uf" class="form-select" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <option value="AC">AC</option><option value="AL">AL</option><option value="AP">AP</option>
                                    <option value="AM">AM</option><option value="BA">BA</option><option value="CE">CE</option>
                                    <option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option>
                                    <option value="MA">MA</option><option value="MT">MT</option><option value="MS">MS</option>
                                    <option value="MG">MG</option><option value="PA">PA</option><option value="PB">PB</option>
                                    <option value="PR">PR</option><option value="PE">PE</option><option value="PI">PI</option>
                                    <option value="RJ">RJ</option><option value="RN">RN</option><option value="RS">RS</option>
                                    <option value="RO">RO</option><option value="RR">RR</option><option value="SC">SC</option>
                                    <option value="SP">SP</option><option value="SE">SE</option><option value="TO">TO</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Local do Evento</label>
                                <input type="text" name="local_evento" class="form-control" placeholder="Nome da praça, clube ou endereço completo" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Condições de Acesso</label>
                                <input type="text" name="condicoes_acesso" class="form-control" placeholder="Ex: Gratuito, 1kg de alimento, Entrada Franca" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Informações Adicionais (Opcional)</label>
                                <textarea name="descricao" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger px-4">Salvar Festa</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>