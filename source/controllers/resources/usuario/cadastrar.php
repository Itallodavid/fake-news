<?php

namespace source\controllers\resources;

use source\helpers\HTTPResponseHelper;
use source\helpers\HTTPStatusHelper;
use source\repositories\UsuarioRepository;

$input = filter_input_array(INPUT_POST, [
    'email' => FILTER_VALIDATE_EMAIL,
    'senha' => FILTER_DEFAULT,
    'nome' => FILTER_DEFAULT
]);

if($input['email'] && $input['senha'] && $input['nome']){
    $email = $input['email'];
    $input['senha'] = password_hash($input['senha'], PASSWORD_DEFAULT);

    $user = UsuarioRepository::selectByEmail($email);

    if($user) HTTPResponseHelper::methodNotAllowed(['mensagem' => 'já existe um usuario com este email']);

    if(UsuarioRepository::createUser($input)) require_once  __DIR__ . '/login.php';
    else HTTPResponseHelper::send(['mensagem' => 'um erro interno aconteceu, tente novamente'], HTTPStatusHelper::ERROR_SERVER);

}

HTTPResponseHelper::badRequest(['mensagem' => 'campos invalidos.']);