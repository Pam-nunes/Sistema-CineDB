<?php
class Nacionalidade extends CadastroSimples
{
    public static function tabela(): string { return 'nacionalidade'; }
    public static function nomeSingular(): string { return 'Nacionalidade'; }
    public static function nomePlural(): string { return 'Nacionalidades'; }

    protected static function sqlContagemUso(): string
    {
        return '((SELECT COUNT(*) FROM ator a WHERE a.nacionalidade_id = t.id)
               + (SELECT COUNT(*) FROM filme f WHERE f.nacionalidade_id = t.id))';
    }
}
