<?php

namespace App\Controllers;

use App\Models\FestaModel;
use App\Models\MidiaModel;

class Galeria extends BaseController
{
    public function index($festaId)
    {
        $festaModel = new FestaModel();
        $midiaModel = new MidiaModel();

        // 1. Segurança: Verifica se a festa pertence ao usuário
        $festa = $festaModel->where('id', $festaId)
                            ->where('user_id', auth()->id())
                            ->first();

        if (!$festa) {
            return redirect()->to('dashboard')->with('error', 'Acesso negado.');
        }

        // 2. Busca mídias já enviadas
        $midias = $midiaModel->where('festa_id', $festaId)
                             ->orderBy('created_at', 'DESC')
                             ->findAll();

        return view('galeria/index', [
            'festa' => $festa,
            'midias' => $midias
        ]);
    }

    public function upload($festaId)
    {
        $festaModel = new FestaModel();

        // Verificação de Segurança
        if (!$festaModel->where('id', $festaId)->where('user_id', auth()->id())->first()) {
            return redirect()->back()->with('error', 'Erro de permissão.');
        }

        $files = $this->request->getFiles();

        if (empty($files) || empty($files['arquivos'])) {
            return redirect()->back()->with('error', 'Nenhum arquivo selecionado.');
        }

        // Tipos MIME permitidos e tamanho máximo (bytes)
        $mimePermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
        $maxBytes       = 10 * 1024 * 1024; // 10 MB

        $midiaModel = new MidiaModel();
        $uploadPath = FCPATH . 'uploads/galeria/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $contagem = 0;
        $erros    = [];

        foreach ($files['arquivos'] as $file) {
            // BUG 3 FIX: Validação aplicada individualmente para cada arquivo
            if (!$file->isValid() || $file->hasMoved()) {
                continue;
            }

            // Valida tipo MIME real (não apenas extensão do cliente)
            $mime = $file->getMimeType();
            if (!in_array($mime, $mimePermitidos)) {
                $erros[] = "'{$file->getClientName()}': tipo não permitido ({$mime}).";
                continue;
            }

            // Valida tamanho
            if ($file->getSize() > $maxBytes) {
                $erros[] = "'{$file->getClientName()}': arquivo muito grande (max 10MB).";
                continue;
            }

            // Define tipo de mídia
            $tipo     = str_starts_with($mime, 'video/') ? 'video' : 'foto';
            $novoNome = $file->getRandomName();

            $file->move($uploadPath, $novoNome);

            $midiaModel->insert([
                'festa_id'      => $festaId,
                'arquivo'       => $novoNome,
                'nome_original' => $file->getClientName(),
                'tipo'          => $tipo,
                'status'        => 'pendente',
            ]);

            $contagem++;
        }

        if (!empty($erros)) {
            $msgErro = implode(' | ', $erros);
            if ($contagem > 0) {
                return redirect()->back()
                    ->with('message', "$contagem arquivo(s) enviado(s) para aprovação.")
                    ->with('error', "Arquivo(s) rejeitado(s): $msgErro");
            }
            return redirect()->back()->with('error', "Nenhum arquivo aceito. $msgErro");
        }

        return redirect()->back()->with('message', "$contagem arquivo(s) enviado(s) para aprovação!");
    }
    
    // Método para deletar (caso o usuário tenha enviado errado)
    public function delete($id)
    {
        $midiaModel = new MidiaModel();
        $festaModel = new FestaModel();
        
        $midia = $midiaModel->find($id);
        
        if ($midia) {
            // Verifica se a festa da mídia pertence ao usuário logado
            $festa = $festaModel->where('id', $midia['festa_id'])
                                ->where('user_id', auth()->id())
                                ->first();
                                
            if ($festa) {
                // Remove arquivo físico
                $path = FCPATH . 'uploads/galeria/' . $midia['arquivo'];
                if (file_exists($path)) {
                    unlink($path);
                }
                // Remove do banco
                $midiaModel->delete($id);
                return redirect()->back()->with('message', 'Arquivo removido.');
            }
        }
        
        return redirect()->back()->with('error', 'Erro ao remover.');
    }
}