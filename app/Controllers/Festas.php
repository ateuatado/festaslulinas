<?php

namespace App\Controllers;

use App\Models\FestaModel;

class Festas extends BaseController
{
    /**
     * Lista pública de todas as festas cadastradas (próximas primeiro).
     */
    public function index()
    {
        $festaModel = new FestaModel();

        $festas = $festaModel
            ->where('data_hora >=', date('Y-m-d'))   // só futuras ou hoje
            ->orderBy('data_hora', 'ASC')
            ->findAll();

        return view('festas_lista', ['festas' => $festas]);
    }
}
