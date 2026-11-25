<?php
/**
 * Classe base para cadastros que possuem apenas "nome"
 * (Nacionalidade, Categoria e Idioma).
 * Cada filha informa a tabela e os textos usados nas telas.
 */
abstract class CadastroSimples
{
    /** Nome da tabela no banco. */
    abstract public static function tabela(): string;

    /** Nome no singular (ex.: "Nacionalidade"). */
    abstract public static function nomeSingular(): string;

    /** Nome no plural (ex.: "Nacionalidades"). */
    abstract public static function nomePlural(): string;

    /** Terminação de gênero usada nas mensagens: 'a' (feminino) ou 'o' (masculino). */
    public static function genero(): string
    {
        return 'a';
    }

    public static function listar(string $busca = ''): array
    {
        $db = Database::conexao();
        $tabela = static::tabela();

        $sql = "SELECT t.id, t.nome, " . static::sqlContagemUso() . " AS total_uso
                FROM {$tabela} t
                WHERE t.nome LIKE ?
                ORDER BY t.nome";
        $stmt = $db->prepare($sql);
        $filtro = '%' . $busca . '%';
        $stmt->bind_param('s', $filtro);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /** Lista simples (id, nome) para preencher <select>. */
    public static function opcoes(): array
    {
        $tabela = static::tabela();
        $res = Database::conexao()->query("SELECT id, nome FROM {$tabela} ORDER BY nome");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function buscar(int $id): ?array
    {
        $tabela = static::tabela();
        $stmt = Database::conexao()->prepare("SELECT id, nome FROM {$tabela} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public static function inserir(string $nome): int
    {
        $db = Database::conexao();
        $tabela = static::tabela();
        $stmt = $db->prepare("INSERT INTO {$tabela} (nome) VALUES (?)");
        $stmt->bind_param('s', $nome);
        $stmt->execute();
        return $db->insert_id;
    }

    public static function atualizar(int $id, string $nome): void
    {
        $tabela = static::tabela();
        $stmt = Database::conexao()->prepare("UPDATE {$tabela} SET nome = ? WHERE id = ?");
        $stmt->bind_param('si', $nome, $id);
        $stmt->execute();
    }

    public static function excluir(int $id): void
    {
        $tabela = static::tabela();
        $stmt = Database::conexao()->prepare("DELETE FROM {$tabela} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    /** Valida os dados do formulário. Retorna lista de erros. */
    public static function validar(string $nome): array
    {
        $erros = [];
        if ($nome === '') {
            $erros[] = 'Informe o nome.';
        } elseif (mb_strlen($nome) > 60) {
            $erros[] = 'O nome deve ter no máximo 60 caracteres.';
        }
        return $erros;
    }

    /** Subconsulta que conta quantos registros usam este item. */
    abstract protected static function sqlContagemUso(): string;
}
