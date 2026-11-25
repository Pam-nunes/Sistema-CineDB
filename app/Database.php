<?php
/**
 * Conexão única (Singleton) com o MySQL usando MySQLi orientado a objeto.
 */
class Database
{
    private static ?mysqli $conexao = null;

    public static function conexao(): mysqli
    {
        if (self::$conexao === null) {
            $cfg = require __DIR__ . '/../config/config.php';

            // Faz o MySQLi lançar exceções em caso de erro
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            self::$conexao = new mysqli(
                $cfg['db_host'],
                $cfg['db_usuario'],
                $cfg['db_senha'],
                $cfg['db_nome'],
                $cfg['db_porta']
            );
            self::$conexao->set_charset('utf8mb4');
        }
        return self::$conexao;
    }
}
