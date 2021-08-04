<?php

namespace source\repositories;

use PDO;

abstract class AbstractRepository
{
    protected static \PDO $pdo; 

    public static function configPDO(\PDO $pdo)
    {
        static::$pdo = $pdo;
    }

    public static function prepare($query,array $options = [])
    {
        return static::$pdo->prepare($query, $options);
    }
}
