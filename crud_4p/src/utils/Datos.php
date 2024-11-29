<?php
namespace App\utils;
class Datos {
    public static function getColores() {
        return ["Blanco","Negro","Rojo","Azul","Naranja"];
    }
    
    public static function getPerfiles() {
        return ["Normal","Administrador"];
    }

    public static function getTipos() {
        return ['image/gif','image/jpeg','image/png','image/webp'];
    }
}