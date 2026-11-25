<?php /** Variáveis: $id, $ator, $erros, $nacionalidades */ ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="atores.php">Atores</a></li>
        <li class="breadcrumb-item active"><?= $id ? 'Editar' : 'Novo' ?></li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h1 class="h4 mb-0"><?= $id ? 'Editar ator' : 'Novo ator' ?></h1>
    </div>
    <div class="card-body">
        <?php require __DIR__ . '/../erros_form.php'; ?>

        <form method="post" action="ator_form.php<?= $id ? '?id=' . e($id) : '' ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                    <input type="text" id="nome" name="nome" class="form-control" maxlength="60" required value="<?= e($ator['nome']) ?>">
                </div>
                <div class="col-md-6">
                    <label for="sobrenome" class="form-label">Sobrenome <span class="text-danger">*</span></label>
                    <input type="text" id="sobrenome" name="sobrenome" class="form-control" maxlength="80" required value="<?= e($ator['sobrenome']) ?>">
                </div>
                <div class="col-md-4">
                    <label for="data_nascimento" class="form-label">Data de nascimento <span class="text-danger">*</span></label>
                    <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required
                           max="<?= date('Y-m-d') ?>" value="<?= e($ator['data_nascimento']) ?>">
                </div>
                <div class="col-md-4">
                    <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                    <select id="sexo" name="sexo" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach (SEXOS as $valor => $rotulo): ?>
                            <option value="<?= $valor ?>" <?= $ator['sexo'] === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="nacionalidade_id" class="form-label">Nacionalidade <span class="text-danger">*</span></label>
                    <select id="nacionalidade_id" name="nacionalidade_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($nacionalidades as $n): ?>
                            <option value="<?= e($n['id']) ?>" <?= (string) $ator['nacionalidade_id'] === (string) $n['id'] ? 'selected' : '' ?>><?= e($n['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text"><a href="nacionalidades.php">Gerenciar nacionalidades</a></div>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
            <a href="atores.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>
