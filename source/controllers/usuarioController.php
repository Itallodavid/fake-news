<?php

namespace source\controllers;

session_start();

use PDOException;
use source\config\PDOConexao;
use source\helpers\HTTPRequestHelper;
use source\helpers\HTTPResponseHelper;
use source\helpers\HTTPStatusHelper;
use source\repositories\UsuarioRepository;

$SOURCE = dirname(__DIR__);

require_once $SOURCE . '/helpers/HTTPRequestHelper.php';
require_once $SOURCE . '/helpers/HTTPResponseHelper.php';
require_once $SOURCE . '/helpers/HTTPStatusHelper.php';
require_once $SOURCE . '/helpers/SessionHelper.php';
require_once $SOURCE . '/repositories/UsuarioRepository.php';
require_once $SOURCE . '/configs/PDOConexao.php';

HTTPRequestHelper::aceitarApenasMetodos([
    HTTPRequestHelper::METHOD_POST
]);

header('Content-Type: application/json');

$ACOES_PERMITIDAS = [
    'login' => fn() => require_once __DIR__ . '/resources/usuario/login.php',
    'cadastrar' => fn() => require_once __DIR__ . '/resources/usuario/cadastrar.php',
    'logout' => fn() => require_once __DIR__ . '/resources/usuario/logout.php',
    'info' => fn() => require_once __DIR__ . '/resources/usuario/info.php'
];

$acao = $_POST['acao'] ?? null;

if(is_null($acao)) HTTPResponseHelper::badRequest(['mensagem' => 'defina um campo "acao"']);

try {
UsuarioRepository::configPDO(PDOConexao::conectar());

if(in_array($acao, array_keys($ACOES_PERMITIDAS))) call_user_func($ACOES_PERMITIDAS[$acao]);

HTTPResponseHelper::badRequest(['mensagem' => 'a ação é invalida']);

} catch(PDOException $erro){
    HTTPResponseHelper::send(['ocorreu um erro inesperado' => $erro->getMessage()], HTTPStatusHelper::ERROR_SERVER);
}