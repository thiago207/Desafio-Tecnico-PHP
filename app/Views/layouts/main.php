<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Agenda' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php if (!empty($calendario)): ?>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('agenda') ?>">Agenda</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === 'agenda' ? 'active' : '' ?>"
                       href="<?= base_url('agenda') ?>">Atividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === 'agenda/calendario' ? 'active' : '' ?>"
                       href="<?= base_url('agenda/calendario') ?>">Calendario</a>
                </li>
            </ul>
            <span class="navbar-text me-3"><?= esc(session()->get('usuario_login')) ?></span>
            <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-light">Sair</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?= $conteudo ?>
</div>

<div id="msg"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($calendario)): ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/pt-br.global.min.js"></script>
<?php endif; ?>
<script src="<?= base_url('js/app.js') ?>"></script>
<?php if (!empty($script)): ?>
<script src="<?= base_url($script) ?>"></script>
<?php endif; ?>

</body>
</html>
