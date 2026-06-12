<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Editar Festa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-fill me-2"></i>Editar Festa</h5>
                </div>
                <div class="card-body p-4">

                    <?php
                        $isAdmin   = auth()->user()->inGroup('admin');
                        $dataMin   = '2026-07-13';
                        $dataMax   = '2026-08-13';

                        // Separa data e hora do campo data_hora do banco
                        $dataAtual = !empty($festa['data_hora']) ? date('Y-m-d', strtotime($festa['data_hora'])) : '';
                        $horaAtual = !empty($festa['data_hora']) ? date('H:i',   strtotime($festa['data_hora'])) : '';
                    ?>

                    <!-- Seção: Dados de Mobilização -->
                    <div class="bg-light p-3 rounded border mb-4">
                        <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">
                            <i class="bi bi-people-fill"></i> Dados de Mobilização
                        </h6>
                        <form action="<?= base_url('dashboard/atualizar/' . $festa['id']) ?>" method="post" id="formEditarFesta">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Público Estimado (Meta)</label>
                                <input type="number" name="publico_estimado" class="form-control"
                                       value="<?= esc($festa['publico_estimado']) ?>">
                                <div class="form-text small">Quantas pessoas você espera?</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Público Real (Pós-Festa)</label>
                                <input type="number" name="publico_real" class="form-control"
                                       value="<?= esc($festa['publico_real']) ?>">
                                <div class="form-text small">Quantas pessoas compareceram?</div>
                            </div>
                        </div>
                    </div>

                    <!-- Dados principais da festa -->
                    <div class="row g-3">

                        <!-- Nome do Coletivo -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nome do Coletivo</label>
                            <input type="text" name="nome_festa" class="form-control"
                                   placeholder="Coletivo pela democracia PE"
                                   value="<?= esc(old('nome_festa', $festa['nome_festa'])) ?>" required>
                        </div>

                        <!-- Data -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data da Festa</label>
                            <input type="date" name="data_evento" id="data_evento" class="form-control"
                                   value="<?= esc(old('data_evento', $dataAtual)) ?>"
                                   <?php if (!$isAdmin): ?>
                                       min="<?= $dataMin ?>"
                                       max="<?= $dataMax ?>"
                                   <?php endif; ?>
                                   required>
                            <?php if (!$isAdmin): ?>
                                <div class="form-text text-muted">
                                    <i class="bi bi-calendar-check text-danger"></i>
                                    Período oficial: 13 de julho a 13 de agosto de 2026.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Hora -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Horário</label>
                            <input type="time" name="hora_evento" id="hora_evento" class="form-control"
                                   value="<?= esc(old('hora_evento', $horaAtual)) ?>" required>
                        </div>

                        <!-- Festa do Coletivo -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Festa do Coletivo</label>
                            <input type="text" name="organizacao" class="form-control"
                                   placeholder="Nome do grupo ou coletivo"
                                   value="<?= esc(old('organizacao', $festa['organizacao'])) ?>" required>
                            <?php if (!$isAdmin): ?>
                                <div class="form-text text-warning-emphasis">
                                    <i class="bi bi-info-circle"></i>
                                    Festas fora do período oficial (13/07 a 13/08) devem ser
                                    encaminhadas ao <strong>administrador</strong> para aprovação.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Separador de Endereço -->
                        <div class="col-12">
                            <hr class="my-1">
                            <p class="fw-semibold mb-2 text-danger">
                                <i class="bi bi-geo-alt-fill"></i> Endereço do Evento
                            </p>
                        </div>

                        <!-- CEP -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">CEP</label>
                            <div class="input-group">
                                <input type="text" name="cep" id="cep" class="form-control"
                                       placeholder="00000-000" maxlength="9"
                                       value="<?= esc(old('cep', $festa['cep'] ?? '')) ?>" required>
                                <span class="input-group-text" id="cep-spinner" style="display:none;">
                                    <span class="spinner-border spinner-border-sm text-danger"></span>
                                </span>
                            </div>
                            <div id="cep-erro" class="form-text text-danger" style="display:none;">
                                CEP não encontrado.
                            </div>
                        </div>

                        <!-- Logradouro -->
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro"
                                   class="form-control bg-light" readonly
                                   placeholder="Preenchido automaticamente"
                                   value="<?= esc(old('logradouro', $festa['logradouro'] ?? '')) ?>">
                        </div>

                        <!-- Bairro -->
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Bairro</label>
                            <input type="text" name="bairro" id="bairro"
                                   class="form-control bg-light" readonly
                                   placeholder="Preenchido automaticamente"
                                   value="<?= esc(old('bairro', $festa['bairro'] ?? '')) ?>">
                        </div>

                        <!-- Cidade -->
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Cidade</label>
                            <input type="text" name="cidade" id="cidade"
                                   class="form-control bg-light" readonly
                                   placeholder="Preenchida automaticamente"
                                   value="<?= esc(old('cidade', $festa['cidade'] ?? '')) ?>">
                        </div>

                        <!-- UF -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">UF</label>
                            <input type="text" name="uf" id="uf"
                                   class="form-control bg-light text-center fw-bold" readonly
                                   maxlength="2" placeholder="UF"
                                   value="<?= esc(old('uf', $festa['uf'] ?? '')) ?>">
                        </div>

                        <!-- Número -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Número</label>
                            <input type="text" name="numero" class="form-control"
                                   placeholder="Ex: 123"
                                   value="<?= esc(old('numero', $festa['numero'] ?? '')) ?>">
                        </div>

                        <!-- Complemento -->
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">Complemento <span class="text-muted small">(Opcional)</span></label>
                            <input type="text" name="complemento" class="form-control"
                                   placeholder="Ex: Apto 12, Bloco B, Sala 3"
                                   value="<?= esc(old('complemento', $festa['complemento'] ?? '')) ?>">
                        </div>

                        <!-- Local do Evento -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Local do Evento</label>
                            <input type="text" name="local_evento" class="form-control"
                                   placeholder="Nome da praça, clube, associação ou espaço cultural"
                                   value="<?= esc(old('local_evento', $festa['local_evento'])) ?>" required>
                        </div>

                        <!-- Tamanho da Festa -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tamanho da Festa</label>
                            <?php
                                $tamanhoAtual = old('tamanho_festa', $festa['tamanho_festa'] ?? '');
                                $opcoes = [
                                    'Até 50 pessoas',
                                    'De 51 a 150 pessoas',
                                    'De 151 a 350 pessoas',
                                    'De 351 a 800 pessoas',
                                    'De 801 a 1500 pessoas',
                                    'De 1501 a 3000 pessoas',
                                    'Sim! Eu vou competir com o carnaval',
                                ];
                            ?>
                            <select name="tamanho_festa" class="form-select" required>
                                <option value="" disabled <?= $tamanhoAtual === '' ? 'selected' : '' ?>>Selecione o público estimado...</option>
                                <?php foreach ($opcoes as $opcao): ?>
                                    <option value="<?= esc($opcao) ?>"
                                            <?= $tamanhoAtual === $opcao ? 'selected' : '' ?>>
                                        <?= $opcao === 'Sim! Eu vou competir com o carnaval' ? '🎉 ' : '' ?><?= esc($opcao) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Informações Adicionais <span class="text-muted small">(Opcional)</span></label>
                            <textarea name="descricao" class="form-control" rows="3"><?= esc(old('descricao', $festa['descricao'])) ?></textarea>
                        </div>

                    </div><!-- /row -->

                    <!-- Campo oculto que junta data + hora -->
                    <input type="hidden" name="data_hora" id="data_hora">


                    <!-- Bloco de vídeos -->
                    <div class="row g-3 mb-4">
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
                                                <p class="fw-semibold mb-2">YouTube:</p>
                                                <ol class="mb-3">
                                                    <li>Abra o vídeo no YouTube</li>
                                                    <li>Clique em <strong>Compartilhar</strong> (ícone de seta)</li>
                                                    <li>Copie o link curto (<code>https://youtu.be/XXXXX</code>)</li>
                                                    <li>Cole no campo abaixo</li>
                                                </ol>
                                                    <div class="alert alert-warning py-2 px-3 small mt-2 mb-0">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                        <strong>Importante:</strong> No YouTube Studio, verifique se o vídeo tem
                                                        <strong>"Permitir incorporação"</strong> ativado
                                                        (Detalhes do vídeo → Mais opções → Distribuição). Sem isso, o vídeo não aparece na página da festa.
                                                    </div>
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
                                                  style="font-size:.82rem;"><?= old('video_principal', $festa['video_principal'] ?? '') ?></textarea>
                                        <div class="form-text">Aparece em destaque no topo da sua página pública.</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small">Vídeo Secundário <span class="text-muted">(opcional)</span></label>
                                        <textarea name="video_secundario" class="form-control font-monospace" rows="3"
                                                  placeholder="Cole aqui o link curto (https://youtu.be/...) OU o código <iframe> completo do YouTube/Vimeo"
                                                  style="font-size:.82rem;"><?= old('video_secundario', $festa['video_secundario'] ?? '') ?></textarea>
                                        <div class="form-text">Aparece após a galeria de fotos.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /videos -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning fw-bold px-4">
                            <i class="bi bi-check-circle me-1"></i> Salvar Alterações
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
// ── Combina data + hora no campo oculto antes de enviar ──
document.getElementById('formEditarFesta').addEventListener('submit', function (e) {
    const data = document.getElementById('data_evento').value;
    const hora = document.getElementById('hora_evento').value;
    if (data && hora) {
        document.getElementById('data_hora').value = data + ' ' + hora + ':00';
    }
});

// ── Máscara de CEP ──
document.getElementById('cep').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
    this.value = v;
    if (v.replace(/\D/g, '').length === 8) buscarCep(v.replace(/\D/g, ''));
});

// ── ViaCEP (só busca se o usuário editar o CEP) ──
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
            ['logradouro','bairro','cidade','uf'].forEach(id => document.getElementById(id).value = '');
        } else {
            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('bairro').value     = data.bairro     || '';
            document.getElementById('cidade').value     = data.localidade || '';
            document.getElementById('uf').value         = data.uf         || '';
            document.getElementById('numero') && document.getElementById('numero').focus();
        }
    } catch (e) {
        erro.style.display = 'block';
    } finally {
        spinner.style.display = 'none';
    }
}

// ── Formata CEP já salvo (ex: 12345678 → 12345-678) ──
(function formatarCepExistente() {
    const el = document.getElementById('cep');
    if (!el) return;
    let v = el.value.replace(/\D/g, '');
    if (v.length === 8) el.value = v.slice(0,5) + '-' + v.slice(5);
})();
</script>
<?= $this->endSection() ?>