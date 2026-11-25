<?php /** Botão que abre o modal de exclusão. Espera $idExcluir e $nomeExcluir. */ ?>
<button type="button" class="btn btn-sm btn-outline-danger" title="Excluir"
        data-bs-toggle="modal" data-bs-target="#modalExcluir"
        data-id="<?= e($idExcluir) ?>" data-nome="<?= e($nomeExcluir) ?>">
    <i class="bi bi-trash"></i>
</button>
