<?php

namespace App\Controllers;

use App\Models\ApoiadorModel; // Adicione esta linha

class Home extends BaseController
{
    public function index()
    {
        $apoiadorModel = new \App\Models\ApoiadorModel();
        
        // A MÁGICA: 
        // 1. Ordena pela Prioridade (1 vem antes de 5)
        // 2. Dentro da mesma prioridade, ordena Aleatoriamente
        $apoiadores = $apoiadorModel->orderBy('prioridade', 'ASC')
                                    ->orderBy('RAND()')
                                    ->findAll(15); // Aumentei para 8 para mostrar "duas linhas" se o design permitir

        return view('home', ['apoiadores' => $apoiadores]);
    }
}