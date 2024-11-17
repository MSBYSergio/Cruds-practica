<?php

if(!isset($_POST["id"])) { // Primero, no permito que entren sin el id
    header("Location:articulos.php");
}

session_start();
use App\db\Articulo;
require __DIR__ . "/../vendor/autoload.php";
$id = $_POST["id"];
Articulo::delete($id);
$_SESSION["mensaje"] = "Artículo eliminado correctamente";
header("Location:articulos.php");
