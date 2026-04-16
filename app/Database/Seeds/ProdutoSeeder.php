<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nome'      => 'Kit Bandeirinhas (50m)',
                'descricao' => 'Pacote com 50 metros de bandeirinhas vermelhas e com a estrela do PT.',
                'tipo'      => 'Decoração',
                'imagem'    => 'bandeirinhas.jpg', 
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Flâmula de Tecido',
                'descricao' => 'Flâmula vertical para pendurar em postes ou paredes. Tamanho 40x60cm.',
                'tipo'      => 'Decoração',
                'imagem'    => 'flamula.jpg',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Totem Fotográfico (Tamanho Real)',
                'descricao' => 'Totem de papelão rígido do Presidente Lula para fotos.',
                'tipo'      => 'Cenografia',
                'imagem'    => 'totem.jpg',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Adesivos "Orgulho de ser Brasileiro"',
                'descricao' => 'Rolo com 100 adesivos redondos para distribuição.',
                'tipo'      => 'Material Gráfico',
                'imagem'    => 'adesivos.jpg',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Camiseta Oficial 2026',
                'descricao' => 'Camiseta vermelha 100% algodão. Tamanhos variados no pacote.',
                'tipo'      => 'Vestuário',
                'imagem'    => 'camiseta.jpg',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Usando o Query Builder para inserir de uma vez
        $this->db->table('produtos')->insertBatch($data);
    }
}