<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FestaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $festaModel = new FestaModel();
        
        // Pega apenas as festas do usuário logado
        $userId = auth()->id();
        $minhasFestas = $festaModel->where('user_id', $userId)->findAll();

        $data = [
            'festas' => $minhasFestas
        ];

        return view('dashboard/index', $data);
    }

    // Método para exibir o formulário de nova festa
    public function nova()
    {
        return view('dashboard/nova_festa');
    }

    // ... dentro da class Dashboard ...

    public function salvar()
    {
        $isAdmin = auth()->user()->inGroup('admin');

        // 1. Monta data_hora a partir dos dois campos separados
        $dataEvento = $this->request->getPost('data_evento');
        $horaEvento = $this->request->getPost('hora_evento');
        $dataHora   = $dataEvento . ' ' . $horaEvento . ':00';

        // 2. Validação
        $regras = [
            'nome_festa'       => 'required|min_length[3]',
            'data_evento'      => 'required|valid_date[Y-m-d]',
            'hora_evento'      => 'required',
            'organizacao'      => 'required',
            'cep'              => 'required|min_length[8]',
            'local_evento'     => 'required',
            'tamanho_festa'    => 'required',
        ];

        if (! $this->validate($regras)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Valida período para usuários comuns
        if (! $isAdmin) {
            $dataEvento = new \DateTime($dataEvento);
            $dataMin    = new \DateTime('2026-07-13');
            $dataMax    = new \DateTime('2026-08-13');
            if ($dataEvento < $dataMin || $dataEvento > $dataMax) {
                return redirect()->back()->withInput()
                    ->with('errors', ['data_evento' => 'A data deve estar entre 13/07/2026 e 13/08/2026. Festas fora deste período devem ser encaminhadas ao administrador.']);
            }
        }

        // 4. Salva
        $festaModel = new FestaModel();
        $dados = [
            'user_id'          => auth()->id(),
            'nome_festa'       => $this->request->getPost('nome_festa'),
            'data_hora'        => $dataHora,
            'organizacao'      => $this->request->getPost('organizacao'),
            'cep'              => preg_replace('/\D/', '', $this->request->getPost('cep')),
            'logradouro'       => $this->request->getPost('logradouro'),
            'bairro'           => $this->request->getPost('bairro'),
            'cidade'           => $this->request->getPost('cidade'),
            'uf'               => strtoupper($this->request->getPost('uf')),
            'numero'           => $this->request->getPost('numero'),
            'complemento'      => $this->request->getPost('complemento'),
            'local_evento'     => $this->request->getPost('local_evento'),
            'tamanho_festa'    => $this->request->getPost('tamanho_festa'),
            'descricao'        => $this->request->getPost('descricao'),
        ];

        if ($festaModel->save($dados)) {
            return redirect()->to('dashboard')->with('message', 'Festa cadastrada com sucesso!');
        }

        return redirect()->back()->withInput()->with('errors', $festaModel->errors());
    }

    // Exibe o formulário de edição
    public function editar($id)
    {
        $festaModel = new FestaModel();
        
        // Busca a festa garantindo que é do usuário logado
        $festa = $festaModel->where('id', $id)
                            ->where('user_id', auth()->id())
                            ->first();

        if (!$festa) {
            return redirect()->to('dashboard')->with('error', 'Festa não encontrada.');
        }

        return view('dashboard/editar', ['festa' => $festa]);
    }

    // Processa a atualização
    public function atualizar($id)
    {
        $festaModel = new FestaModel();
        
        // Verifica propriedade
        $festa = $festaModel->where('id', $id)->where('user_id', auth()->id())->first();
        if (!$festa) return redirect()->to('dashboard');

        // Regras de validação
        $regras = [
            'nome_festa' => 'required|min_length[3]',
            'data_hora'  => 'required',
            'cidade'     => 'required',
            // Públicos são opcionais, mas devem ser números
            'publico_estimado' => 'permit_empty|integer',
            'publico_real'     => 'permit_empty|integer',
        ];

        if (!$this->validate($regras)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Dados para atualizar
        $dados = [
            'nome_festa'       => $this->request->getPost('nome_festa'),
            'data_hora'        => $this->request->getPost('data_hora'),
            'organizacao'      => $this->request->getPost('organizacao'),
            'cidade'           => $this->request->getPost('cidade'),
            'uf'               => $this->request->getPost('uf'),
            'local_evento'     => $this->request->getPost('local_evento'),
            'condicoes_acesso' => $this->request->getPost('condicoes_acesso'),
            'descricao'        => $this->request->getPost('descricao'),
            'publico_estimado' => $this->request->getPost('publico_estimado'),
            'publico_real'     => $this->request->getPost('publico_real'),
        ];

        $festaModel->update($id, $dados);

        return redirect()->to('dashboard')->with('message', 'Dados da festa atualizados!');
    }

    public function excluir($id)
    {
        $festaModel = new FestaModel();
        
        // Verifica se a festa existe e pertence ao usuário (Segurança)
        $festa = $festaModel->where('id', $id)->where('user_id', auth()->id())->first();
        
        if ($festa) {
            $festaModel->delete($id); // Soft delete automático
            return redirect()->to('dashboard')->with('message', 'Festa removida com sucesso.');
        }

        return redirect()->to('dashboard')->with('error', 'Erro ao remover festa.');
    }
}