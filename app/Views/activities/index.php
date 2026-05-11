<?php
$statusMap = [
    'pendente'  => ['label' => 'Pendente',  'class' => 'badge-pendente'],
    'concluida' => ['label' => 'Concluida', 'class' => 'badge-concluida'],
    'cancelada' => ['label' => 'Cancelada', 'class' => 'badge-cancelada'],
];

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Minhas Atividades</h2>
    <button class="btn btn-primary btn-sm" id="btnNova">+ Nova Atividade</button>
</div>

<!-- Filtros -->
<form method="GET" action="<?= base_url('agenda') ?>" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="busca" class="form-control form-control-sm"
               placeholder="Buscar..." value="<?= esc($filtros['busca'] ?? '') ?>">
    </div>
    <div class="col-auto">
        <select name="status" class="form-select form-select-sm">
            <option value="">Todos os status</option>
            <option value="pendente"  <?= ($filtros['status'] ?? '') === 'pendente'  ? 'selected' : '' ?>>Pendente</option>
            <option value="concluida" <?= ($filtros['status'] ?? '') === 'concluida' ? 'selected' : '' ?>>Concluida</option>
            <option value="cancelada" <?= ($filtros['status'] ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
        <?php if (!empty($filtros['busca']) || !empty($filtros['status'])): ?>
            <a href="<?= base_url('agenda') ?>" class="btn btn-outline-secondary btn-sm">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<?php if (empty($atividades)): ?>
    <div class="estado-vazio">
        <p class="mb-1">Nenhuma atividade encontrada.</p>
        <p class="mb-0">Clique em "+ Nova Atividade" para comecar.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Descricao</th>
                    <th>Inicio</th>
                    <th>Termino</th>
                    <th>Status</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($atividades as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= esc($a['nome']) ?></td>
                    <td><?= esc(mb_strimwidth($a['descricao'] ?? '', 0, 50, '...')) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($a['inicio'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($a['fim'])) ?></td>
                    <td>
                        <span class="<?= $statusMap[$a['status']]['class'] ?>">
                            <?= $statusMap[$a['status']]['label'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary"
                                    onclick="abrirEditar(<?= $a['id'] ?>)">Editar</button>

                            <select class="form-select form-select-sm" style="width:auto;"
                                    onchange="alterarStatus(<?= $a['id'] ?>, this.value); this.value='';">
                                <option value="">Status</option>
                                <option value="pendente">Pendente</option>
                                <option value="concluida">Concluida</option>
                                <option value="cancelada">Cancelada</option>
                            </select>

                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="excluir(<?= $a['id'] ?>, '<?= esc($a['nome']) ?>')">Excluir</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Modal: Nova / Editar -->
<div class="modal fade" id="modalAtividade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Nova Atividade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="atividadeId">

                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" id="fNome" class="form-control" placeholder="Nome da atividade">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descricao</label>
                    <textarea id="fDescricao" class="form-control" rows="3" placeholder="Opcional"></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col">
                        <label class="form-label">Inicio *</label>
                        <input type="datetime-local" id="fInicio" class="form-control">
                    </div>
                    <div class="col">
                        <label class="form-label">Termino *</label>
                        <input type="datetime-local" id="fFim" class="form-control">
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label">Status</label>
                    <select id="fStatus" class="form-select">
                        <option value="pendente">Pendente</option>
                        <option value="concluida">Concluida</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSalvar">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Confirmar exclusao -->
<div class="modal fade" id="modalConfirm" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar exclusao</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Deseja excluir a atividade <strong id="confirmNome"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarExclusao">Excluir</button>
            </div>
        </div>
    </div>
</div>

<?php
$conteudo = ob_get_clean();
echo view('layouts/main', [
    'titulo'   => 'Atividades',
    'conteudo' => $conteudo,
    'script'   => 'js/atividades.js',
]);
