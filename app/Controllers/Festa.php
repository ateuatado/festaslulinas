<?php

namespace App\Controllers;

use App\Models\FestaModel;
use App\Models\MidiaModel;
use App\Models\FestaPostModel;
use App\Models\FestaLinkModel;
use App\Models\FestaHomenageadoModel;
use App\Models\UserProfileModel;

class Festa extends BaseController
{
    /**
     * Exibe a página pública de uma festa.
     * Aceita /festa/{slug} (SEO) ou /festa/{id} (retrocompatibilidade).
     */
    public function ver(string $slugOrId)
    {
        $festaModel  = new FestaModel();
        $midiaModel  = new MidiaModel();
        $postModel   = new FestaPostModel();
        $linkModel   = new FestaLinkModel();
        $homModel    = new FestaHomenageadoModel();
        $perfilModel = new UserProfileModel();

        // Resolve slug ou id
        $festa = $festaModel->findBySlugOrId($slugOrId);

        if (! $festa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Festa não encontrada.');
        }

        // Redireciona /festa/{id} para /festa/{slug} se existir (SEO 301)
        if (ctype_digit($slugOrId) && ! empty($festa['slug'])) {
            return redirect()->to(base_url('festa/' . $festa['slug']), 301);
        }

        // Mídias aprovadas
        $midias = $midiaModel->where('festa_id', $festa['id'])
                             ->where('status', 'aprovado')
                             ->orderBy('created_at', 'DESC')
                             ->findAll();

        // Blog aprovado
        $post = $postModel->getAprovadoPorFesta($festa['id']);

        // Links aprovados
        $links = $linkModel->getLinksPorFesta($festa['id'], 'aprovado');

        // Homenageados
        $homenageados = $homModel->getPorFesta($festa['id']);

        // Perfil do festeiro organizador
        $perfil = $perfilModel->findByUserId($festa['user_id']);

        return view('festa_publica', [
            'festa'        => $festa,
            'midias'       => $midias,
            'post'         => $post,
            'links'        => $links,
            'homenageados' => $homenageados,
            'perfil'       => $perfil,
        ]);
    }
}