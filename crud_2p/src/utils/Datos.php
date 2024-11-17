<?php

namespace App\utils;

class Datos
{
    public static function getColores()
    {
        return ["Rojo", "Azul", "Amarillo"];
    }

    public static function getTypes()
    {
        return [
            'image/gif',
            'image/png',
            'image/jpeg',
            'image/bmp',
            'image/webp'
        ];
    }
}
