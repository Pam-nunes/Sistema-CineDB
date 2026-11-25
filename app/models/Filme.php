<?php
/**
 * Acesso a dados da tabela "filme" e do vínculo filme_ator.
 */
class Filme
{
    public static function listar(string $busca = ''): array
    {
        $sql = "SELECT f.id, f.titulo, f.ano_lancamento, f.classificacao,
                       c.nome AS categoria, i.nome AS idioma, n.nome AS nacionalidade,
                       (SELECT COUNT(*) FROM filme_ator fa WHERE fa.filme_id = f.id) AS total_atores
                FROM filme f
                INNER JOIN categoria c ON c.id = f.categoria_id
                INNER JOIN idioma i ON i.id = f.idioma_id
                LEFT JOIN nacionalidade n ON n.id = f.nacionalidade_id
                WHERE f.titulo LIKE ?
                ORDER BY f.titulo";
        $stmt = Database::conexao()->prepare($sql);
        $filtro = '%' . $busca . '%';
        $stmt->bind_param('s', $filtro);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function buscar(int $id): ?array
    {
        $stmt = Database::conexao()->prepare(
            "SELECT id, titulo, descricao, ano_lancamento, classificacao,
                    categoria_id, idioma_id, nacionalidade_id
             FROM filme WHERE id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $filme = $stmt->get_result()->fetch_assoc();
        if (!$filme) {
            return null;
        }
        $filme['atores'] = self::idsAtores($id);
        return $filme;
    }

    /** IDs dos atores vinculados a um filme. */
    public static function idsAtores(int $filmeId): array
    {
        $stmt = Database::conexao()->prepare("SELECT ator_id FROM filme_ator WHERE filme_id = ?");
        $stmt->bind_param('i', $filmeId);
        $stmt->execute();
        return array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'ator_id');
    }

    public static function inserir(array $d): int
    {
        $db = Database::conexao();
        $db->begin_transaction();
        try {
            $stmt = $db->prepare(
                "INSERT INTO filme (titulo, descricao, ano_lancamento, classificacao, categoria_id, idioma_id, nacionalidade_id)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('ssisiii', $d['titulo'], $d['descricao'], $d['ano_lancamento'], $d['classificacao'],
                $d['categoria_id'], $d['idioma_id'], $d['nacionalidade_id']);
            $stmt->execute();
            $id = $db->insert_id;

            self::salvarAtores($id, $d['atores']);
            $db->commit();
            return $id;
        } catch (Throwable $ex) {
            $db->rollback();
            throw $ex;
        }
    }

    public static function atualizar(int $id, array $d): void
    {
        $db = Database::conexao();
        $db->begin_transaction();
        try {
            $stmt = $db->prepare(
                "UPDATE filme SET titulo = ?, descricao = ?, ano_lancamento = ?, classificacao = ?,
                        categoria_id = ?, idioma_id = ?, nacionalidade_id = ?
                 WHERE id = ?"
            );
            $stmt->bind_param('ssisiiii', $d['titulo'], $d['descricao'], $d['ano_lancamento'], $d['classificacao'],
                $d['categoria_id'], $d['idioma_id'], $d['nacionalidade_id'], $id);
            $stmt->execute();

            self::salvarAtores($id, $d['atores']);
            $db->commit();
        } catch (Throwable $ex) {
            $db->rollback();
            throw $ex;
        }
    }

    public static function excluir(int $id): void
    {
        // Os vínculos em filme_ator são removidos pelo ON DELETE CASCADE
        $stmt = Database::conexao()->prepare("DELETE FROM filme WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    /** Substitui o elenco do filme pelos atores informados (relação N:N). */
    private static function salvarAtores(int $filmeId, array $atores): void
    {
        $db = Database::conexao();

        $del = $db->prepare("DELETE FROM filme_ator WHERE filme_id = ?");
        $del->bind_param('i', $filmeId);
        $del->execute();

        if (!$atores) {
            return;
        }
        $ins = $db->prepare("INSERT INTO filme_ator (filme_id, ator_id) VALUES (?, ?)");
        foreach ($atores as $atorId) {
            $ins->bind_param('ii', $filmeId, $atorId);
            $ins->execute();
        }
    }

    /** Valida e normaliza os dados do formulário. Retorna [dados, erros]. */
    public static function validar(array $post): array
    {
        $d = [
            'titulo'           => trim($post['titulo'] ?? ''),
            'descricao'        => trim($post['descricao'] ?? ''),
            'ano_lancamento'   => trim($post['ano_lancamento'] ?? ''),
            'classificacao'    => $post['classificacao'] ?? '',
            'categoria_id'     => $post['categoria_id'] ?? '',
            'idioma_id'        => $post['idioma_id'] ?? '',
            'nacionalidade_id' => $post['nacionalidade_id'] ?? '',
            'atores'           => (array) ($post['atores'] ?? []),
        ];
        $erros = [];

        if ($d['titulo'] === '' || mb_strlen($d['titulo']) > 150) {
            $erros[] = 'Informe o título (máx. 150 caracteres).';
        }
        if ($d['descricao'] === '') {
            $erros[] = 'Informe a descrição.';
        }

        // Requisito 2.7: campo numérico aceita apenas números
        $anoMax = (int) date('Y') + 5;
        if (!apenasNumeros($d['ano_lancamento'])) {
            $erros[] = 'O ano de lançamento deve conter apenas números.';
        } elseif ((int) $d['ano_lancamento'] < 1888 || (int) $d['ano_lancamento'] > $anoMax) {
            $erros[] = "O ano de lançamento deve estar entre 1888 e {$anoMax}.";
        } else {
            $d['ano_lancamento'] = (int) $d['ano_lancamento'];
        }

        if (!array_key_exists($d['classificacao'], CLASSIFICACOES)) {
            $erros[] = 'Selecione a classificação indicativa.';
        }
        if (!apenasNumeros($d['categoria_id']) || !Categoria::buscar((int) $d['categoria_id'])) {
            $erros[] = 'Selecione a categoria.';
        } else {
            $d['categoria_id'] = (int) $d['categoria_id'];
        }
        if (!apenasNumeros($d['idioma_id']) || !Idioma::buscar((int) $d['idioma_id'])) {
            $erros[] = 'Selecione o idioma.';
        } else {
            $d['idioma_id'] = (int) $d['idioma_id'];
        }

        // Nacionalidade é opcional para o filme
        if ($d['nacionalidade_id'] === '') {
            $d['nacionalidade_id'] = null;
        } elseif (!apenasNumeros($d['nacionalidade_id']) || !Nacionalidade::buscar((int) $d['nacionalidade_id'])) {
            $erros[] = 'Nacionalidade inválida.';
        } else {
            $d['nacionalidade_id'] = (int) $d['nacionalidade_id'];
        }

        // Elenco: mantém apenas IDs numéricos válidos
        $idsValidos = array_map('intval', array_column(Ator::opcoes(), 'id'));
        $d['atores'] = array_values(array_unique(array_map('intval', array_filter(
            $d['atores'],
            fn($id) => apenasNumeros($id) && in_array((int) $id, $idsValidos, true)
        ))));

        return [$d, $erros];
    }
}
