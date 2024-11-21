<?php

$id = filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT); // Tiene que tener tanto name como value

if(!$id || $id <= 0) {
    header("Location:productos.php");
    die();
}

use App\db\Producto;
session_start();
require __DIR__ . "/../vendor/autoload.php";

$producto = Producto::read($id)[0];

if(basename($producto -> imagen) != "default.webp") {
    unlink($producto -> imagen);
}

Producto::delete($id);

$_SESSION["mensaje"] = "Producto eliminado correctamente.";
header("Location:productos.php");

