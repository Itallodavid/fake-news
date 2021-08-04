<?php

namespace source\helpers;

final class SessionHelper
{
    public const NIVEL_PERMISSAO_VISITANTE = 4;
    public const NIVEL_PERMISSAO_USUARIO = 6;
    public const NIVEL_PERMISSAO_ADMIN = 8;

    public static function criarSessao($idUsuario, $nomeUsuario, $nivelPermissaoUsuario)
    {
        if (self::possuiSessaoAtiva()) return false;

        $_SESSION['user'] = [
            'id' => $idUsuario,
            'nome' => $nomeUsuario,
            'nivel_permissao' => $nivelPermissaoUsuario
        ];

        return true;
    }

    public static function possuiSessaoAtiva()
    {
        return isset($_SESSION['user']);
    }

    public static function destruirSessao()
    {
        if (self::possuiSessaoAtiva()) {
            session_destroy();
            return true;
        }

        return false;
    }

    // public static function obterIdUsuario()
    // {
    //     return $_SESSION['user']['id'] ?? null;
    // }

    public static function obterIdUsuario()
    {
        return 1; // mock 
    }

    public static function obterNomeUsuario()
    {
        return $_SESSION['user']['nome'] ?? null;
    }

    public static function obterNivelPermissaoUsuario()
    {
        return $_SESSION['user']['nivel_permissao'] ?? self::NIVEL_PERMISSAO_VISITANTE;
    }

    public static function obterInfoSessao()
    {
        return  [
            'id' => self::obterIdUsuario(),
            'nome' => self::obterNomeUsuario(),
            'nivel_permissao' => self::obterNivelPermissaoUsuario()
        ];
    }

    private function __construct()
    {
    }
}
