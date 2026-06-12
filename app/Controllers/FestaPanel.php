<?php

namespace App\Controllers;

use App\Models\FestaModel;
use App\Models\FestaPostModel;
use App\Models\FestaLinkModel;
use App\Models\FestaHomenageadoModel;

helper('slug');

/**
 * Painel do Festeiro — gerencia Blog, Links e Homenageados de cada festa.
 * Acesso: /festa-panel/{festaId}/{secao}
 */
class FestaPanel extends BaseController
{
    private FestaModel $festaModel;

    public function __construct()
    {
        $this->festaModel = new FestaModel();
    }

    // ─── Guarda de segurança ────────────────────────────────────────────
    private function getFesta(int $id): ?array
    {
        $isAdmin = auth()->user()->inGroup('admin');
        $q = $this->festaModel->where('id', $id);
        if (! $isAdmin) {
            $q->where('user_id', auth()->id());
        }
        return $q->first();
    }

    // ═══════════════════════════════════════════════════════════════════
    // BLOG
    // ═══════════════════════════════════════════════════════════════════

    public function blog(int $festaId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $postModel = new FestaPostModel();
        $post      = $postModel->getUltimoPorFesta($festaId);

        return view('festa_panel/blog', [
            'festa' => $festa,
            'post'  => $post ?? null,
        ]);
    }

    public function salvarBlog(int $festaId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $postModel = new FestaPostModel();
        $post      = $postModel->getUltimoPorFesta($festaId);
        $acao      = $this->request->getPost('acao'); // 'rascunho' ou 'publicar'
        $novoStatus = ($acao === 'publicar') ? 'pendente' : 'rascunho';

        $conteudo = $this->request->getPost('conteudo');

        if ($post) {
            // Não regrida um post aprovado para rascunho sem motivo
            if ($post['status'] === 'aprovado' && $acao !== 'publicar') {
                $novoStatus = 'aprovado';
            }
            $postModel->update($post['id'], [
                'conteudo' => $conteudo,
                'status'   => $novoStatus,
            ]);
        } else {
            $postModel->insert([
                'festa_id' => $festaId,
                'conteudo' => $conteudo,
                'status'   => $novoStatus,
            ]);
        }

        $msg = $acao === 'publicar'
            ? 'Texto enviado para aprovação! O administrador será notificado. 📬'
            : 'Rascunho salvo! Você pode continuar editando antes de publicar.';

        return redirect()->to("festa-panel/{$festaId}/blog")->with('message', $msg);
    }

    // ═══════════════════════════════════════════════════════════════════
    // LINKS
    // ═══════════════════════════════════════════════════════════════════

    public function links(int $festaId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $linkModel = new FestaLinkModel();
        $links     = $linkModel->getLinksPorFesta($festaId);

        return view('festa_panel/links', [
            'festa' => $festa,
            'links' => $links,
        ]);
    }

    public function adicionarLink(int $festaId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $url = trim($this->request->getPost('url') ?? '');
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return redirect()->back()->withInput()->with('error', 'URL inválida. Use http:// ou https://');
        }

        (new FestaLinkModel())->insert([
            'festa_id'   => $festaId,
            'titulo'     => $this->request->getPost('titulo'),
            'url'        => $url,
            'status'     => 'pendente',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to("festa-panel/{$festaId}/links")
            ->with('message', 'Link enviado para aprovação do administrador! 🔗');
    }

    public function removerLink(int $festaId, int $linkId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $linkModel = new FestaLinkModel();
        $link = $linkModel->where('id', $linkId)->where('festa_id', $festaId)->first();
        if ($link) $linkModel->delete($linkId);

        return redirect()->to("festa-panel/{$festaId}/links")->with('message', 'Link removido.');
    }

    // ═══════════════════════════════════════════════════════════════════
    // HOMENAGEADOS
    // ═══════════════════════════════════════════════════════════════════

    public function homenageados(int $festaId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $homModel    = new FestaHomenageadoModel();
        $homenageados = $homModel->getPorFesta($festaId);

        return view('festa_panel/homenageados', [
            'festa'        => $festa,
            'homenageados' => $homenageados,
        ]);
    }

    public function adicionarHomenageado(int $festaId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        // Foto (opcional)
        $fotoNome = null;
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && ! $fotoFile->hasMoved()) {
            $path = FCPATH . 'uploads/homenageados/';
            if (! is_dir($path)) mkdir($path, 0775, true);
            $fotoNome = $fotoFile->getRandomName();
            $fotoFile->move($path, $fotoNome);
        }

        (new FestaHomenageadoModel())->insert([
            'festa_id'   => $festaId,
            'nome'       => $this->request->getPost('nome'),
            'titulo'     => $this->request->getPost('titulo'),
            'foto'       => $fotoNome,
            'frase'      => $this->request->getPost('frase'),
            'ordem'      => (int) ($this->request->getPost('ordem') ?? 0),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to("festa-panel/{$festaId}/homenageados")
            ->with('message', 'Homenageado adicionado! 🌟');
    }

    public function removerHomenageado(int $festaId, int $homId)
    {
        $festa = $this->getFesta($festaId);
        if (! $festa) return redirect()->to('dashboard')->with('error', 'Acesso negado.');

        $homModel = new FestaHomenageadoModel();
        $hom = $homModel->where('id', $homId)->where('festa_id', $festaId)->first();
        if ($hom) {
            // Remove a foto se existir
            if ($hom['foto']) {
                $path = FCPATH . 'uploads/homenageados/' . $hom['foto'];
                if (file_exists($path)) unlink($path);
            }
            $homModel->delete($homId);
        }

        return redirect()->to("festa-panel/{$festaId}/homenageados")->with('message', 'Homenageado removido.');
    }
}
