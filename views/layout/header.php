<?php
/** @var string $titulo  Título da página */
/** @var string $menu    Item ativo do menu */
$menu = $menu ?? '';
$itensMenu = [
    'inicio'         => ['index.php', 'Início', 'bi-house'],
    'filmes'         => ['filmes.php', 'Filmes', 'bi-film'],
    'atores'         => ['atores.php', 'Atores', 'bi-person-video2'],
    'elenco'         => ['elenco.php', 'Atores & Filmes', 'bi-people'],
];
$itensCadastros = [
    'nacionalidades' => ['nacionalidades.php', 'Nacionalidades', 'bi-flag'],
    'categorias'     => ['categorias.php', 'Categorias', 'bi-tags'],
    'idiomas'        => ['idiomas.php', 'Idiomas', 'bi-translate'],
];
$cadastroAtivo = array_key_exists($menu, $itensCadastros);
$flash = pegarFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo ?? 'CineDB') ?> · CineDB</title>
    <link href="assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-cine shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-camera-reels-fill text-warning"></i> CineDB</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($itensMenu as $chave => [$url, $rotulo, $icone]): ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $menu === $chave ? ' active' : '' ?>" href="<?= $url ?>">
                            <i class="bi <?= $icone ?>"></i> <?= $rotulo ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?= $cadastroAtivo ? ' active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Cadastros
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php foreach ($itensCadastros as $chave => [$url, $rotulo, $icone]): ?>
                            <li><a class="dropdown-item<?= $menu === $chave ? ' active' : '' ?>" href="<?= $url ?>"><i class="bi <?= $icone ?>"></i> <?= $rotulo ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['tipo']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['mensagem']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>
