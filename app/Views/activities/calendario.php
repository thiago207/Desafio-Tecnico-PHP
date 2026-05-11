<?php
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Calendario</h2>
    <div class="d-flex gap-2">
        <a href="<?= base_url('agenda') ?>" class="btn btn-outline-secondary btn-sm">Ver lista</a>
        <button class="btn btn-primary btn-sm" id="btnNovaCal">+ Nova Atividade</button>
    </div>
</div>

<!-- Legenda -->
<div class="d-flex gap-3 mb-3" style="font-size:12px;">
    <span><span class="badge-pendente">Pendente</span></span>
    <span><span class="badge-concluida">Concluida</span></span>
    <span><span class="badge-cancelada">Cancelada</span></span>
</div>

<div class="border p-3">
    <div id="calendar"></div>
</div>

<!-- Modal: detalhe do evento -->
<div class="modal fade" id="modalEvento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evNome"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Descricao:</strong> <span id="evDesc"></span></p>
                <p><strong>Inicio:</strong>    <span id="evInicio"></span></p>
                <p><strong>Termino:</strong>   <span id="evFim"></span></p>
                <p class="mb-0"><strong>Status:</strong> <span id="evStatus"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="btnEditarEvento">Editar</button>
            </div>
        </div>
    </div>
</div>

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
                    <input type="text" id="fNome" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Descricao</label>
                    <textarea id="fDescricao" class="form-control" rows="3"></textarea>
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
                <div>
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

<script>const BASE_URL = '<?= base_url() ?>';</script>

<?php
$conteudo = ob_get_clean();
echo view('layouts/main', [
    'titulo'     => 'Calendario',
    'conteudo'   => $conteudo,
    'calendario' => true,
    'script'     => 'js/calendario.js',
]);
