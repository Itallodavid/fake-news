<?php

use source\helpers\HTTPRequestHelper;
use source\helpers\HTTPResponseHelper;
use source\helpers\ImagemHelper;
use source\helpers\SessionHelper;
use source\model\NoticiaRepository;

$input = filter_input_array(INPUT_POST, [
    'titulo' => FILTER_DEFAULT,
    'conteudo' => FILTER_DEFAULT
]);

if(empty($input['titulo']) || empty($input['conteudo'])) HTTPResponseHelper::badRequest(['mensagem' => 'campos invalidos']);

$input['titulo'] = $input['titulo'];
$input['conteudo'] = $input['conteudo'];

$path_info = HTTPRequestHelper::obterPathInfo();
$e_valido_path = is_numeric($path_info);

if($e_valido_path) {
    $noticia = NoticiaRepository::selectById($path_info);
   
    if(!$noticia) HTTPResponseHelper::badRequest('a notica não pode ser atualizada, ela não existe na base de dados.');

    $input['id'] = $noticia['id'];

    if(isset($_FILES['imagem']) && ImagemHelper::validarImagem()) $input['imagem'] = ImagemHelper::salvarImagem();
    else $input['imagem'] = $noticia['imagem'];

    NoticiaRepository::updateById($input);
    HTTPResponseHelper::ok(['aviso' => ImagemHelper::obterLog()]);
}

$input['autor_id'] = SessionHelper::obterIdUsuario();
$input['imagem'] = ImagemHelper::salvarImagem();
NoticiaRepository::create($input);