<?php
require_once __DIR__ . '/app/bootstrap.php';

$totais = Database::conexao()->query(
    "SELECT (SELECT COUNT(*) FROM filme)         AS filmes,
            (SELECT COUNT(*) FROM ator)          AS atores,
            (SELECT COUNT(*) FROM nacionalidade) AS nacionalidades,
            (SELECT COUNT(*) FROM categoria)     AS categorias,
            (SELECT COUNT(*) FROM idioma)        AS idiomas"
)->fetch_assoc();

$titulo = 'Início';
$menu = 'inicio';
require __DIR__ . '/views/layout/header.php';
require __DIR__ . '/views/inicio.php';
require __DIR__ . '/views/layout/footer.php';
