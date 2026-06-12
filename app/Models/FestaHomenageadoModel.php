<?php
namespace App\Models;
use CodeIgniter\Model;

class FestaHomenageadoModel extends Model
{
    protected $table         = 'festa_homenageados';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['festa_id', 'nome', 'titulo', 'foto', 'frase', 'ordem'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    public function getPorFesta(int $festaId): array
    {
        return $this->where('festa_id', $festaId)
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
