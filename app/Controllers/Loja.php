<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\FestaModel;

class Loja extends BaseController
{
    public function index($festaId = null) 
    {
        $festaModel = new FestaModel();
        $produtoModel = new ProdutoModel();
        
        $data = [];
        $userId = auth()->id();

        // --- CENÁRIO 1: Acesso via Menu (Loja Geral) ---
        if ($festaId === null) {
            $data['festa'] = null; // Não tem festa vinculada
            $data['titulo'] = "Banca Lulina - Loja Oficial";
        } 
        // --- CENÁRIO 2: Acesso via "Minhas Festas" (Pedido Vinculado) ---
        else {
            // Verifica se a festa existe e é do usuário
            $festa = $festaModel->where('id', $festaId)
                                ->where('user_id', $userId)
                                ->first();

            if (!$festa) {
                return redirect()->to('dashboard')->with('error', 'Festa não encontrada.');
            }
            
            $data['festa'] = $festa;
            $data['titulo'] = "Materiais para: " . $festa['nome_festa'];
        }

        // Busca os produtos ordenados (agora com preço!)
        // Se você já rodou a migration de preços, eles virão automaticamente aqui
        $data['produtos'] = $produtoModel->orderBy('tipo', 'ASC')->findAll();

        return view('loja/index', $data);
    }

    // ... dentro da class Loja ...

    public function salvar($festaId)
    {
        $festaModel = new FestaModel();
        
        // 1. Segurança: Verifica se a festa é do usuário
        $festa = $festaModel->where('id', $festaId)
                            ->where('user_id', auth()->id())
                            ->first();

        if (!$festa) {
            return redirect()->to('dashboard')->with('error', 'Operação não autorizada.');
        }

        // 2. Coletar os dados do formulário
        $quantidades = $this->request->getPost('quantidades'); // Array [id_produto => qtd]
        if (empty($quantidades) || !is_array($quantidades)) {
            return redirect()->back()->with('error', 'Nenhum item foi selecionado ou não há produtos disponíveis.');
        }
        $itensParaSalvar = [];

        // Filtra apenas produtos com quantidade > 0
        foreach ($quantidades as $produtoId => $qtd) {
            if ($qtd > 0) {
                $itensParaSalvar[] = [
                    'produto_id' => $produtoId,
                    'quantidade' => $qtd
                ];
            }
        }

        if (empty($itensParaSalvar)) {
            return redirect()->back()->with('error', 'Selecione pelo menos um item para solicitar.');
        }

        // 3. Salvar no Banco (Usando Transação para garantir integridade)
        $db = \Config\Database::connect();
        $db->transStart(); // Inicia a transação

            // A. Criar o Pedido
            $pedidoModel = new \App\Models\PedidoModel();
            $pedidoId = $pedidoModel->insert([
                'festa_id' => $festaId,
                'user_id'  => auth()->id(),
                'status'   => 'Solicitado'
            ]);

            // B. Criar os Itens do Pedido
            $itemModel = new \App\Models\PedidoItemModel();
            foreach ($itensParaSalvar as $item) {
                $item['pedido_id'] = $pedidoId;
                $itemModel->insert($item);
            }

        $db->transComplete(); // Finaliza

        // 4. Redirecionar
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erro ao salvar o pedido. Tente novamente.');
        }

        return redirect()->to('dashboard')->with('message', 'Solicitação de material enviada com sucesso!');
    }
}