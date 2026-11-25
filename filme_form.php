<?php
require_once __DIR__ . '/app/bootstrap.php';

$id = lerId($_GET) ?? lerId($_POST);
$erros = [];
$filme = [
    'titulo' => '', 'descricao' => '', 'ano_lancamento' => '', 'classificacao' => '',
    'categoria_id' => '', 'idioma_id' => '', 'nacionalidade_id' => '', 'atores' => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$filme, $erros] = Filme::validar($_POST);
    if (!$erros) {
        if ($id) {
            Filme::atualizar($id, $filme);
            flash('success', 'Filme atualizado com sucesso.');
        } else {
            Filme::inserir($filme);
            flash('success', 'Filme cadastrado com sucesso.');
        }
        redirecionar('filmes.php');
    }
} elseif ($id) {
    $filme = Filme::buscar($id);
    if (!$filme) {
        flash('warning', 'Filme não encontrado.');
        redirecionar('filmes.php');
    }
}

$categorias = Categoria::opcoes();
$idiomas = Idioma::opcoes();
$nacionalidades = Nacionalidade::opcoes();
$atores = Ator::opcoes();

$titulo = $id ? 'Editar filme' : 'Novo filme';
$menu = 'filmes';
require __DIR__ . '/views/layout/header.php';
require __DIR__ . '/views/filmes/form.php';
require __DIR__ . '/views/layout/footer.php';
