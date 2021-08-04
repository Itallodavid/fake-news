<?php

namespace source\helpers;

final class HTTPRequestHelper
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PUT = 'PUT';

    public static function aceitarApenasMetodos(array $metodos_http_aceitos)
    {
        $metodo = strtoupper($_SERVER['REQUEST_METHOD']);
        if(!in_array($metodo, $metodos_http_aceitos)) 
        HTTPResponseHelper::methodNotAllowed(['metodo_http_aceito' => $metodos_http_aceitos]);

        return $metodo;
        
    }

    public static function obterPathInfo(): string
    {
        return str_replace('/', '', $_SERVER['PATH_INFO'] ?? '');
    }

    private function __construct()
    {
    }
}