<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\PedidoModel;
use App\Models\PedidoItemModel;
use App\Models\FestaModel;

/**
 * Carrinho — gerencia o carrinho de compras baseado em sessão.
 *
 * Permite que qualquer visitante monte o carrinho sem precisar
 * de conta. O login é exigido apenas no momento de finalizar.
 */
class Carrinho extends BaseController
{
    /** Chave da sessão onde o carrinho é armazenado. */
    private const CART_KEY = 'carrinho';

    // ----------------------------------------------------------------
    // Adicionar produtos ao carrinho
    // ----------------------------------------------------------------

    public function adicionar()
    {
        $quantidades = $this->request->getPost('quantidades');

        if (empty($quantidades) || ! is_array($quantidades)) {
            return redirect()->back()->with('error', 'Selecione ao menos um produto.');
        }

        $carrinho = session()->get(self::CART_KEY) ?? [];

        foreach ($quantidades as $produtoId => $qtd) {
            $qtd = (int) $qtd;
            if ($qtd > 0) {
                $carrinho[(int) $produtoId] = ($carrinho[(int) $produtoId] ?? 0) + $qtd;
            }
        }

        session()->set(self::CART_KEY, $carrinho);

        $totalItens = count($carrinho);
        return redirect()->to('carrinho')
            ->with('message', "Produtos adicionados! Você tem {$totalItens} tipo(s) no carrinho.");
    }

    // ----------------------------------------------------------------
    // Exibir carrinho
    // ----------------------------------------------------------------

    public function index()
    {
        $carrinho  = session()->get(self::CART_KEY) ?? [];
        $itens     = [];
        $total     = 0.0;

        if (! empty($carrinho)) {
            $produtoModel = new ProdutoModel();

            foreach ($carrinho as $produtoId => $qtd) {
                $produto = $produtoModel->find($produtoId);
                if ($produto) {
                    $produto['quantidade'] = $qtd;
                    $produto['subtotal']   = (float) $produto['preco'] * $qtd;
                    $total                += $produto['subtotal'];
                    $itens[]              = $produto;
                }
            }
        }

        // Carrega festas do usuário logado para vincular o pedido (opcional)
        $minhasFestas = [];
        if (auth()->loggedIn()) {
            $festaModel   = new FestaModel();
            $minhasFestas = $festaModel->where('user_id', auth()->id())->findAll();
        }

        return view('carrinho/index', [
            'itens'       => $itens,
            'total'       => $total,
            'minhasFestas' => $minhasFestas,
        ]);
    }

    // ----------------------------------------------------------------
    // Remover um item do carrinho
    // ----------------------------------------------------------------

    public function remover($produtoId)
    {
        $carrinho = session()->get(self::CART_KEY) ?? [];
        unset($carrinho[(int) $produtoId]);
        session()->set(self::CART_KEY, $carrinho);

        return redirect()->back()->with('message', 'Item removido do carrinho.');
    }

    // ----------------------------------------------------------------
    // Limpar o carrinho inteiro
    // ----------------------------------------------------------------

    public function limpar()
    {
        session()->remove(self::CART_KEY);
        return redirect()->to('carrinho')->with('message', 'Carrinho esvaziado.');
    }

    // ----------------------------------------------------------------
    // Finalizar pedido (requer login — protegido pela rota)
    // ----------------------------------------------------------------

    public function finalizar()
    {
        if (! auth()->loggedIn()) {
            session()->setTempdata('beforeLoginUrl', current_url(), 300);
            return redirect()->to('login')->with('error', 'Faça login para finalizar o pedido.');
        }

        $carrinho = session()->get(self::CART_KEY) ?? [];

        if (empty($carrinho)) {
            return redirect()->to('carrinho')->with('error', 'Seu carrinho está vazio.');
        }

        // Festa opcional — vincula o pedido a uma festa do usuário
        $festaId = $this->request->getPost('festa_id') ?: null;

        if ($festaId !== null) {
            $festaModel = new FestaModel();
            $festa = $festaModel->where('id', $festaId)
                                ->where('user_id', auth()->id())
                                ->first();
            if (! $festa) {
                return redirect()->back()->with('error', 'Festa selecionada não é válida.');
            }
        }

        // Salva com transação para garantir atomicidade
        $db = \Config\Database::connect();
        $db->transStart();

        $pedidoModel = new PedidoModel();
        $pedidoId    = $pedidoModel->insert([
            'festa_id' => $festaId,
            'user_id'  => auth()->id(),
            'status'   => 'Solicitado',
        ]);

        $itemModel = new PedidoItemModel();
        foreach ($carrinho as $produtoId => $qtd) {
            $itemModel->insert([
                'pedido_id'  => $pedidoId,
                'produto_id' => $produtoId,
                'quantidade' => $qtd,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erro ao finalizar o pedido. Tente novamente.');
        }

        // Limpa o carrinho após o pedido
        session()->remove(self::CART_KEY);

        return redirect()->to('dashboard')
            ->with('message', 'Pedido #' . $pedidoId . ' criado com sucesso! Aguarde confirmação.');
    }
}
