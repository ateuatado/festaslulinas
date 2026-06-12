<?php

namespace App\Controllers;

use App\Models\UserProfileModel;
use App\Models\ApoiadorModel;

class PerfilController extends BaseController
{
    public function index()
    {
        $userId  = auth()->id();
        $model   = new UserProfileModel();
        $perfil  = $model->findByUserId($userId);

        return view('perfil/index', [
            'perfil' => $perfil ?? [],
            'user'   => auth()->user(),
        ]);
    }

    public function salvar()
    {
        $userId = auth()->id();
        $model  = new UserProfileModel();
        $perfil = $model->findByUserId($userId);

        // ── Upload da foto ──────────────────────────────────────────────
        $fotoNome = $perfil['foto'] ?? null; // mantém a foto existente por padrão

        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {

            // Valida imagem
            if (! in_array($fotoFile->getMimeType(), ['image/jpeg','image/png','image/webp','image/gif'])) {
                return redirect()->back()->withInput()
                    ->with('error', 'A foto deve ser uma imagem (JPG, PNG, WEBP ou GIF).');
            }
            if ($fotoFile->getSizeByUnit('mb') > 5) {
                return redirect()->back()->withInput()
                    ->with('error', 'A foto não pode ultrapassar 5 MB.');
            }

            $path     = FCPATH . 'uploads/perfil/';
            if (! is_dir($path)) mkdir($path, 0775, true);

            // Remove a foto antiga se existir
            if ($fotoNome && file_exists($path . $fotoNome)) {
                unlink($path . $fotoNome);
            }

            $fotoNome = $fotoFile->getRandomName();
            $fotoFile->move($path, $fotoNome);
        }

        // ── Dados do perfil ─────────────────────────────────────────────
        $dados = [
            'user_id'             => $userId,
            'nome_completo'       => $this->request->getPost('nome_completo'),
            'foto'                => $fotoNome,
            'cep'                 => preg_replace('/\D/', '', $this->request->getPost('cep') ?? ''),
            'logradouro'          => $this->request->getPost('logradouro'),
            'bairro'              => $this->request->getPost('bairro'),
            'cidade'              => $this->request->getPost('cidade'),
            'uf'                  => strtoupper($this->request->getPost('uf') ?? ''),
            'numero'              => $this->request->getPost('numero'),
            'complemento'         => $this->request->getPost('complemento'),
            'filiacao'            => $this->request->getPost('filiacao'),
            'ativista'            => $this->request->getPost('ativista') === 'sim' ? 1 : 0,
            'representa_entidade' => $this->request->getPost('representa_entidade'),
            'profissao'           => $this->request->getPost('profissao'),
            'historico_cargos'    => $this->request->getPost('historico_cargos'),
            'hobbies'             => $this->request->getPost('hobbies'),
        ];

        // ── Salva ou atualiza o perfil ──────────────────────────────────
        if ($perfil) {
            $model->update($perfil['id'], $dados);
        } else {
            $model->insert($dados);
        }

        // ── Auto-cria ou atualiza o Apoiador ───────────────────────────
        if ($fotoNome && $dados['nome_completo']) {
            $this->sincronizarApoiador($userId, $dados['nome_completo'], $fotoNome);
        }

        return redirect()->to('perfil')
            ->with('message', 'Perfil de Festeiro atualizado com sucesso! 🎉');
    }

    // ────────────────────────────────────────────────────────────────────
    // Cria ou atualiza o Apoiador vinculado a este usuário
    // ────────────────────────────────────────────────────────────────────
    private function sincronizarApoiador(int $userId, string $nome, string $foto): void
    {
        $apoiadorModel = new ApoiadorModel();

        // Copia a foto para a pasta de apoiadores (mantém ambas as pastas consistentes)
        $origem  = FCPATH . 'uploads/perfil/' . $foto;
        $destino = FCPATH . 'uploads/apoiadores/' . $foto;

        if (! is_dir(FCPATH . 'uploads/apoiadores/')) {
            mkdir(FCPATH . 'uploads/apoiadores/', 0775, true);
        }

        if (file_exists($origem) && ! file_exists($destino)) {
            copy($origem, $destino);
        }

        // Verifica se já existe um apoiador vinculado a este usuário
        $apoiador = $apoiadorModel->where('user_id', $userId)->first();

        if ($apoiador) {
            // Atualiza nome e foto
            $apoiadorModel->update($apoiador['id'], [
                'nome' => $nome,
                'foto' => $foto,
            ]);
        } else {
            // Cria novo apoiador com função Festeiro e prioridade média (3)
            $apoiadorModel->insert([
                'user_id'    => $userId,
                'nome'       => $nome,
                'funcao'     => 'Festeiro',
                'prioridade' => 3,
                'foto'       => $foto,
                'frase'      => '',
            ]);
        }
    }
}
