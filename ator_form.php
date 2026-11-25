<?php
require_once __DIR__ . '/app/bootstrap.php';

$id = lerId($_GET) ?? lerId($_POST);
$erros = [];
$ator = ['nome' => '', 'sobrenome' => '', 'data_nascimento' => '', 'sexo' => '', 'nacionalidade_id' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$ator, $erros] = Ator::validar($_POST);
    if (!$erros) {
        if ($id) {
            Ator::atualizar($id, $ator);
            flash('success', 'Ator atualizado com sucesso.');
        } else {
            Ator::inserir($ator);
            flash('success', 'Ator cadastrado com sucesso.');
        }
        redirecionar('atores.php');
    }
} elseif ($id) {
    $ator = Ator::buscar($id);
    if (!$ator) {
        flash('warning', 'Ator não encontrado.');
        redirecionar('atores.php');
    }
}

$nacionalidades = Nacionalidade::opcoes();

$titulo = $id ? 'Editar ator' : 'Novo ator';
$menu = 'atores';
require __DIR__ . '/views/layout/header.php';
require __DIR__ . '/views/atores/form.php';
require __DIR__ . '/views/layout/footer.php';
