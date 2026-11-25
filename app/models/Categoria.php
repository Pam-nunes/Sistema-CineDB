<?php
class Categoria extends CadastroSimples
{
    public static function tabela(): string { return 'categoria'; }
    public static function nomeSingular(): string { return 'Categoria'; }
    public static function nomePlural(): string { return 'Categorias'; }

    protected static function sqlContagemUso(): string
    {
        return '(SELECT COUNT(*) FROM filme f WHERE f.categoria_id = t.id)';
    }
}
