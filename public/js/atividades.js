// public/js/atividades.js
$(function () {

    var BASE         = window.location.origin + '/';
    var modalAtiv    = new bootstrap.Modal('#modalAtividade');
    var modalConfirm = new bootstrap.Modal('#modalConfirm');
    var deletandoId  = null;

    // Abre modal para nova atividade
    $('#btnNova').on('click', function () {
        limpar();
        $('#modalTitulo').text('Nova Atividade');
        modalAtiv.show();
    });

    // Salva (criar ou editar)
    $('#btnSalvar').on('click', function () {
        var id    = $('#atividadeId').val();
        var dados = coletar();
        if (!dados) return;

        var url = id ? BASE + 'atividade/update/' + id
                     : BASE + 'atividade/store';

        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            success: function (res) {
                if (res.ok) {
                    modalAtiv.hide();
                    mostrarMsg(res.msg, 'sucesso');
                    setTimeout(function () { location.reload(); }, 700);
                } else {
                    mostrarMsg(res.msg, 'erro');
                }
            },
            error: function () {
                mostrarMsg('Erro de comunicacao com o servidor.', 'erro');
            }
        });
    });

    // Confirmar exclusao
    $('#btnConfirmarExclusao').on('click', function () {
        if (!deletandoId) return;
        $.ajax({
            url: BASE + 'atividade/destroy/' + deletandoId,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({}),
            success: function (res) {
                modalConfirm.hide();
                if (res.ok) {
                    mostrarMsg(res.msg, 'sucesso');
                    setTimeout(function () { location.reload(); }, 700);
                } else {
                    mostrarMsg(res.msg, 'erro');
                }
            }
        });
    });

    function limpar() {
        $('#atividadeId').val('');
        $('#fNome').val('');
        $('#fDescricao').val('');
        $('#fInicio').val('');
        $('#fFim').val('');
        $('#fStatus').val('pendente');
    }

    function coletar() {
        var nome   = $('#fNome').val().trim();
        var inicio = $('#fInicio').val();
        var fim    = $('#fFim').val();

        if (!nome)           { mostrarMsg('Informe o nome da atividade.', 'erro'); return null; }
        if (!inicio || !fim) { mostrarMsg('Informe inicio e termino.', 'erro'); return null; }
        if (new Date(fim) <= new Date(inicio)) {
            mostrarMsg('O termino deve ser posterior ao inicio.', 'erro');
            return null;
        }

        return {
            nome:      nome,
            descricao: $('#fDescricao').val().trim(),
            inicio:    inicio,
            fim:       fim,
            status:    $('#fStatus').val()
        };
    }

    // Funcoes chamadas nos botoes da tabela (inline onclick)
    window.abrirEditar = function (id) {
        $.getJSON(BASE + 'atividade/' + id, function (res) {
            if (!res.ok) { mostrarMsg(res.msg, 'erro'); return; }
            var a = res.atividade;
            $('#atividadeId').val(a.id);
            $('#fNome').val(a.nome);
            $('#fDescricao').val(a.descricao);
            $('#fInicio').val(paraInput(a.inicio));
            $('#fFim').val(paraInput(a.fim));
            $('#fStatus').val(a.status);
            $('#modalTitulo').text('Editar Atividade');
            modalAtiv.show();
        });
    };

    window.excluir = function (id, nome) {
        deletandoId = id;
        $('#confirmNome').text(nome);
        modalConfirm.show();
    };

    window.alterarStatus = function (id, status) {
        if (!status) return;
        $.ajax({
            url: BASE + 'atividade/status/' + id,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ status: status }),
            success: function (res) {
                if (res.ok) {
                    mostrarMsg(res.msg, 'sucesso');
                    setTimeout(function () { location.reload(); }, 600);
                } else {
                    mostrarMsg(res.msg, 'erro');
                }
            }
        });
    };

});
