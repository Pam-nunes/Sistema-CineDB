<?php
class Idioma extends CadastroSimples
{
    public static function tabela(): string { return 'idioma'; }
    public static function nomeSingular(): string { return 'Idioma'; }
    public static function nomePlural(): string { return 'Idiomas'; }
    public static function genero(): string { return 'o'; }

    protected static function sqlContagemUso(): string
    {
        return '(SELECT COUNT(*) FROM filme f WHERE f.idioma_id = t.id)';
    }
}
