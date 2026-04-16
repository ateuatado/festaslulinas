<?php

namespace App\Controllers;

use App\Models\FestaModel;
use App\Models\MidiaModel;

class Festa extends BaseController
{
    public function ver($id)
    {
        $festaModel = new FestaModel();
        $midiaModel = new MidiaModel();

        // 1. Busca a Festa
        $festa = $festaModel->find($id);

        if (!$festa) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Festa não encontrada.");
        }

        // 2. Busca APENAS as mídias APROVADAS pelo Admin
        $midias = $midiaModel->where('festa_id', $id)
                             ->where('status', 'aprovado') // <--- O filtro mágico
                             ->orderBy('created_at', 'DESC')
                             ->findAll();

        return view('festa_publica', [
            'festa' => $festa,
            'midias' => $midias
        ]);
    }
}