// public/js/app.js

function mostrarMsg(texto, tipo) {
    var cor = tipo === 'erro' ? '#cc0000' : '#006600';
    var bg  = tipo === 'erro' ? '#fff0f0' : '#f0fff0';
    var el  = document.getElementById('msg');
    el.innerText  = texto;
    el.style.display     = 'block';
    el.style.color       = cor;
    el.style.background  = bg;
    el.style.borderColor = cor;
    setTimeout(function () { el.style.display = 'none'; }, 3000);
}

// "2026-05-08 09:00:00" -> "2026-05-08T09:00"
function paraInput(valor) {
    if (!valor) return '';
    return valor.replace(' ', 'T').substring(0, 16);
}

function formatar(iso) {
    if (!iso) return '';
    return new Date(iso).toLocaleString('pt-BR');
}
