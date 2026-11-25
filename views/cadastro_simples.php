<?php
/**
 * Tela única (formulário + listagem) para Nacionalidade, Categoria e Idioma.
 * Variáveis: $classe, $pagina, $titulo, $singular, $editando, $nome, $erros, $registros, $busca
 */
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= e($titulo) ?></h1>
    <span class="badge text-bg-secondary"><?= count($registros) ?> registro(s)</span>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <?= $editando ? '<i class="bi bi-pencil"></i> Editar ' : '<i class="bi bi-plus-circle"></i> Nov' . $g . ' ' ?><?= e(mb_strtolower($singular)) ?>
            </div>
            <div class="card-body">
                <?php require __DIR__ . '/erros_form.php'; ?>
                <form method="post" action="<?= e($pagina) ?>">
                    <input type="hidden" name="acao" value="salvar">
                    <input type="hidden" name="id" value="<?= e($editando['id'] ?? '') ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="60" required autofocus
                               value="<?= e($nome) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
                    <?php if ($editando): ?>
                        <a href="<?= e($pagina) ?>" class="btn btn-outline-secondary">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="get" class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" name="busca" class="form-control" placeholder="Buscar por nome..." value="<?= e($busca) ?>">
                        <button class="btn btn-outline-primary">Filtrar</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>#</th><th>Nome</th><th>Em uso</th><th class="text-end">Ações</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($registros as $r): ?>
                            <tr class="<?= ($editando['id'] ?? null) == $r['id'] ? 'table-warning' : '' ?>">
                                <td class="text-muted"><?= e($r['id']) ?></td>
                                <td><?= e($r['nome']) ?></td>
                                <td><span class="badge rounded-pill text-bg-light border"><?= e($r['total_uso']) ?></span></td>
                                <td class="acoes text-end">
                                    <a href="<?= e($pagina) ?>?editar=<?= e($r['id']) ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <?php $idExcluir = $r['id']; $nomeExcluir = $r['nome']; require __DIR__ . '/botao_excluir.php'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$registros): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Nenhum registro encontrado.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
