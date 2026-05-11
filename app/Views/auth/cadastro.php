<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>

<div class="auth-card">
    <h2>Criar conta</h2>

    <?php if (session()->getFlashdata('erro')): ?>
        <div class="alert alert-danger py-2"><?= session()->getFlashdata('erro') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('cadastro') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="login" class="form-label">Login</label>
            <input type="text" name="login" id="login" class="form-control"
                   value="<?= old('login') ?>" minlength="3" required autofocus>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" name="senha" id="senha" class="form-control"
                   minlength="6" required>
        </div>

        <div class="mb-3">
            <label for="confirmar" class="form-label">Confirmar senha</label>
            <input type="password" name="confirmar" id="confirmar" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
    </form>

    <p class="text-center mt-3 mb-0" style="font-size:13px;">
        Ja tem conta? <a href="<?= base_url('login') ?>">Fazer login</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
