<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Meu Perfil de Festeiro<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Cabeçalho -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:60px;height:60px;border-radius:50%;background:#1565C0;
                            display:flex;align-items:center;justify-content:center;font-size:1.8rem;">
                    🎉
                </div>
                <div>
                    <h2 class="mb-0 fw-bold" style="color:#1565C0;">Meu Perfil de Festeiro</h2>
                    <p class="mb-0 text-muted small">
                        Seus dados aparecem no carrossel de apoiadores da página inicial quando você adicionar uma foto.
                    </p>
                </div>
            </div>

            <form action="<?= base_url('perfil/salvar') ?>" method="post" enctype="multipart/form-data" id="formPerfil">
                <?= csrf_field() ?>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- SEÇÃO 1 — DADOS PESSOAIS                              -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header fw-bold" style="background:#1565C0;color:#fff;">
                        <i class="bi bi-person-fill me-2"></i> Dados Pessoais
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <!-- Foto + Preview -->
                            <div class="col-md-3 text-center">
                                <div id="foto-preview-wrap"
                                     style="width:120px;height:120px;border-radius:50%;
                                            border:4px solid #C9971C;overflow:hidden;
                                            margin:0 auto 10px;background:#f0f0f0;
                                            display:flex;align-items:center;justify-content:center;">
                                    <?php if (!empty($perfil['foto'])): ?>
                                        <img id="foto-preview"
                                             src="<?= base_url('uploads/perfil/' . $perfil['foto']) ?>"
                                             style="width:100%;height:100%;object-fit:cover;"
                                             alt="Minha foto">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle" id="foto-icon"
                                           style="font-size:3rem;color:#aaa;"></i>
                                        <img id="foto-preview" src="" style="display:none;
                                             width:100%;height:100%;object-fit:cover;" alt="Preview">
                                    <?php endif; ?>
                                </div>
                                <label for="foto" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-camera me-1"></i> Minha Foto
                                </label>
                                <input type="file" name="foto" id="foto" accept="image/*"
                                       class="d-none" onchange="previewFoto(this)">
                                <div class="form-text">JPG/PNG até 5MB</div>
                            </div>

                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
                                        <input type="text" name="nome_completo" class="form-control"
                                               placeholder="Seu nome completo"
                                               value="<?= esc($perfil['nome_completo'] ?? '') ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Profissão</label>
                                        <input type="text" name="profissao" class="form-control"
                                               placeholder="Ex: Professora, Vereador, Produtor Cultural..."
                                               value="<?= esc($perfil['profissao'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- SEÇÃO 2 — ENDEREÇO                                    -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header fw-bold" style="background:#1565C0;color:#fff;">
                        <i class="bi bi-geo-alt-fill me-2"></i> Endereço
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">CEP</label>
                                <div class="input-group">
                                    <input type="text" name="cep" id="cep" class="form-control"
                                           placeholder="00000-000" maxlength="9"
                                           value="<?= esc($perfil['cep'] ?? '') ?>">
                                    <span class="input-group-text" id="cep-spinner" style="display:none;">
                                        <span class="spinner-border spinner-border-sm text-primary"></span>
                                    </span>
                                </div>
                                <div id="cep-erro" class="form-text text-danger" style="display:none;">
                                    CEP não encontrado.
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro"
                                       class="form-control bg-light" readonly
                                       placeholder="Preenchido pelo CEP"
                                       value="<?= esc($perfil['logradouro'] ?? '') ?>">
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Bairro</label>
                                <input type="text" name="bairro" id="bairro"
                                       class="form-control bg-light" readonly
                                       placeholder="Preenchido pelo CEP"
                                       value="<?= esc($perfil['bairro'] ?? '') ?>">
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Cidade</label>
                                <input type="text" name="cidade" id="cidade"
                                       class="form-control bg-light" readonly
                                       placeholder="Preenchida pelo CEP"
                                       value="<?= esc($perfil['cidade'] ?? '') ?>">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">UF</label>
                                <input type="text" name="uf" id="uf"
                                       class="form-control bg-light text-center fw-bold" readonly
                                       maxlength="2" placeholder="UF"
                                       value="<?= esc($perfil['uf'] ?? '') ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Número</label>
                                <input type="text" name="numero" class="form-control"
                                       placeholder="Ex: 123"
                                       value="<?= esc($perfil['numero'] ?? '') ?>">
                            </div>

                            <div class="col-md-9">
                                <label class="form-label fw-semibold">Complemento <span class="text-muted small">(Opcional)</span></label>
                                <input type="text" name="complemento" class="form-control"
                                       placeholder="Ex: Apto 12, Bloco B"
                                       value="<?= esc($perfil['complemento'] ?? '') ?>">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- SEÇÃO 3 — DADOS POLÍTICOS / SOCIAIS                   -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header fw-bold" style="background:#C8102E;color:#fff;">
                        <i class="bi bi-star-fill me-2"></i> Dados Políticos e Sociais
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Filiação Partidária / Política / Religiosa</label>
                                <input type="text" name="filiacao" class="form-control"
                                       placeholder="Ex: PT, MST, Igreja Católica, sem filiação..."
                                       value="<?= esc($perfil['filiacao'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-block">Você é um ativista?</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ativista"
                                               id="ativista_sim" value="sim"
                                               <?= ($perfil['ativista'] ?? 0) == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-success" for="ativista_sim">
                                            ✅ Sim
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ativista"
                                               id="ativista_nao" value="nao"
                                               <?= ($perfil['ativista'] ?? 1) == 0 ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-secondary" for="ativista_nao">
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    Representa ou assessora algum mandato ou entidade?
                                </label>
                                <input type="text" name="representa_entidade" class="form-control"
                                       placeholder="Ex: Vereadora Maria Silva - PSOL, Instituto Lula, Coletivo Periferia..."
                                       value="<?= esc($perfil['representa_entidade'] ?? '') ?>">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- SEÇÃO 4 — CURRÍCULO DO FESTEIRO                       -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header fw-bold" style="background:#C9971C;color:#fff;">
                        <i class="bi bi-journal-text me-2"></i> Currículo do Festeiro
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Histórico de Cargos / Militância</label>
                                <textarea name="historico_cargos" class="form-control" rows="4"
                                          placeholder="Conte sua trajetória política e de militância. Ex:&#10;2019-2022 — Presidente do DCE da UFPE&#10;2020-hoje — Coordenador do Grupo Cultural Maculelê&#10;2022 — Candidato a Vereador pelo PT..."><?= esc($perfil['historico_cargos'] ?? '') ?></textarea>
                                <div class="form-text">Coloque um cargo por linha para facilitar a leitura.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Hobbies e Interesses</label>
                                <textarea name="hobbies" class="form-control" rows="2"
                                          placeholder="Ex: Capoeira, Literatura de Cordel, Fotografia, Culinária Nordestina..."><?= esc($perfil['hobbies'] ?? '') ?></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Voltar ao Dashboard
                    </a>
                    <button type="submit" class="btn btn-danger px-5 fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Salvar Perfil
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ── Preview da foto antes do upload ──
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('foto-preview');
            const icon    = document.getElementById('foto-icon');
            if (icon) icon.style.display = 'none';
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Máscara de CEP ──
document.getElementById('cep').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
    this.value = v;
    if (v.replace(/\D/g, '').length === 8) buscarCep(v.replace(/\D/g, ''));
});

// ── Busca ViaCEP ──
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
        }
    } catch (e) {
        erro.style.display = 'block';
    } finally {
        spinner.style.display = 'none';
    }
}
</script>
<?= $this->endSection() ?>
