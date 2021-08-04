<?php

namespace source\controllers\resources;

use source\helpers\HTTPRequestHelper;
use source\helpers\HTTPResponseHelper;
use source\model\NoticiaRepository;

$path_info = HTTPRequestHelper::obterPathInfo();
$e_path_valido = is_numeric($path_info);

if(!$e_path_valido) HTTPResponseHelper::badRequest(['mensagem' => 'o id precisa numerico']);

$noticia = NoticiaRepository::selectById($path_info);

if($noticia) NoticiaRepository::deleteById($path_info);

HTTPResponseHelper::ok(null);