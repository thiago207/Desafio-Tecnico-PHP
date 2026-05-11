<?php
// app/Controllers/AtividadeController.php
namespace App\Controllers;

use App\Models\AtividadeModel;
use CodeIgniter\Controller;

class AtividadeController extends Controller
{
    private AtividadeModel $model;
    private int $usuarioId;

    public function __construct()
    {
        $this->model = new AtividadeModel();
        helper(['url', 'form']);

        // Proteção de sessão em todas as rotas
        if (!session()->get('logado')) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $this->usuarioId = (int) session()->get('usuario_id');
    }

    // ── GET /agenda — lista de atividades ─────────────────
    public function index()
    {
        $filtros = [
            'status' => $this->request->getGet('status'),
            'busca'  => $this->request->getGet('busca'),
        ];

        $atividades = $this->model->listarPorUsuario($this->usuarioId, $filtros);

        return view('activities/index', [
            'atividades' => $atividades,
            'filtros'    => $filtros,
        ]);
    }

    // ── GET /agenda/calendario ────────────────────────────
    public function calendario()
    {
        return view('activities/calendario');
    }

    // ── GET /agenda/eventos?start=...&end=... (JSON) ──────
    public function eventos()
    {
        $start = $this->request->getGet('start') ?? date('Y-m-01');
        $end   = $this->request->getGet('end')   ?? date('Y-m-t');

        $atividades = $this->model->eventosPorIntervalo($this->usuarioId, $start, $end);

        $cores = [
            'pendente'  => ['bg' => '#FFC107', 'text' => '#000'],
            'concluida' => ['bg' => '#10B981', 'text' => '#fff'],
            'cancelada' => ['bg' => '#EF4444', 'text' => '#fff'],
        ];

        $eventos = array_map(function ($a) use ($cores) {
            $c = $cores[$a['status']] ?? $cores['pendente'];
            return [
                'id'              => $a['id'],
                'title'           => $a['nome'],
                'start'           => $a['inicio'],
                'end'             => $a['fim'],
                'backgroundColor' => $c['bg'],
                'textColor'       => $c['text'],
                'borderColor'     => $c['bg'],
                'extendedProps'   => [
                    'descricao' => $a['descricao'],
                    'status'    => $a['status'],
                ],
            ];
        }, $atividades);

        return $this->response->setJSON($eventos);
    }

    // ── POST /atividade/store (AJAX) ──────────────────────
    public function store()
    {
        $dados = $this->request->getJSON(true);
        $erro  = $this->validar($dados);

        if ($erro) {
            return $this->response->setJSON(['ok' => false, 'msg' => $erro]);
        }

        $this->model->insert([
            'usuario_id' => $this->usuarioId,
            'nome'       => $dados['nome'],
            'descricao'  => $dados['descricao'] ?? '',
            'inicio'     => $dados['inicio'],
            'fim'        => $dados['fim'],
            'status'     => $dados['status'] ?? 'pendente',
        ]);

        return $this->response->setJSON([
            'ok'  => true,
            'msg' => 'Atividade criada com sucesso!',
            'id'  => $this->model->getInsertID(),
        ]);
    }

    // ── GET /atividade/:id (AJAX) ─────────────────────────
    public function show(int $id)
    {
        $a = $this->model->buscarDoUsuario($id, $this->usuarioId);

        if (!$a) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Não encontrado.']);
        }

        return $this->response->setJSON(['ok' => true, 'atividade' => $a]);
    }

    // ── POST /atividade/update/:id (AJAX) ─────────────────
    public function update(int $id)
    {
        $a = $this->model->buscarDoUsuario($id, $this->usuarioId);

        if (!$a) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Não encontrado.']);
        }

        $dados = $this->request->getJSON(true);
        $erro  = $this->validar($dados);

        if ($erro) {
            return $this->response->setJSON(['ok' => false, 'msg' => $erro]);
        }

        $this->model->update($id, [
            'nome'      => $dados['nome'],
            'descricao' => $dados['descricao'] ?? '',
            'inicio'    => $dados['inicio'],
            'fim'       => $dados['fim'],
            'status'    => $dados['status'],
        ]);

        return $this->response->setJSON(['ok' => true, 'msg' => 'Atividade atualizada!']);
    }

    // ── POST /atividade/destroy/:id (AJAX) ────────────────
    public function destroy(int $id)
    {
        $a = $this->model->buscarDoUsuario($id, $this->usuarioId);

        if (!$a) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Não encontrado.']);
        }

        $this->model->delete($id);

        return $this->response->setJSON(['ok' => true, 'msg' => 'Atividade excluída.']);
    }

    // ── POST /atividade/status/:id (AJAX) ─────────────────
    public function updateStatus(int $id)
    {
        $dados  = $this->request->getJSON(true);
        $status = $dados['status'] ?? '';

        if (!in_array($status, ['pendente', 'concluida', 'cancelada'])) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Status inválido.']);
        }

        $ok = $this->model->alterarStatus($id, $this->usuarioId, $status);

        return $this->response->setJSON([
            'ok'  => $ok,
            'msg' => $ok ? 'Status atualizado!' : 'Erro ao atualizar.',
        ]);
    }

    // ── Validação interna ─────────────────────────────────
    private function validar(array $d): ?string
    {
        if (empty($d['nome'])) {
            return 'O nome da atividade é obrigatório.';
        }
        if (empty($d['inicio']) || empty($d['fim'])) {
            return 'Data/hora de início e término são obrigatórias.';
        }
        if (strtotime($d['fim']) <= strtotime($d['inicio'])) {
            return 'O término deve ser posterior ao início.';
        }
        return null;
    }
}
