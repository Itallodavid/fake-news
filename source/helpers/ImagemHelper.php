<?php

namespace source\helpers;

final class ImagemHelper
{
    public const DIR_RAIZ = './source';
    public const DIR_IMAGEM = '/images';
    public const IMAGEM_PADRAO = 'default.jpg';
    public const EXTENSOES_VALIDAS = ['jpg', 'png'];
    public const TAMANHO_MAXIMO = 1024 * 1024 * 2; // 2MB
    public const LINK_DA_IMAGEM_PADRAO = self::DIR_RAIZ . self::DIR_IMAGEM . '/' . self::IMAGEM_PADRAO;

    private static $erro_log = [];
    private static $imagem_e_valida = false;
    private static $imagem_foi_validada = false;
    private static $imagem_valida_para_salvar = 0;

    public static function obterCaminhoAbsolutoImagem()
    {
        return dirname(__DIR__, 1) . self::DIR_IMAGEM;
    }

    public static function validarImagem(string $nome_campo = 'imagem'): bool
    {
        $imagem = $_FILES[$nome_campo] ?? [];
        $tamanho_imagem = $imagem['size'] ?? 0;
        $erro = $imagem['error'] ?? 4;
        $extensao = self::obterExtensaoImagem($nome_campo);

        if (!in_array($extensao, self::EXTENSOES_VALIDAS)) self::$erro_log[] = 'extensão invalida, apenas JPG ou PNG';

        if ($tamanho_imagem > self::TAMANHO_MAXIMO) self::$erro_log[] = 'a imagem não deve ter mais de 2MB';

        if ($erro !== 0) self::$erro_log[] = 'ocorreu um erro com o envio da imagem';

        if (empty(self::$erro_log)) self::$imagem_e_valida = true;

        self::$imagem_foi_validada = true;
        return self::$imagem_e_valida;
    }

    public static function salvarImagem(string $nome_campo = 'imagem')
    {

        if (!self::$imagem_foi_validada) self::validarImagem($nome_campo);

        if (self::$imagem_e_valida) {
            $imagem = $_FILES[$nome_campo];
            $imagem_diretorio_temporario = $imagem['tmp_name'];
            $novo_nome_imagem = time() . "." . self::obterExtensaoImagem($nome_campo);
            $caminho_completo_nova_imagem = self::obterCaminhoAbsolutoImagem() . '/' . "$novo_nome_imagem";
            $link_da_imagem = sprintf("%s%s/%s", self::DIR_RAIZ, self::DIR_IMAGEM, $novo_nome_imagem);

            if (move_uploaded_file($imagem_diretorio_temporario, $caminho_completo_nova_imagem)) return $link_da_imagem;
            
            self::$erro_log[] = 'não foi possivel salvar a imagem no diretorio';
            return self::LINK_DA_IMAGEM_PADRAO;
        }

        return self::LINK_DA_IMAGEM_PADRAO;
    }

    public static function obterExtensaoImagem($nome = 'imagem')
    {
        $path = explode('.', $_FILES[$nome]['name'] ?? '');
        return strtolower(
            end($path)
        );
    }

    public static function obterLog()
    {
        return self::$erro_log;
    }

    private function __construct()
    {
    }
}
