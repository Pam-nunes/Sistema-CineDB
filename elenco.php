<?php
/**
 * Requisito 2.6: página separada com os atores e os filmes em que atuam,
 * com filtro por nome do ator e por título do filme.
 */
require_once __DIR__ . '/app/bootstrap.php';

$filtroAtor = trim($_GET['ator'] ?? '');
$filtroFilme = trim($_GET['filme'] ?? '');
$atores = Ator::comFilmes($filtroAtor, $filtroFilme);

$titulo = 'Atores & Filmes';
$menu = 'elenco';
require __DIR__ . '/views/layout/header.php';
require __DIR__ . '/views/elenco.php';
require __DIR__ . '/views/layout/footer.php';
