<?php
/**
 * Lógica compartilhada pelos CRUDs que só possuem "nome"
 * (Nacionalidade, Categoria e Idioma).
 *
 * Variáveis esperadas (definidas pela página que inclui este arquivo):
 *   $classe  -> nome da classe do model (ex.: 'Nacionalidade')
 *   $menu    -> item do menu ativo
 *   $pagina  -> arquivo da página (ex.: 'nacionalidades.php')
 */

/** @var class-string<CadastroSimples> $classe */
$singular = $classe::nomeSingular();
$g = $classe::genero(); // 'a' ou 'o'
$uma = $g === 'a' ? 'uma' : 'um';
$esta = $g === 'a' ? 'esta' : 'este';
$erros = [];
$editando = null;
$nome = '';

// ---------- Ações enviadas por POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id = lerId($_POST);

    if ($acao === 'excluir' && $id) {
        try {
            $classe::excluir($id);
            flash('success', "{$singular} excluíd{$g} com sucesso.");
        } catch (mysqli_sql_exception $ex) {
            if (!erroRegistroEmUso($ex)) {
                throw $ex;
            }
            flash('warning', "Não é possível excluir: {$esta} " . mb_strtolower($singular) . " está sendo usad{$g} por outros cadastros.");
        }
        redirecionar($pagina);
    }

    if ($acao === 'salvar') {
        $nome = trim($_POST['nome'] ?? '');
        $erros = $classe::validar($nome);

        if (!$erros) {
            try {
                if ($id) {
                    $classe::atualizar($id, $nome);
                    flash('success', "{$singular} atualizad{$g} com sucesso.");
                } else {
                    $classe::inserir($nome);
                    flash('success', "{$singular} cadastrad{$g} com sucesso.");
                }
                redirecionar($pagina);
            } catch (mysqli_sql_exception $ex) {
                if (!erroDuplicado($ex)) {
                    throw $ex;
                }
                $erros[] = "Já existe {$uma} " . mb_strtolower($singular) . " com esse nome.";
            }
        }
        $editando = $id ? ['id' => $id, 'nome' => $nome] : null;
    }
}

// ---------- Edição (GET ?editar=ID) ----------
if (!$erros && ($idEditar = lerId($_GET, 'editar'))) {
    $editando = $classe::buscar($idEditar);
    if (!$editando) {
        flash('warning', "{$singular} não encontrad{$g}.");
        redirecionar($pagina);
    }
    $nome = $editando['nome'];
}

$busca = trim($_GET['busca'] ?? '');
$registros = $classe::listar($busca);
$titulo = $classe::nomePlural();

require __DIR__ . '/../../views/layout/header.php';
require __DIR__ . '/../../views/cadastro_simples.php';
require __DIR__ . '/../../views/layout/footer.php';
