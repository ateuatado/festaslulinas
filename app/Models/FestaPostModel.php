<?php
namespace App\Models;
use CodeIgniter\Model;

class FestaPostModel extends Model
{
    protected $table         = 'festa_posts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['festa_id', 'conteudo', 'status'];
    protected $useTimestamps = true;

    /** Retorna o post mais recente de qualquer status para edição no painel. */
    public function getUltimoPorFesta(int $festaId): ?array
    {
        return $this->where('festa_id', $festaId)
                    ->orderBy('updated_at', 'DESC')
                    ->first();
    }

    /** Retorna apenas o post aprovado para exibição pública. */
    public function getAprovadoPorFesta(int $festaId): ?array
    {
        return $this->where('festa_id', $festaId)
                    ->where('status', 'aprovado')
                    ->orderBy('updated_at', 'DESC')
                    ->first();
    }
}
