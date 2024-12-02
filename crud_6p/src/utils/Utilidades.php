<?php

namespace App\utils;

use App\db\Libro;
use App\db\User;

class Utilidades
{

    const TIPOS = ['image/gif', 'image/jpeg', 'image/png', 'image/webp'];

    public static function limpiarCadena(string $cadena)
    {
        return htmlspecialchars(trim($cadena));
    }

    public static function esLongitudValida(string $nombre, string $valor, int $min, int $max)
    {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nombre"] = "El campo $nombre tiene que tener entre $min y $max carácteres.";
            return false;
        }
        return true;
    }
 
    public static function existeCampo(string $nombre, string $valor, ?int $id = null) {
        if(User::existeCampo($nombre,$valor,$id)) {
            $_SESSION["err_$nombre"] = "El campo $nombre ya está duplicado.";
            return true;
        }
        return false;
    }

    public static function esEmailValido(string $email) {
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $_SESSION["err_email"] = "El email no tiene un formato válido.";
            return false;
        }
        return true;
    }

    public static function esLibroIdValido(int $id)
    {
        if (!in_array($id, Libro::getLibrosIds())) {
            $_SESSION["err_libro_id"] = "No has elegido ningún libro o es incorrecto.";
            return false;
        }
        return true;
    }

    public static function esPerfilValido(string $perfil)
    {
        if (!in_array($perfil, User::PERFILES)) {
            $_SESSION["err_perfil"] = "El perfil es incorrecto o no selecciono ninguno.";
            return false;
        }
        return true;
    }

    public static function esImagenValida(string $tipo, int $size)
    {
        if (!in_array($tipo, self::TIPOS)) {
            $_SESSION["err_imagen"] = "El tipo de la imagen no es válida.";
            return false;
        }
        if ($size > 200000) {
            $_SESSION["err_imagen"] = "EL tamaño de la imagen sobrepasa el límite.";
            return false;
        }
        return true;
    }

    public static function mostrarError(string $error)
    {
        if (isset($_SESSION[$error])) {
            echo "<p class='text-red-700 text-sm font-mono italic'> $_SESSION[$error] </p>";
            unset($_SESSION[$error]);
        }
    }
}
