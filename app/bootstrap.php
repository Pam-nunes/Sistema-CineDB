<?php
/**
 * Arquivo de inicialização: carregado no topo de todas as páginas.
 */
session_start();

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/models/CadastroSimples.php';
require_once __DIR__ . '/models/Nacionalidade.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/models/Idioma.php';
require_once __DIR__ . '/models/Ator.php';
require_once __DIR__ . '/models/Filme.php';

// Mostra uma página amigável caso ocorra algum erro inesperado (ex.: banco fora do ar)
set_exception_handler(function (Throwable $ex) {
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Erro</title>'
       . '<link href="assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet"></head>'
       . '<body class="bg-light"><div class="container py-5"><div class="alert alert-danger">'
       . '<h4>Ops! Ocorreu um erro.</h4><p>Verifique se o MySQL está rodando e se o banco <code>cinedb</code> foi criado '
       . '(script em <code>database/banco.sql</code>).</p><small class="text-muted">' . e($ex->getMessage()) . '</small>'
       . '</div><a href="index.php" class="btn btn-primary">Voltar ao início</a></div></body></html>';
});
