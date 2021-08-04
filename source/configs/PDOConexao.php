<?php

namespace source\config;

use PDO;
use PDOException;

require_once __DIR__ . '/bancoDadosConfig.php';

final class PDOConexao
{
    private static PDO $pdo;

    public const CONFIG = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    /** @throws PDOException  */
    public static function conectar(): PDO
    {
        if (isset(self::$pdo)) return self::$pdo;

        $url_conexao = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s",
            DATABASE_HOST,
            DATABASE_PORT,
            DATABASE_NAME
        );

        self::$pdo = new \PDO($url_conexao, DATABASE_USERNAME, DATABASE_PASSWORD, self::CONFIG);

        return self::$pdo;
    }

    private function __construct()
    {
    }
}
