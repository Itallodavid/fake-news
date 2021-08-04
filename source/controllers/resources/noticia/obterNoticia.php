<?php

use source\helpers\HTTPRequestHelper;
use source\helpers\HTTPResponseHelper;
use source\helpers\HTTPStatusHelper;
use source\model\NoticiaRepository;

$filtro = filter_input_array(INPUT_GET, [
    'pagina' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['default' => 1, 'min_range' => 1]
    ],
    'usuario' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 1]
    ]
]);

$resposta = null;
$pagina = $filtro['pagina'] ?? null;
$usuario = $filtro['usuario'] ?? null;

if(is_null($filtro)) $resposta = NoticiaRepository::selectAllPost();
else if($pagina) {
    if($usuario) $resposta = NoticiaRepository::selectByUser($usuario, $pagina);
    else $resposta = NoticiaRepository::selectByPage($pagina);
}

HTTPResponseHelper::send(
    $resposta, 
    is_null($resposta) ? HTTPStatusHelper::NOT_FOUND : HTTPStatusHelper::OK
);