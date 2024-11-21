<?php

namespace App\utils;

use App\db\Producto;
use App\utils\Datos;

class Utilidades
{
    public static function limpiarCadena(string $cadena): string
    {
        return htmlspecialchars(trim($cadena));
    }

    public static function isLongitudValida(string $nombre, string $valor, int $min, int $max): bool
    {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nombre"] = "El campo $nombre tiene que estar entre $min y $max carácteres.";
            return false;
        }
        return true;
    }

    public static function esTipoValido(string | int $tipo): bool
    {
        if (gettype($tipo) == "integer") {
            $_SESSION["err_tipo"] = "No se ha seleccionado ningún tipo.";
            return false;
        }
        if (!in_array($tipo, Datos::getTipos())) {
            $_SESSION["err_tipo"] = "El tipo introducido no es correcto.";
            return false;
        }
        return true;
    }

    public static function esImagenValida(int $size, string $mime): bool
    {
        if ($size > 20000) {
            $_SESSION["err_imagen"] = "El tamaño no es correcto.";
            return false;
        }
        if (!in_array($mime,Datos::getFormatos())) {
            $_SESSION["err_imagen"] = "No has introducido una imagen.";
            return false;
        }
        return true;
    }

    public static function esDuplicado(string $nombre, string $valor, ?int $id = null) {
        if(Producto::existeCampo($nombre,$valor,$id)) {
            $_SESSION["err_$nombre"] = "El campo $valor ya está registrado.";
            return true;
        }
        return false;
    }

    public static function mostrarErrores(string $error) : void {
        if(isset($_SESSION[$error])) {
            echo "<p class='text-orange-600 text-sm italic'> $_SESSION[$error] </p>";
            unset($_SESSION[$error]);
        }
    }
}
