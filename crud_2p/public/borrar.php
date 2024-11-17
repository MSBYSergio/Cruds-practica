<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";

use App\db\Usuario;

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id == 0) { // Significa que no es válido
    header("Location:usuarios.php");
    die();
}
// Necesito el usuario para saber si existe y para eliminarle la imagen

$usuario = Usuario::read($id);

if (count($usuario) == 0) { // Significa que la consulta no me ha devuelto nada, por lo que no existe
    header("Location:usuarios.php");
    die();
}

if (basename($usuario[0]->imagen) != "rana.jpg") {
    unlink($usuario[0]->imagen);
}

Usuario::delete($id);
$_SESSION["mensaje"] = "Usuario eliminado correctamente.";
header("Location:usuarios.php");
