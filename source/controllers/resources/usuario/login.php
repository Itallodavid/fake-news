<?php

use source\helpers\HTTPResponseHelper;
use source\helpers\HTTPStatusHelper;
use source\repositories\UsuarioRepository;
use source\helpers\SessionHelper;

$input = filter_input_array(INPUT_POST, [
    'email' => FILTER_VALIDATE_EMAIL,
    'senha' => FILTER_DEFAULT
]);

if(SessionHelper::possuiSessaoAtiva()) HTTPResponseHelper::methodNotAllowed(['mensagem' => 'você já esta autenticado']);

if($input['email'] && $input['senha']){
    $email = $input['email'];
    $senha = $input['senha'];

    $user = UsuarioRepository::selectByEmail($email);

    if(!$user) HTTPResponseHelper::send(['mensagem' => 'usuario não foi encontrado'], HTTPStatusHelper::NOT_FOUND);

    $comparar_senha = password_verify($senha, $user['senha']);

    if(!$comparar_senha) HTTPResponseHelper::methodNotAllowed(['mensagem' => 'não foi possivel efetuar a autenticação']);

    SessionHelper::criarSessao($user['id'], $user['nome'], $user['permissao']);
    HTTPResponseHelper::ok(SessionHelper::obterInfoSessao());
}

HTTPResponseHelper::badRequest(['mensagem' => 'parametros invalidos']);