<?php

namespace source\controllers;

session_start();

use PDOException;
use source\config\PDOConexao;
use source\helpers\HTTPRequestHelper;
use source\model\NoticiaRepository;
use source\helpers\HTTPStatusHelper;
use source\helpers\HTTPResponseHelper;

$DIR_SOURCE = dirname(__DIR__);

require_once $DIR_SOURCE . '/helpers/HTTPRequestHelper.php';
require_once $DIR_SOURCE . '/helpers/HTTPResponseHelper.php';
require_once $DIR_SOURCE . '/helpers/HTTPStatusHelper.php';
require_once $DIR_SOURCE . '/helpers/ImagemHelper.php';
require_once $DIR_SOURCE . '/helpers/SessionHelper.php';
require_once $DIR_SOURCE . '/repositories/NoticiaRepository.php';
require_once $DIR_SOURCE . '/configs/PDOConexao.php';

header("Content-Type: application/json");

$METODOS_HTTP_ACEITOS = [
    HTTPRequestHelper::METHOD_GET => fn() => require_once __DIR__ . '/resources/noticia/obterNoticia.php',
    HTTPRequestHelper::METHOD_POST => fn() => require_once __DIR__ . '/resources/noticia/criarNoticia.php',
    HTTPRequestHelper::METHOD_DELETE => fn() => require_once __DIR__ . '/resources/noticia/excluirNoticia.php'
];

$metodo_aceito = HTTPRequestHelper::aceitarApenasMetodos(array_keys($METODOS_HTTP_ACEITOS));

try {
    NoticiaRepository::configPDO(PDOConexao::conectar());
    call_user_func($METODOS_HTTP_ACEITOS[$metodo_aceito]);
} catch(PDOException $erro){
    HTTPResponseHelper::send(['ocorreu um erro inesperado' => $erro->getMessage()], HTTPStatusHelper::ERROR_SERVER);
}