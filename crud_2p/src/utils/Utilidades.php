<?php

namespace App\utils;

use App\db\Usuario;

class Utilidades
{
    public static function limpiarCadena(string $cadena): string
    {
        return htmlspecialchars(trim($cadena));
    }

    public static function esCadenaValida(string $nombre, string $valor, int $min, int $max): bool
    {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nombre"] = "El campo $nombre no tiene un formato válido.";
            return false;
        }
        return true;
    }

    public static function esEmailValido(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["err_email"] = "El campo email no tiene un formato válido.";
            return false;
        }
        return true;
    }

    public static function existeColor(string $color): bool
    {
        if ($color == -1) {
            $_SESSION["err_color"] = "No has escogido ningún color.";
            return false;
        }
        return true;
    }

    public static function esColorValido(string $color): bool
    {
        if (!in_array($color, Datos::getColores())) {
            $_SESSION["err_color"] = "Intento de ataque.";
            return false;
        }
        return true;
    }

    public static function esCampoDuplicado(string $nombre, string $valor, ?int $id = null): bool
    {
        if (Usuario::existeCampo($nombre, $valor,$id)) {
            $_SESSION["err_$nombre"] = "El campo $valor ya existe";
            return true;
        }
        return false;
    }
    
    public static function esImagenValida(string $extension, int $tamanio)
    {
        if (!in_array($extension, Datos::getTypes())) {
            $_SESSION["err_imagen"] = "La extensión de la imagen no es correcta";
            return false;
        }
        if ($tamanio > 200000) {
            $_SESSION["err_imagen"] = "La imagen sobrepasa el límite de tamaño";
            return false;
        }
        return true;
    }

    public static function mostrarErrores($error)
    {
        if (isset($_SESSION[$error])) {
            echo "<p class='text-red-600 italic text-sm'> $_SESSION[$error] </p>";
            unset($_SESSION[$error]);
        }
    }
}
