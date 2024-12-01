<?php

namespace App\utils;

use App\db\Articulo;
use App\db\Categoria;

class Utilidades
{

    const TIPOS = ['image/png','image/jpeg','image/jpeg','image/gif','image/webp'];

    public static function limpiarCadena(string $cadena): string
    {
        return htmlspecialchars(trim($cadena));
    }

    public static function esCadenaValida(string $nombre, string $valor, int $min, int $max): bool
    {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nombre"] = "El campo tiene que estar entre $min y $max carácteres.";
            return false;
        }
        return true;
    }

    public static function esDisponibleValido(string |int $valor): bool
    {
        if (!in_array($valor, Articulo::DISPONIBILIDAD)) {
            $_SESSION["err_disponible"] = "La disponibilidad es incorrecta o no has seleccionado ninguna.";
            return false;
        }
        return true;
    }

    public static function esCategoriaValida(string $valor): bool
    {
        if (!in_array($valor, Categoria::categoriasIds())) {
            $_SESSION["err_categoria"] = "La categoría es incorrecta o no has seleccionado ninguna.";
            return false;
        }
        return true;
    }

    public static function existeCampo(string $nombre, string $valor , ?int $id = null)
    {
        if (Articulo::existeCampo($nombre, $valor, $id)) {
            $_SESSION["err_$nombre"] = "El campo $valor ya está duplicado.";
            return true;
        }
        return false;
    }

    public static function esImagenValida(string $tipo, int $tamanio) {
        if(!in_array($tipo,self::TIPOS)) {
            $_SESSION["err_imagen"] = "No has introducido una imagen.";
            return false;
        }
        if($tamanio > 2000000) {
            $_SESSION["err_imagen"] = "El tamaño de la imagen supera el límite.";
            return false;
        }
        return true;
    }

    public static function mostrarErrores(string $error)
    {
        if (isset($_SESSION[$error])) {
            echo "<p class='text-red-600 italic text-sm'>$_SESSION[$error] </p>";
            unset($_SESSION[$error]);
        }
    }

}
