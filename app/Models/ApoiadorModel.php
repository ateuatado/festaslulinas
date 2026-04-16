<?php

namespace App\Models;

use CodeIgniter\Model;

class ApoiadorModel extends Model
{
    protected $table            = 'apoiadores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields = ['nome', 'funcao', 'foto', 'prioridade', 'frase'];
    protected $useTimestamps    = true;
}