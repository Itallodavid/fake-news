<?php

namespace source\model;

use source\repositories\AbstractRepository;

use const source\config\TABLE_NEWS;
use const source\config\TABLE_USER;

require_once __DIR__ . '/AbstractRepository.php';

final class NoticiaRepository extends AbstractRepository
{
    public const LIMIT_DEFAULT = 1000;

    // public const QUERY_SELECT_BY_PAGE = 'SELECT * FROM ' . TABLE_NEWS . ' LIMIT ? OFFSET ?';
    public const QUERY_SELECT_BY_ID = 'SELECT * FROM ' . TABLE_NEWS . ' WHERE id=?';
    public const QUERY_DELETE_BY_ID = 'DELETE FROM ' . TABLE_NEWS . ' WHERE id=?';
    public const QUERY_CREATE = 'INSERT INTO ' . TABLE_NEWS . ' (autor_id, titulo, conteudo, imagem) VALUES(:autor_id, :titulo, :conteudo, :imagem)';
    public const QUERY_UPDATE_BY_ID = 'UPDATE ' . TABLE_NEWS . ' SET titulo=:titulo, conteudo=:conteudo, imagem=:imagem WHERE id=:id';

    public const QUERY_SELECT_BY_PAGE = <<<SQL
    SELECT p.id AS post_id, p.titulo, p.conteudo, p.imagem, p.created_at, p.autor_id, u.nome AS autor_nome 
    FROM %s AS p
    INNER JOIN %s as u
    ON p.autor_id=u.id ORDER BY p.updated_at DESC LIMIT :limit OFFSET :offset
    SQL;

    public const QUERY_SELECT_ALL_POSTS = <<<SQL
    SELECT p.id AS post_id, p.titulo, p.conteudo, p.imagem, p.created_at, p.autor_id, u.nome AS autor_nome 
    FROM %s AS p
    INNER JOIN %s as u
    ON p.autor_id=u.id
    SQL;

    public const QUERY_SELECT_BY_USER = <<<SQL
    SELECT p.id AS post_id, p.titulo, p.conteudo, p.imagem, p.created_at, p.autor_id, u.nome AS autor_nome
    FROM %s AS p
    INNER JOIN %s as u
    ON p.autor_id=u.id
    WHERE p.autor_id=:user_id
    LIMIT :limit OFFSET :offset
    SQL;
    
    public static function selectByUser($user_id, $pagina = 1)
    {
        $query = sprintf(self::QUERY_SELECT_BY_USER, TABLE_NEWS, TABLE_USER);
        $sql = self::prepare($query);
        $sql->execute([
            'user_id' => $user_id,
            'limit' => self::LIMIT_DEFAULT,
            'offset' => ($pagina - 1) * self::LIMIT_DEFAULT
        ]);
        return $sql->fetchAll();
    }

    public static function selectByPage($page = 1)
    {
        $page = $page > 0 ? $page - 1 : 0;
        $limit = 10;
        $query = sprintf(self::QUERY_SELECT_BY_PAGE, TABLE_NEWS, TABLE_USER);
        $sql = self::prepare($query);
        $sql->execute([
            'limit' => self::LIMIT_DEFAULT,
            'offset' => $page * $limit
        ]);
        return $sql->fetchAll();
    }

    public static function selectById($id)
    {
        $sql = self::prepare(self::QUERY_SELECT_BY_ID);
        $sql->execute([$id]);
        return $sql->fetch();
    }

    public static function selectAllPost()
    {
        $query = sprintf(self::QUERY_SELECT_ALL_POSTS, TABLE_NEWS, TABLE_USER);
        $sql = self::prepare($query);
        $sql->execute();
        return $sql->fetchAll();
    }

    public static function deleteById($id)
    {
        $sql = self::prepare(self::QUERY_DELETE_BY_ID);
        $sql->execute([$id]);
        return;
    }

    public static function updateById(array $input)
    {
        $sql = self::prepare(self::QUERY_UPDATE_BY_ID);
        return $sql->execute($input);
    }

    public static function create(array $input)
    {
        $sql = self::prepare(self::QUERY_CREATE);
        return $sql->execute($input);
    }
}