<?php

namespace App\utils;

class Datos
{
    public static function getTipos()
    {
        return ["Bazar", "Alimentación", "Limpieza"];
    }

    public static function getFormatos()
    {
        return ["image/gif", "image/jpeg", "image/png", "image/webp"];
    }
}
