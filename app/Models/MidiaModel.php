<?php

namespace App\Models;

use CodeIgniter\Model;

class MidiaModel extends Model
{
    protected $table            = 'midias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['festa_id', 'arquivo', 'nome_original', 'tipo', 'status'];
}