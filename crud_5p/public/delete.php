<?php
session_start();
use App\db\Articulo;

require __DIR__ . "/../vendor/autoload.php";

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location:articulos.php");
    die();
}

if (!$usuario = Articulo::getImagenById($id)) {
    header("Location:articulos.php");
    die();
}

if (basename($usuario -> imagen) != "default.png") {
    unlink($usuario -> imagen);
}

Articulo::delete($id);
$_SESSION["mensaje"] = "Articulo eliminado.";
header("Location:articulos.php");
