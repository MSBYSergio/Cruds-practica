<?php

namespace App\utils;

use App\db\Color;
use App\db\Usuario;

class Utilidades
{
    public static function limpiarCadena(string $cadena): string
    {
        return htmlspecialchars(trim($cadena));
    }

    public static function esLongitudValida(string $campo, string $valor, int $min, int $max): bool
    {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$campo"] = "Error, el campo tiene que estar entre $min y $max carácteres.";
            return false;
        }
        return true;
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id = null): bool
    {
        if (Usuario::existeCampo($nombre, $valor, $id)) {
            $_SESSION["err_$nombre"] = "El campo $valor ya está registrado.";
            return true;
        }
        return false;
    }

    public static function esColorValido(string | int $color_id) : bool
    {
        if (!in_array($color_id, Color::getColorIds())) {
            $_SESSION["err_color"] = "El color no es válido o no has seleccionado ninguno.";
            return false;
        }
        return true;
    }

    public static function esPerfilValido(string $perfil)
    {
        if (!in_array($perfil, Datos::getPerfiles())) {
            $_SESSION["err_perfil"] = "El perfil no es válido o no has seleccionado ninguno.";
            return false;
        }
        return true;
    }

    public static function esImagenValida(string $type, int $size): bool
    {
        if (!in_array($type, Datos::getTipos())) {
            $_SESSION["err_imagen"] = "No has enviado una imagen.";
            return false;
        }
        if ($size > 200000) {
            $_SESSION["err_imagen"] = "El tamaño supera el límite.";
            return false;
        }
        return true;
    }

    public static function esLoginValido(string $nombre, string $password) : bool
    {
        $datos = Usuario::esLoginValido($nombre, $password);
        if (!is_array($datos)) {
            $_SESSION["err_login"] = "Credenciales incorrectas.";
            return false;
        }
        $_SESSION['login'] = $datos;
        return true;
    }

    public static function mostrarErrores(string $error): void
    {
        if (isset($_SESSION[$error])) {
            echo "<p class='text-red-600 font-mono italic'> $_SESSION[$error] </p>";
            unset($_SESSION[$error]);
        }
    }
}
