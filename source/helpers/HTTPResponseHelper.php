<?php

namespace source\helpers;

final class HTTPResponseHelper
{
    public static function send($mensagem = null, $status_code = HTTPStatusHelper::OK)
    {
        http_response_code($status_code);
        die(json_encode($mensagem));
    }

    public static function ok($message)
    {
        self::send($message);
    }

    public static function methodNotAllowed($message)
    {
        self::send($message, HTTPStatusHelper::METHOD_NOT_ALLOWED);
    }

    public static function badRequest($message)
    {
        self::send($message, HTTPStatusHelper::BAD_REQUEST);
    }

    private function __construct()
    {
    }
}