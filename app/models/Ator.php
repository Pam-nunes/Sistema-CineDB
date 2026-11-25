<?php
/**
 * Acesso a dados da tabela "ator".
 */
class Ator
{
    public static function listar(string $busca = ''): array
    {
        $sql = "SELECT a.id, a.nome, a.sobrenome, a.data_nascimento, a.sexo,
                       n.nome AS nacionalidade,
                       (SELECT COUNT(*) FROM filme_ator fa WHERE fa.ator_id = a.id) AS total_filmes
                FROM ator a
                INNER JOIN nacionalidade n ON n.id = a.nacionalidade_id
                WHERE CONCAT(a.nome, ' ', a.sobrenome) LIKE ?
                ORDER BY a.nome, a.sobrenome";
        $stmt = Database::conexao()->prepare($sql);
        $filtro = '%' . $busca . '%';
        $stmt->bind_param('s', $filtro);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /** Lista simples (id, nome completo) para preencher o formulário de filmes. */
    public static function opcoes(): array
    {
        $res = Database::conexao()->query(
            "SELECT id, CONCAT(nome, ' ', sobrenome) AS nome FROM ator ORDER BY nome, sobrenome"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function buscar(int $id): ?array
    {
        $stmt = Database::conexao()->prepare(
            "SELECT id, nome, sobrenome, data_nascimento, sexo, nacionalidade_id FROM ator WHERE id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public static function inserir(array $d): int
    {
        $db = Database::conexao();
        $stmt = $db->prepare(
            "INSERT INTO ator (nome, sobrenome, data_nascimento, sexo, nacionalidade_id) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssi', $d['nome'], $d['sobrenome'], $d['data_nascimento'], $d['sexo'], $d['nacionalidade_id']);
        $stmt->execute();
        return $db->insert_id;
    }

    public static function atualizar(int $id, array $d): void
    {
        $stmt = Database::conexao()->prepare(
            "UPDATE ator SET nome = ?, sobrenome = ?, data_nascimento = ?, sexo = ?, nacionalidade_id = ? WHERE id = ?"
        );
        $stmt->bind_param('ssssii', $d['nome'], $d['sobrenome'], $d['data_nascimento'], $d['sexo'], $d['nacionalidade_id'], $id);
        $stmt->execute();
    }

    public static function excluir(int $id): void
    {
        // Os vínculos em filme_ator são removidos pelo ON DELETE CASCADE
        $stmt = Database::conexao()->prepare("DELETE FROM ator WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    /**
     * Atores com os filmes em que atuam (requisito 2.6).
     * Filtra pelo nome do ator e/ou pelo título do filme.
     */
    public static function comFilmes(string $nomeAtor = '', string $tituloFilme = ''): array
    {
        $sql = "SELECT a.id AS ator_id, CONCAT(a.nome, ' ', a.sobrenome) AS ator,
                       n.nome AS nacionalidade,
                       f.id AS filme_id, f.titulo, f.ano_lancamento
                FROM ator a
                INNER JOIN nacionalidade n ON n.id = a.nacionalidade_id
                LEFT JOIN filme_ator fa ON fa.ator_id = a.id
                LEFT JOIN filme f ON f.id = fa.filme_id
                WHERE CONCAT(a.nome, ' ', a.sobrenome) LIKE ?";
        $tipos = 's';
        $params = ['%' . $nomeAtor . '%'];

        if ($tituloFilme !== '') {
            $sql .= " AND f.titulo LIKE ?";
            $tipos .= 's';
            $params[] = '%' . $tituloFilme . '%';
        }
        $sql .= " ORDER BY a.nome, a.sobrenome, f.ano_lancamento";

        $stmt = Database::conexao()->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        $stmt->execute();
        $linhas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Agrupa as linhas por ator
        $atores = [];
        foreach ($linhas as $l) {
            $id = $l['ator_id'];
            if (!isset($atores[$id])) {
                $atores[$id] = [
                    'id' => $id,
                    'nome' => $l['ator'],
                    'nacionalidade' => $l['nacionalidade'],
                    'filmes' => [],
                ];
            }
            if ($l['filme_id'] !== null) {
                $atores[$id]['filmes'][] = [
                    'id' => $l['filme_id'],
                    'titulo' => $l['titulo'],
                    'ano' => $l['ano_lancamento'],
                ];
            }
        }
        return array_values($atores);
    }

    /** Valida e normaliza os dados do formulário. Retorna [dados, erros]. */
    public static function validar(array $post): array
    {
        $d = [
            'nome'             => trim($post['nome'] ?? ''),
            'sobrenome'        => trim($post['sobrenome'] ?? ''),
            'data_nascimento'  => trim($post['data_nascimento'] ?? ''),
            'sexo'             => $post['sexo'] ?? '',
            'nacionalidade_id' => $post['nacionalidade_id'] ?? '',
        ];
        $erros = [];

        if ($d['nome'] === '' || mb_strlen($d['nome']) > 60) {
            $erros[] = 'Informe o nome (máx. 60 caracteres).';
        }
        if ($d['sobrenome'] === '' || mb_strlen($d['sobrenome']) > 80) {
            $erros[] = 'Informe o sobrenome (máx. 80 caracteres).';
        }
        if (!dataValida($d['data_nascimento'])) {
            $erros[] = 'Informe uma data de nascimento válida.';
        } elseif ($d['data_nascimento'] > date('Y-m-d')) {
            $erros[] = 'A data de nascimento não pode ser no futuro.';
        }
        if (!array_key_exists($d['sexo'], SEXOS)) {
            $erros[] = 'Selecione o sexo.';
        }
        if (!apenasNumeros($d['nacionalidade_id']) || !Nacionalidade::buscar((int) $d['nacionalidade_id'])) {
            $erros[] = 'Selecione a nacionalidade (obrigatória).';
        } else {
            $d['nacionalidade_id'] = (int) $d['nacionalidade_id'];
        }

        return [$d, $erros];
    }
}
