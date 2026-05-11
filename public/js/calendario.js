// public/js/calendario.js
$(function () {

    var eventoAtivoId = null;
    var modalAtiv  = new bootstrap.Modal('#modalAtividade');
    var modalEvt   = new bootstrap.Modal('#modalEvento');

    var cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
        locale: 'pt-br',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,listWeek'
        },
        height: 'auto',

        // Carrega eventos via AJAX
        events: function (info, ok, fail) {
            $.getJSON(BASE_URL + 'agenda/eventos', {
                start: info.startStr.substring(0, 10),
                end:   info.endStr.substring(0, 10)
            }, function (data) { ok(data); }).fail(fail);
        },

        // Clique em evento: abre detalhe
        eventClick: function (info) {
            var e = info.event;
            var p = e.extendedProps;
            eventoAtivoId = e.id;
            $('#evNome').text(e.title);
            $('#evDesc').text(p.descricao || '(sem descricao)');
            $('#evInicio').text(formatar(e.startStr));
            $('#evFim').text(formatar(e.endStr));
            $('#evStatus').text(p.status);
            modalEvt.show();
        },

        // Clique em dia: abre modal com data preenchida
        dateClick: function (info) {
            limpar();
            $('#fInicio').val(info.dateStr + 'T09:00');
            $('#fFim').val(info.dateStr + 'T10:00');
            $('#modalTitulo').text('Nova Atividade');
            modalAtiv.show();
        }
    });

    cal.render();

    // Botao nova atividade no topo
    $('#btnNovaCal').on('click', function () {
        limpar();
        $('#modalTitulo').text('Nova Atividade');
        modalAtiv.show();
    });

    // Editar a partir do modal de detalhe
    $('#btnEditarEvento').on('click', function () {
        if (!eventoAtivoId) return;
        modalEvt.hide();
        $.getJSON(BASE_URL + 'atividade/' + eventoAtivoId, function (res) {
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
    });

    // Salvar atividade
    $('#btnSalvar').on('click', function () {
        var id    = $('#atividadeId').val();
        var dados = coletar();
        if (!dados) return;

        var url = id ? BASE_URL + 'atividade/update/' + id
                     : BASE_URL + 'atividade/store';

        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            success: function (res) {
                if (res.ok) {
                    modalAtiv.hide();
                    mostrarMsg(res.msg, 'sucesso');
                    cal.refetchEvents();
                } else {
                    mostrarMsg(res.msg, 'erro');
                }
            },
            error: function () {
                mostrarMsg('Erro de comunicacao com o servidor.', 'erro');
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

});
