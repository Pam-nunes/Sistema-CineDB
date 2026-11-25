<?php /** Lista de erros de validação. Espera $erros. */ ?>
<?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
        <strong>Corrija os campos abaixo:</strong>
        <ul class="mb-0">
            <?php foreach ($erros as $erro): ?>
                <li><?= e($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
