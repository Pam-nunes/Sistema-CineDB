<?php
require_once __DIR__ . '/app/bootstrap.php';

// Exclusão (POST vindo do modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
    if ($id = lerId($_POST)) {
        Ator::excluir($id);
        flash('success', 'Ator excluído com sucesso.');
    }
    redirecionar('atores.php');
}

$busca = trim($_GET['busca'] ?? '');
$atores = Ator::listar($busca);

$titulo = 'Atores';
$menu = 'atores';
require __DIR__ . '/views/layout/header.php';
require __DIR__ . '/views/atores/lista.php';
require __DIR__ . '/views/layout/footer.php';
