<?php
namespace App\Models;
use CodeIgniter\Model;

class FestaLinkModel extends Model
{
    protected $table         = 'festa_links';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['festa_id', 'titulo', 'url', 'status'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    public function getLinksPorFesta(int $festaId, string $status = null): array
    {
        $q = $this->where('festa_id', $festaId);
        if ($status) $q->where('status', $status);
        return $q->orderBy('id', 'ASC')->findAll();
    }
}
