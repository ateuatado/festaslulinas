<?php

namespace App\Controllers;

use App\Models\FestaModel;

class Festas extends BaseController
{
    /**
     * Lista pública de festas filtradas por ano.
     */
    public function index()
    {
        $anoAtual = (int) date('Y');
        $ano      = (int) ($this->request->getGet('ano') ?? $anoAtual);

        // Intervalo válido: 2020 até ano atual
        if ($ano < 2020 || $ano > $anoAtual) {
            $ano = $anoAtual;
        }

        $festaModel = new FestaModel();

        $festas = $festaModel
            ->where("YEAR(data_hora)", $ano)
            ->orderBy('data_hora', 'ASC')
            ->findAll();

        // Lista de anos disponíveis para o combo (atual até 2020)
        $anos = range($anoAtual, 2020);

        return view('festas_lista', [
            'festas' => $festas,
            'ano'    => $ano,
            'anos'   => $anos,
        ]);
    }
}
