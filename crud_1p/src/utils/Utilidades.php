<?php

namespace App\utils;

use App\db\Articulo;

class Utilidades
{

    public static function limpiarCadena(string $cadena): string
    {
        return htmlspecialchars(trim($cadena));
    }

    public static function validarCadena(string $cadena, int $min, int $max): bool
    {
        if (strlen($cadena) < $min || strlen($cadena) > $max) {
            $_SESSION["err_nombre"] = "El nombre no tiene  un formato válido.";
            return false;
        }
        return true;
    }

    public static function validarNumero(int | float $numero, string $nombre): bool
    {
        if ($numero <= 0 || $numero > 1000) {
            $_SESSION["err_$nombre"] = "El $nombre no tiene un formato válido.";
            return false;
        }
        return true;
    }

    public static function existeArticulo(string $nombre, ?int $id = null): bool
    {
        if (Articulo::existeArticulo($nombre, $id)) {
            $_SESSION["err_nombre"] = "El artículo ya está en la base de datos";
            return true;
        }
        return false;
    }

    public static function mostrarErrores(string $error): void
    {
        if (isset($_SESSION[$error])) {
            echo "<p class='text-base text-red-600 font-sans italic'>" . $_SESSION[$error] . "</p>";
            unset($_SESSION[$error]);
        }
    }
}
