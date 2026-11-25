<?php
/** Variáveis: $atores, $filtroAtor, $filtroFilme */

/** Destaca o termo buscado dentro do texto. */
$destacar = function (string $texto, string $termo): string {
    $seguro = e($texto);
    if ($termo === '') {
        return $seguro;
    }
    return preg_replace('/(' . preg_quote(e($termo), '/') . ')/iu', '<mark>$1</mark>', $seguro);
};
?>
<h1 class="h3 mb-3">Atores & Filmes</h1>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label for="ator" class="form-label small text-muted mb-1">Nome do ator</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="search" id="ator" name="ator" class="form-control" placeholder="Ex.: Fernanda" value="<?= e($filtroAtor) ?>">
                </div>
            </div>
            <div class="col-md-5">
                <label for="filme" class="form-label small text-muted mb-1">Título do filme</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-film"></i></span>
                    <input type="search" id="filme" name="filme" class="form-control" placeholder="Ex.: Central do Brasil" value="<?= e($filtroFilme) ?>">
                </div>
            </div>
            <div class="col-md-2 d-grid gap-1">
                <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filtrar</button>
            </div>
        </form>
        <?php if ($filtroAtor !== '' || $filtroFilme !== ''): ?>
            <div class="mt-2 small">
                <?= count($atores) ?> ator(es) encontrado(s) ·
                <a href="elenco.php">Limpar filtros</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3">
    <?php foreach ($atores as $a): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card card-ator shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar"><?= e(mb_strtoupper(mb_substr($a['nome'], 0, 1))) ?></div>
                        <div>
                            <div class="fw-semibold"><?= $destacar($a['nome'], $filtroAtor) ?></div>
                            <div class="small text-muted"><i class="bi bi-flag"></i> <?= e($a['nacionalidade']) ?></div>
                        </div>
                    </div>
                    <?php if ($a['filmes']): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($a['filmes'] as $f): ?>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span><i class="bi bi-film text-muted"></i> <?= $destacar($f['titulo'], $filtroFilme) ?></span>
                                    <span class="text-muted small"><?= e($f['ano']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted small mb-0">Nenhum filme vinculado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$atores): ?>
        <div class="col-12"><div class="alert alert-light border text-center">Nenhum resultado para os filtros informados.</div></div>
    <?php endif; ?>
</div>
