<?php
require_once __DIR__ . '/app/bootstrap.php';

// Exclusão (POST vindo do modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
    if ($id = lerId($_POST)) {
        Filme::excluir($id);
        flash('success', 'Filme excluído com sucesso.');
    }
    redirecionar('filmes.php');
}

$busca = trim($_GET['busca'] ?? '');
$filmes = Filme::listar($busca);

$titulo = 'Filmes';
$menu = 'filmes';
require __DIR__ . '/views/layout/header.php';
require __DIR__ . '/views/filmes/lista.php';
require __DIR__ . '/views/layout/footer.php';
