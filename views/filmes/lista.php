<?php /** Variáveis: $filmes, $busca */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Filmes</h1>
    <a href="filme_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Novo filme</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="search" name="busca" class="form-control" placeholder="Buscar filme por título..." value="<?= e($busca) ?>">
                <button class="btn btn-outline-primary">Filtrar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Título</th><th>Ano</th><th>Categoria</th><th>Idioma</th><th>Nacionalidade</th><th>Classif.</th><th>Elenco</th><th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($filmes as $f): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($f['titulo']) ?></td>
                        <td><?= e($f['ano_lancamento']) ?></td>
                        <td><?= e($f['categoria']) ?></td>
                        <td><?= e($f['idioma']) ?></td>
                        <td><?= $f['nacionalidade'] ? e($f['nacionalidade']) : '<span class="text-muted">—</span>' ?></td>
                        <td><span class="badge bg-<?= corClassificacao($f['classificacao']) ?>"><?= e($f['classificacao']) ?></span></td>
                        <td>
                            <a href="elenco.php?filme=<?= urlencode($f['titulo']) ?>" class="badge rounded-pill text-bg-primary text-decoration-none">
                                <?= e($f['total_atores']) ?>
                            </a>
                        </td>
                        <td class="acoes text-end">
                            <a href="filme_form.php?id=<?= e($f['id']) ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <?php $idExcluir = $f['id']; $nomeExcluir = $f['titulo']; require __DIR__ . '/../botao_excluir.php'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$filmes): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Nenhum filme encontrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
