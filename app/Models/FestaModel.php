<?php

namespace App\Models;

use CodeIgniter\Model;

class FestaModel extends Model
{
    protected $table            = 'festas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true; // Importante para não perder dados acidentalmente
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 
        'nome_festa', 
        'data_hora', 
        'organizacao',
        'cep',
        'logradouro',
        'bairro',
        'cidade', 
        'uf', 
        'numero',
        'complemento',
        'local_evento', 
        'condicoes_acesso', 
        'descricao',
        'publico_estimado', 
        'publico_real'
    ];

    // Datas
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validação básica (Segurança extra)
    protected $validationRules      = [
        'nome_festa'   => 'required|min_length[3]|max_length[255]',
        'data_hora'    => 'required|valid_date',
        'organizacao'  => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}