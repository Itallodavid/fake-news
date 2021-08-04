<?php

namespace source\repositories;

use const source\config\TABLE_USER;

require_once __DIR__ . '/AbstractRepository.php';

final class UsuarioRepository extends AbstractRepository
{
    public const QUERY_SELECT_USER = 'SELECT id, nome, email, senha FROM ' . TABLE_USER . ' WHERE email=?';

    public const QUERY_INSERT_USER = 'INSERT INTO ' . TABLE_USER . '(email, senha, nome) VALUES (:email, :senha, :nome)';

    public static function selectByEmail($email)
    {
        $sql = self::prepare(self::QUERY_SELECT_USER);
        $sql->execute([$email]);
        return $sql->fetch();
    }

    public static function createUser(array $input)
    {
        $sql = self::prepare(self::QUERY_INSERT_USER);
        return $sql->execute($input);
    }

    private function __construct()
    {
    }
}
