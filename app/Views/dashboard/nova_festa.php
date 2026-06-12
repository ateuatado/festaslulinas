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

                    <?php
                        $isAdmin   = auth()->user()->inGroup('admin');
                        $dataMin   = '2026-07-13';
                        $dataMax   = '2026-08-13';
                    ?>

                    <form action="<?= base_url('dashboard/salvar') ?>" method="post" id="formNovaFesta">
                        <?= csrf_field() ?>

                        <div class="row g-3">

                            <!-- Nome do Coletivo -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nome do Coletivo</label>
                                <input type="text"
                                       name="nome_festa"
                                       id="nome_festa"
                                       class="form-control"
                                       placeholder="Coletivo pela democracia PE"
                                       value="<?= old('nome_festa') ?>"
                                       required>
                            </div>

                            <!-- Data -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Data da Festa</label>
                                <input type="date"
                                       name="data_evento"
                                       id="data_evento"
                                       class="form-control"
                                       value="<?= old('data_evento') ?>"
                                       <?php if (!$isAdmin): ?>
                                           min="<?= $dataMin ?>"
                                           max="<?= $dataMax ?>"
                                       <?php endif; ?>
                                       required>
                                <?php if (!$isAdmin): ?>
                                    <div class="form-text text-muted">
                                        <i class="bi bi-calendar-check text-danger"></i>
                                        Período oficial: 13 de julho a 13 de agosto de 2026.
                                        Festas fora desta data devem ser encaminhadas ao administrador.
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Hora -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Horário</label>
                                <input type="time"
                                       name="hora_evento"
                                       id="hora_evento"
                                       class="form-control"
                                       value="<?= old('hora_evento') ?>"
                                       required>
                            </div>

                            <!-- Festa do Coletivo -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Festa do Coletivo</label>
                                <input type="text"
                                       name="organizacao"
                                       id="organizacao"
                                       class="form-control"
                                       placeholder="Nome do grupo ou coletivo"
                                       value="<?= old('organizacao') ?>"
                                       required>
                                <?php if (!$isAdmin): ?>
                                    <div class="form-text text-warning-emphasis">
                                        <i class="bi bi-info-circle"></i>
                                        Festas fora do período oficial (13/07 a 13/08) devem ser
                                        encaminhadas ao <strong>administrador</strong> para aprovação.
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- CEP + Endereço via ViaCEP -->
                            <div class="col-12">
                                <hr class="my-1">
                                <p class="fw-semibold mb-2 text-danger">
                                    <i class="bi bi-geo-alt-fill"></i> Endereço do Evento
                                </p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">CEP</label>
                                <div class="input-group">
                                    <input type="text"
                                           name="cep"
                                           id="cep"
                                           class="form-control"
                                           placeholder="00000-000"
                                           maxlength="9"
                                           value="<?= old('cep') ?>"
                                           required>
                                    <span class="input-group-text" id="cep-spinner" style="display:none;">
                                        <span class="spinner-border spinner-border-sm text-danger"></span>
                                    </span>
                                </div>
                                <div id="cep-erro" class="form-text text-danger" style="display:none;">
                                    CEP não encontrado. Verifique e tente novamente.
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Logradouro</label>
                                <input type="text"
                                       name="logradouro"
                                       id="logradouro"
                                       class="form-control bg-light"
                                       placeholder="Preenchido automaticamente"
                                       value="<?= old('logradouro') ?>"
                                       readonly>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Bairro</label>
                                <input type="text"
                                       name="bairro"
                                       id="bairro"
                                       class="form-control bg-light"
                                       placeholder="Preenchido automaticamente"
                                       value="<?= old('bairro') ?>"
                                       readonly>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Cidade</label>
                                <input type="text"
                                       name="cidade"
                                       id="cidade"
                                       class="form-control bg-light"
                                       placeholder="Preenchida automaticamente"
                                       value="<?= old('cidade') ?>"
                                       readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">UF</label>
                                <input type="text"
                                       name="uf"
                                       id="uf"
                                       class="form-control bg-light text-center fw-bold"
                                       placeholder="UF"
                                       maxlength="2"
                                       value="<?= old('uf') ?>"
                                       readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Número</label>
                                <input type="text"
                                       name="numero"
                                       id="numero"
                                       class="form-control"
                                       placeholder="Ex: 123"
                                       value="<?= old('numero') ?>">
                            </div>

                            <div class="col-md-9">
                                <label class="form-label fw-semibold">Complemento <span class="text-muted small">(Opcional)</span></label>
                                <input type="text"
                                       name="complemento"
                                       id="complemento"
                                       class="form-control"
                                       placeholder="Ex: Apto 12, Bloco B, Sala 3"
                                       value="<?= old('complemento') ?>">
                            </div>

                            <!-- Local do Evento -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Local do Evento</label>
                                <input type="text"
                                       name="local_evento"
                                       id="local_evento"
                                       class="form-control"
                                       placeholder="Nome da praça, clube, associação ou espaço cultural"
                                       value="<?= old('local_evento') ?>"
                                       required>
                            </div>

                            <!-- Tamanho da Festa -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tamanho da Festa</label>
                                <select name="tamanho_festa" id="tamanho_festa" class="form-select" required>
                                    <option value="" disabled <?= old('tamanho_festa') ? '' : 'selected' ?>>Selecione o público estimado...</option>
                                    <option value="Até 50 pessoas"              <?= old('tamanho_festa') === 'Até 50 pessoas'              ? 'selected' : '' ?>>Até 50 pessoas</option>
                                    <option value="De 51 a 150 pessoas"         <?= old('tamanho_festa') === 'De 51 a 150 pessoas'         ? 'selected' : '' ?>>De 51 a 150 pessoas</option>
                                    <option value="De 151 a 350 pessoas"        <?= old('tamanho_festa') === 'De 151 a 350 pessoas'        ? 'selected' : '' ?>>De 151 a 350 pessoas</option>
                                    <option value="De 351 a 800 pessoas"        <?= old('tamanho_festa') === 'De 351 a 800 pessoas'        ? 'selected' : '' ?>>De 351 a 800 pessoas</option>
                                    <option value="De 801 a 1500 pessoas"       <?= old('tamanho_festa') === 'De 801 a 1500 pessoas'       ? 'selected' : '' ?>>De 801 a 1500 pessoas</option>
                                    <option value="De 1501 a 3000 pessoas"      <?= old('tamanho_festa') === 'De 1501 a 3000 pessoas'      ? 'selected' : '' ?>>De 1501 a 3000 pessoas</option>
                                    <option value="Sim! Eu vou competir com o carnaval" <?= old('tamanho_festa') === 'Sim! Eu vou competir com o carnaval' ? 'selected' : '' ?>>🎉 Sim! Eu vou competir com o carnaval</option>
                                </select>
                            </div>

                            <!-- Informações Adicionais -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Informações Adicionais <span class="text-muted small">(Opcional)</span></label>
                                <textarea name="descricao" id="descricao" class="form-control" rows="3"><?= old('descricao') ?></textarea>
                            </div>

                        </div><!-- /row -->

                        <!-- Bloco de vídeos -->
                        <div class="row g-3">
                            <!-- ── Links de Vídeo ──────────────────────────────────── -->
                            <div class="col-12">
                                <div class="card border-0 bg-light p-3 rounded-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-camera-video text-danger fs-5"></i>
                                        <h6 class="fw-bold mb-0">Vídeos da Festa <span class="text-muted fw-normal">(Opcional)</span></h6>
                                    </div>

                                    <!-- Tutorial em accordion -->
                                    <div class="accordion accordion-flush mb-3" id="acc-tutorial-video">
                                        <div class="accordion-item border rounded-2">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2 px-3 small" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#tutorial-video">
                                                    <i class="bi bi-question-circle me-2"></i>
                                                    Como adicionar um vídeo do YouTube ou Vimeo?
                                                </button>
                                            </h2>
                                            <div id="tutorial-video" class="accordion-collapse collapse">
                                                <div class="accordion-body small py-2">
                                                    <p class="fw-semibold mb-2">YouTube — Opção 1 (link curto):</p>
                                                    <ol class="mb-3">
                                                        <li>Abra o vídeo no YouTube</li>
                                                        <li>Clique em <strong>Compartilhar</strong></li>
                                                        <li>Copie o link (<code>https://youtu.be/XXXXX</code>)</li>
                                                        <li>Cole no campo abaixo</li>
                                                    </ol>
                                                    <p class="fw-semibold mb-2">YouTube — Opção 2 (código incorporado):</p>
                                                    <ol class="mb-3">
                                                        <li>Abra o vídeo no YouTube</li>
                                                        <li>Clique em <strong>Compartilhar</strong> → <strong>Incorporar</strong> (<code>&lt;&gt;</code>)</li>
                                                        <li>Clique em <strong>Copiar</strong> (copia o código <code>&lt;iframe&gt;</code> completo)</li>
                                                        <li>Cole no campo abaixo — o sistema extrai o link automaticamente</li>
                                                    </ol>
                                                    <p class="fw-semibold mb-2">Vimeo:</p>
                                                    <ol class="mb-0">
                                                        <li>Abra o vídeo no Vimeo</li>
                                                        <li>Copie o endereço da página (<code>https://vimeo.com/123456</code>)</li>
                                                        <li>Cole no campo abaixo</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small">Vídeo Principal</label>
                                            <textarea name="video_principal" class="form-control font-monospace" rows="3"
                                                      placeholder="Cole aqui o link curto (https://youtu.be/...) OU o código <iframe> completo do YouTube/Vimeo"
                                                      style="font-size:.82rem;"><?= old('video_principal') ?></textarea>
                                            <div class="form-text">Aparece em destaque no topo da sua página pública.</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small">Vídeo Secundário <span class="text-muted">(opcional)</span></label>
                                            <input type="url" name="video_secundario" class="form-control"
                                                   placeholder="Ex: https://youtu.be/XXXXX ou https://vimeo.com/123456"
                                                   value="<?= old('video_secundario') ?>">
                                            <div class="form-text">Aparece após a galeria de fotos.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /videos -->

                        <!-- Campo oculto que junta data + hora antes de enviar -->
                        <input type="hidden" name="data_hora" id="data_hora">

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger px-4 fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Salvar Festa
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ── Combina data + hora no campo oculto data_hora antes de enviar ──
document.getElementById('formNovaFesta').addEventListener('submit', function (e) {
    const data = document.getElementById('data_evento').value;
    const hora = document.getElementById('hora_evento').value;
    if (data && hora) {
        document.getElementById('data_hora').value = data + ' ' + hora + ':00';
    }
});

// ── Máscara simples para o CEP ──
document.getElementById('cep').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
    this.value = v;

    // Dispara busca quando CEP tiver 8 dígitos
    if (v.replace(/\D/g, '').length === 8) {
        buscarCep(v.replace(/\D/g, ''));
    }
});

// ── Busca endereço na API ViaCEP ──
async function buscarCep(cep) {
    const spinner = document.getElementById('cep-spinner');
    const erro    = document.getElementById('cep-erro');

    spinner.style.display = 'inline-flex';
    erro.style.display    = 'none';

    try {
        const res  = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await res.json();

        if (data.erro) {
            erro.style.display = 'block';
            limparEndereco();
        } else {
            document.getElementById('logradouro').value = data.logradouro  || '';
            document.getElementById('bairro').value     = data.bairro      || '';
            document.getElementById('cidade').value     = data.localidade  || '';
            document.getElementById('uf').value         = data.uf          || '';
            // Foca no campo número para o usuário completar
            document.getElementById('numero').focus();
        }
    } catch (err) {
        erro.style.display = 'block';
        limparEndereco();
    } finally {
        spinner.style.display = 'none';
    }
}

function limparEndereco() {
    ['logradouro', 'bairro', 'cidade', 'uf'].forEach(id => {
        document.getElementById(id).value = '';
    });
}
</script>
<?= $this->endSection() ?>