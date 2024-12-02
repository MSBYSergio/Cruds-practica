<?php
session_start();
use App\db\User;
require __DIR__ . "/../vendor/autoload.php";

$id = $_POST['id'] ?? 0;
if (!$id) {
    header("Location:usuarios.php");
    die();
}

$user = User::getUserById($id);

if(basename($user -> imagen) != "default.png") {
    unlink($user -> imagen);
}

User::delete($id);
$_SESSION["mensaje"] = "Usuario eliminado correctamente.";
header("Location:usuarios.php");