<?php

namespace App\Controllers;

use App\Models\MidiaModel;
use App\Models\FestaModel;
use App\Models\ApoiadorModel;
use App\Models\ProdutoModel;
use App\Models\FestaPostModel;
use App\Models\FestaLinkModel;
use CodeIgniter\Shield\Entities\User as ShieldUser;

class Admin extends BaseController
{
    // ----------------------------------------------------------------
    // Guard — verifica se o usuário autenticado pertence ao grupo admin
    // ----------------------------------------------------------------

    private function checkAdmin(): void
    {
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function index(): string
    {
        $this->checkAdmin();
        return view('admin/index');
    }

    // ----------------------------------------------------------------
    // MODERAÇÃO DE MÍDIA
    // ----------------------------------------------------------------

    public function midias()
    {
        $this->checkAdmin();

        $midiaModel = new MidiaModel();

        $pendentes = $midiaModel->select('midias.*, festas.nome_festa, festas.cidade, festas.uf')
                                ->join('festas', 'festas.id = midias.festa_id')
                                ->where('midias.status', 'pendente')
                                ->findAll();

        $aprovadas = $midiaModel->select('midias.*, festas.nome_festa, festas.cidade, festas.uf')
                                ->join('festas', 'festas.id = midias.festa_id')
                                ->where('midias.status', 'aprovado')
                                ->orderBy('midias.updated_at', 'DESC')
                                ->findAll(50); // limita a 50 para não sobrecarregar

        return view('admin/midias', [
            'pendentes' => $pendentes,
            'aprovadas' => $aprovadas,
        ]);
    }

    public function statusMidia($id, $novoStatus)
    {
        $this->checkAdmin();

        if (! in_array($novoStatus, ['aprovado', 'rejeitado'], true)) {
            return redirect()->back()->with('error', 'Status inválido.');
        }

        (new MidiaModel())->update($id, ['status' => $novoStatus]);

        return redirect()->back()->with('message', 'Mídia marcada como ' . $novoStatus . '.');
    }

    // ----------------------------------------------------------------
    // GESTÃO DE APOIADORES
    // ----------------------------------------------------------------

    public function apoiadores()
    {
        $this->checkAdmin();

        $apoiadores = (new ApoiadorModel())->findAll();
        return view('admin/apoiadores', ['apoiadores' => $apoiadores]);
    }

    public function salvarApoiador()
    {
        $this->checkAdmin();

        $regras = [
            'nome'       => 'required',
            'funcao'     => 'required',
            'prioridade' => 'required|integer',
            'foto'       => 'uploaded[foto]|is_image[foto]|max_size[foto,10240]',
            'frase'      => 'permit_empty|string',
        ];

        if (! $this->validate($regras)) {
            return redirect()->back()->with('error', 'Dados inválidos ou foto ausente.');
        }

        $file = $this->request->getFile('foto');

        if ($file->isValid() && ! $file->hasMoved()) {
            $novoNome = $file->getRandomName();
            $path     = FCPATH . 'uploads/apoiadores/';

            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $file->move($path, $novoNome);

            (new ApoiadorModel())->save([
                'nome'       => $this->request->getPost('nome'),
                'funcao'     => $this->request->getPost('funcao'),
                'prioridade' => $this->request->getPost('prioridade'),
                'frase'      => $this->request->getPost('frase'),
                'foto'       => $novoNome,
            ]);

            return redirect()->back()->with('message', 'Apoiador adicionado!');
        }

        return redirect()->back()->with('error', 'Erro no upload.');
    }

    public function deletarApoiador($id)
    {
        $this->checkAdmin();
        (new ApoiadorModel())->delete($id);
        return redirect()->back()->with('message', 'Apoiador removido.');
    }

    // ----------------------------------------------------------------
    // GESTÃO DE FESTAS
    // ----------------------------------------------------------------

    public function festas()
    {
        $this->checkAdmin();

        $festas    = (new FestaModel())->orderBy('created_at', 'DESC')->findAll();
        $festaMap  = array_column($festas, null, 'id');

        $enrich = function (array &$rows) use ($festaMap): void {
            foreach ($rows as &$row) {
                $row['nome_festa'] = $festaMap[$row['festa_id']]['nome_festa'] ?? '(Festa #' . $row['festa_id'] . ')';
                $row['slug']       = $festaMap[$row['festa_id']]['slug'] ?? null;
            }
            unset($row);
        };

        $postsPend = (new FestaPostModel())->where('status', 'pendente')->orderBy('updated_at', 'DESC')->findAll();
        $postsApr  = (new FestaPostModel())->where('status', 'aprovado')->orderBy('updated_at', 'DESC')->findAll();
        $linksPend = (new FestaLinkModel())->where('status', 'pendente')->orderBy('id', 'DESC')->findAll();
        $linksApr  = (new FestaLinkModel())->where('status', 'aprovado')->orderBy('id', 'DESC')->findAll();

        $enrich($postsPend);
        $enrich($postsApr);
        $enrich($linksPend);
        $enrich($linksApr);

        return view('admin/festas', [
            'festas'    => $festas,
            'postsPend' => $postsPend,
            'postsApr'  => $postsApr,
            'linksPend' => $linksPend,
            'linksApr'  => $linksApr,
        ]);
    }

    public function excluirFesta($id)
    {
        $this->checkAdmin();
        (new FestaModel())->delete($id);
        return redirect()->to('admin/festas')->with('message', 'Festa removida pelo Administrador.');
    }

    // ── Aprovação de Posts do Blog ───────────────────────────────────
    public function statusPost($id, $novoStatus)
    {
        $this->checkAdmin();
        if (! in_array($novoStatus, ['aprovado', 'rejeitado', 'pendente'], true)) {
            return redirect()->back()->with('error', 'Status inválido.');
        }
        (new FestaPostModel())->update($id, ['status' => $novoStatus]);
        return redirect()->back()
            ->with('message', 'Post marcado como ' . $novoStatus . '.');
    }

    // ── Aprovação de Links ───────────────────────────────────────────
    public function statusLink($id, $novoStatus)
    {
        $this->checkAdmin();
        if (! in_array($novoStatus, ['aprovado', 'rejeitado', 'pendente'], true)) {
            return redirect()->back()->with('error', 'Status inválido.');
        }
        (new FestaLinkModel())->update($id, ['status' => $novoStatus]);
        return redirect()->back()
            ->with('message', 'Link marcado como ' . $novoStatus . '.');
    }

    // ----------------------------------------------------------------
    // GESTÃO DA LOJA (PRODUTOS) — usa ProdutoModel (soft delete correto)
    // ----------------------------------------------------------------

    public function produtos()
    {
        $this->checkAdmin();

        $produtos = (new ProdutoModel())->orderBy('created_at', 'DESC')->findAll();
        return view('admin/produtos', ['produtos' => $produtos]);
    }

    public function salvarProduto()
    {
        $this->checkAdmin();

        $produtoModel = new ProdutoModel();
        $id           = $this->request->getPost('id');

        $dados = [
            'nome'      => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'tipo'      => $this->request->getPost('tipo'),
            'preco'     => $this->request->getPost('preco'),
        ];

        $img = $this->request->getFile('foto');

        if ($img && $img->isValid() && ! $img->hasMoved()) {
            $novoNome = $img->getRandomName();
            $path     = FCPATH . 'uploads/produtos/';

            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $img->move($path, $novoNome);
            $dados['imagem'] = $novoNome;
        }

        if (! empty($id)) {
            $produtoModel->update($id, $dados);
            $msg = 'Produto atualizado com sucesso!';
        } else {
            $produtoModel->save($dados);
            $msg = 'Novo produto criado!';
        }

        return redirect()->to('admin/produtos')->with('message', $msg);
    }

    public function excluirProduto($id)
    {
        $this->checkAdmin();
        (new ProdutoModel())->delete($id);
        return redirect()->to('admin/produtos')->with('message', 'Item removido da loja.');
    }

    // ----------------------------------------------------------------
    // GERENCIAMENTO DE USUÁRIOS
    // ----------------------------------------------------------------

    public function usuarios()
    {
        $this->checkAdmin();

        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        $usuarios  = $userModel->withDeleted()->orderBy('username', 'ASC')->findAll();

        // Carrega grupos de todos de uma vez (evita N queries)
        $db          = \Config\Database::connect();
        $allGroups   = $db->table('auth_groups_users')->get()->getResultArray();
        $groupsByUser = [];

        foreach ($allGroups as $g) {
            $groupsByUser[(int) $g['user_id']][] = $g['group'];
        }

        return view('admin/usuarios', [
            'usuarios'    => $usuarios,
            'groupsByUser' => $groupsByUser,
        ]);
    }

    public function criarUsuario()
    {
        $this->checkAdmin();

        if (! $this->validate([
            'username' => 'required|min_length[3]|max_length[30]',
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
            'grupo'    => 'required|in_list[admin,user]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);

        // Verifica duplicidade manualmente (email é em auth_identities)
        $db = \Config\Database::connect();
        if ($db->table('auth_identities')->where('secret', $this->request->getPost('email'))->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'Este e-mail já está cadastrado.');
        }

        $user = new ShieldUser([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1,
        ]);

        $userModel->save($user);
        $user = $userModel->findById($userModel->getInsertID());
        $user->activate();
        $user->addGroup($this->request->getPost('grupo'));

        return redirect()->to('admin/usuarios')->with('message', 'Usuário criado com sucesso!');
    }

    public function toggleGrupo($userId, $grupo)
    {
        $this->checkAdmin();

        if (! in_array($grupo, ['admin', 'user'], true)) {
            return redirect()->back()->with('error', 'Grupo inválido.');
        }

        // Impede que o admin remova o próprio grupo
        if ((int) $userId === auth()->id() && $grupo === 'admin') {
            return redirect()->back()->with('error', 'Você não pode remover seu próprio acesso admin.');
        }

        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        /** @var ShieldUser|null $user */
        $user = $userModel->findById($userId);

        if (! $user) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }

        if ($user->inGroup($grupo)) {
            $user->removeGroup($grupo);
            $msg = "Grupo '{$grupo}' removido do usuário.";
        } else {
            $user->addGroup($grupo);
            $msg = "Usuário promovido ao grupo '{$grupo}'.";
        }

        return redirect()->back()->with('message', $msg);
    }

    public function desativarUsuario($userId)
    {
        $this->checkAdmin();

        if ((int) $userId === auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode desativar sua própria conta.');
        }

        $userModel = model(\CodeIgniter\Shield\Models\UserModel::class);
        $user      = $userModel->withDeleted()->findById($userId);

        if (! $user) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }

        if ($user->deletedAt !== null) {
            // Reativa
            $userModel->withDeleted()->update($userId, ['deleted_at' => null, 'active' => 1]);
            $msg = 'Usuário reativado com sucesso.';
        } else {
            // Desativa via soft delete
            $userModel->delete($userId);
            $msg = 'Usuário desativado.';
        }

        return redirect()->back()->with('message', $msg);
    }
}