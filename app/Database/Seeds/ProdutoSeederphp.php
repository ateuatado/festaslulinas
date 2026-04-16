<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nome'      => 'Bandeira Oficial',
                'descricao' => 'Bandeira em tecido 100% poliéster, tamanho 100x70cm.',
                'tipo'      => 'Material',
                'imagem'    => null, // Depois você pode por o nome do arquivo se tiver
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Kit Adesivos',
                'descricao' => 'Pacote com 50 adesivos variados (redondos e retangulares).',
                'tipo'      => 'Material',
                'imagem'    => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Camiseta Vermelha',
                'descricao' => 'Camiseta algodão com estampa frontal. Tamanhos P, M, G.',
                'tipo'      => 'Vestuário',
                'imagem'    => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome'      => 'Cartaz A3',
                'descricao' => 'Cartaz para colagem em pontos estratégicos. Pacote com 20.',
                'tipo'      => 'Impresso',
                'imagem'    => null,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Usando o Query Builder
        $this->db->table('produtos')->insertBatch($data);
    }
}