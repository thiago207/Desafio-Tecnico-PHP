<?php
// app/Models/AtividadeModel.php
namespace App\Models;

use CodeIgniter\Model;

class AtividadeModel extends Model
{
    protected $table         = 'atividades';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['usuario_id', 'nome', 'descricao', 'inicio', 'fim', 'status'];
    protected $returnType    = 'array';

    // ── Listar todas as atividades do usuário ─────────────
    public function listarPorUsuario(int $usuarioId, array $filtros = []): array
    {
        $builder = $this->where('usuario_id', $usuarioId);

        if (!empty($filtros['status'])) {
            $builder->where('status', $filtros['status']);
        }

        if (!empty($filtros['busca'])) {
            $builder->groupStart()
                        ->like('nome', $filtros['busca'])
                        ->orLike('descricao', $filtros['busca'])
                    ->groupEnd();
        }

        return $builder->orderBy('inicio', 'ASC')->findAll();
    }

    // ── Buscar uma atividade do usuário (evita acesso cruzado) ──
    public function buscarDoUsuario(int $id, int $usuarioId): array|false
    {
        return $this->where('id', $id)
                    ->where('usuario_id', $usuarioId)
                    ->first() ?? false;
    }

    // ── Atividades para o calendário (intervalo de datas) ─
    public function eventosPorIntervalo(int $usuarioId, string $inicio, string $fim): array
    {
        return $this->where('usuario_id', $usuarioId)
                    ->where('inicio >=', $inicio)
                    ->where('inicio <=', $fim)
                    ->findAll();
    }

    // ── Alterar apenas o status ───────────────────────────
    public function alterarStatus(int $id, int $usuarioId, string $status): bool
    {
        return $this->where('id', $id)
                    ->where('usuario_id', $usuarioId)
                    ->set('status', $status)
                    ->update();
    }
}
