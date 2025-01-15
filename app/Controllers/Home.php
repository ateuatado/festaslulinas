<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index() {
       // view('template/header');
        return view('home');
        //view('template/footer');

    }

    public function sobre() {
        view('template/header');
        view('sobre');
        view('template/footer');
    }

    public function contato() {
        view('template/header');
        view('contato');
        view('template/footer');
    }
}
