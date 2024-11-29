<?php

use App\db\Usuario;

require __DIR__ . "/../vendor/autoload.php";
session_start();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);


if(!isset($_SESSION['login'])) { /* Si no se han logeado me voy */
    header("Location:usuarios.php");
    die();
}

/* No hace falta comprobar nada más porque pueden eliminar tanto normal como administrador */

if (!$id || $id <= 0) {
    header("Location:usuarios.php");
    die();
}

$usuario = Usuario::getUsuarioById($id);

if (basename($usuario->imagen) != "default.png") {
    unlink($usuario->imagen);
}

Usuario::delete($id);
$_SESSION["mensaje"] = "Usuario eliminado correctamente.";
header("Location:usuarios.php");
