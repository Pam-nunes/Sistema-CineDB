<?php
/** Variáveis: $totais */
$cards = [
    ['filmes.php', 'Filmes', $totais['filmes'], 'bi-film', 'primary'],
    ['atores.php', 'Atores', $totais['atores'], 'bi-person-video2', 'success'],
    ['nacionalidades.php', 'Nacionalidades', $totais['nacionalidades'], 'bi-flag', 'warning'],
    ['categorias.php', 'Categorias', $totais['categorias'], 'bi-tags', 'info'],
    ['idiomas.php', 'Idiomas', $totais['idiomas'], 'bi-translate', 'danger'],
];
?>
<section class="hero p-4 p-md-5 mb-4 shadow">
    <h1 class="display-6 fw-bold"><i class="bi bi-camera-reels"></i> Bem-vindo ao CineDB</h1>
    <p class="lead mb-4">Gerencie filmes, atores e seus elencos em um só lugar.</p>
    <a href="filme_form.php" class="btn btn-warning me-2"><i class="bi bi-plus-lg"></i> Novo filme</a>
    <a href="ator_form.php" class="btn btn-outline-light me-2"><i class="bi bi-plus-lg"></i> Novo ator</a>
    <a href="elenco.php" class="btn btn-outline-light"><i class="bi bi-people"></i> Ver atores & filmes</a>
</section>

<div class="row g-3">
    <?php foreach ($cards as [$url, $rotulo, $total, $icone, $cor]): ?>
        <div class="col-6 col-md">
            <a href="<?= $url ?>" class="text-decoration-none">
                <div class="card card-stat shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0 fw-bold text-dark"><?= e($total) ?></div>
                            <div class="text-muted small"><?= $rotulo ?></div>
                        </div>
                        <i class="bi <?= $icone ?> icone text-<?= $cor ?>"></i>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
