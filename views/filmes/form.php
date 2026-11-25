<?php /** Variáveis: $id, $filme, $erros, $categorias, $idiomas, $nacionalidades, $atores */ ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="filmes.php">Filmes</a></li>
        <li class="breadcrumb-item active"><?= $id ? 'Editar' : 'Novo' ?></li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h1 class="h4 mb-0"><?= $id ? 'Editar filme' : 'Novo filme' ?></h1>
    </div>
    <div class="card-body">
        <?php require __DIR__ . '/../erros_form.php'; ?>

        <form method="post" action="filme_form.php<?= $id ? '?id=' . e($id) : '' ?>">
            <div class="row g-3">
                <div class="col-md-9">
                    <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                    <input type="text" id="titulo" name="titulo" class="form-control" maxlength="150" required value="<?= e($filme['titulo']) ?>">
                </div>
                <div class="col-md-3">
                    <label for="ano_lancamento" class="form-label">Ano de lançamento <span class="text-danger">*</span></label>
                    <!-- Requisito 2.7: aceita apenas números -->
                    <input type="text" id="ano_lancamento" name="ano_lancamento" class="form-control so-numeros"
                           inputmode="numeric" pattern="[0-9]{4}" maxlength="4" placeholder="Ex.: 2024"
                           title="Digite apenas números (4 dígitos)" required value="<?= e($filme['ano_lancamento']) ?>">
                </div>
                <div class="col-12">
                    <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                    <textarea id="descricao" name="descricao" class="form-control" rows="3" required><?= e($filme['descricao']) ?></textarea>
                </div>
                <div class="col-md-3">
                    <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                    <select id="categoria_id" name="categoria_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= e($c['id']) ?>" <?= (string) $filme['categoria_id'] === (string) $c['id'] ? 'selected' : '' ?>><?= e($c['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="idioma_id" class="form-label">Idioma <span class="text-danger">*</span></label>
                    <select id="idioma_id" name="idioma_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($idiomas as $i): ?>
                            <option value="<?= e($i['id']) ?>" <?= (string) $filme['idioma_id'] === (string) $i['id'] ? 'selected' : '' ?>><?= e($i['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="classificacao" class="form-label">Classificação indicativa <span class="text-danger">*</span></label>
                    <select id="classificacao" name="classificacao" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach (CLASSIFICACOES as $valor => $rotulo): ?>
                            <option value="<?= $valor ?>" <?= (string) $filme['classificacao'] === (string) $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="nacionalidade_id" class="form-label">Nacionalidade <small class="text-muted">(opcional)</small></label>
                    <select id="nacionalidade_id" name="nacionalidade_id" class="form-select">
                        <option value="">Não informada</option>
                        <?php foreach ($nacionalidades as $n): ?>
                            <option value="<?= e($n['id']) ?>" <?= (string) $filme['nacionalidade_id'] === (string) $n['id'] ? 'selected' : '' ?>><?= e($n['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Elenco <small class="text-muted">(selecione os atores do filme)</small></label>
                    <input type="search" id="buscaElenco" class="form-control form-control-sm mb-2" placeholder="Filtrar atores...">
                    <div class="lista-atores">
                        <div class="row">
                        <?php foreach ($atores as $a): ?>
                            <div class="col-md-4 item-ator">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="atores[]" value="<?= e($a['id']) ?>" id="ator<?= e($a['id']) ?>"
                                        <?= in_array($a['id'], $filme['atores']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ator<?= e($a['id']) ?>"><?= e($a['nome']) ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (!$atores): ?>
                            <div class="col text-muted">Nenhum ator cadastrado. <a href="ator_form.php">Cadastrar ator</a></div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
            <a href="filmes.php" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>
