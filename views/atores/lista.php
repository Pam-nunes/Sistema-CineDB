<?php /** Variáveis: $atores, $busca */ ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Atores</h1>
    <a href="ator_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Novo ator</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="search" name="busca" class="form-control" placeholder="Buscar ator por nome..." value="<?= e($busca) ?>">
                <button class="btn btn-outline-primary">Filtrar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nome</th><th>Nascimento</th><th>Sexo</th><th>Nacionalidade</th><th>Filmes</th><th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($atores as $a): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($a['nome'] . ' ' . $a['sobrenome']) ?></td>
                        <td><?= e(dataBr($a['data_nascimento'])) ?></td>
                        <td><?= e(rotuloSexo($a['sexo'])) ?></td>
                        <td><?= e($a['nacionalidade']) ?></td>
                        <td>
                            <a href="elenco.php?ator=<?= urlencode($a['nome'] . ' ' . $a['sobrenome']) ?>" class="badge rounded-pill text-bg-primary text-decoration-none">
                                <?= e($a['total_filmes']) ?>
                            </a>
                        </td>
                        <td class="acoes text-end">
                            <a href="ator_form.php?id=<?= e($a['id']) ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <?php $idExcluir = $a['id']; $nomeExcluir = $a['nome'] . ' ' . $a['sobrenome']; require __DIR__ . '/../botao_excluir.php'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$atores): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Nenhum ator encontrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
