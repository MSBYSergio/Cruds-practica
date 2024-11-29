<?php
require __DIR__ . "/../vendor/autoload.php";
session_start();

use App\db\Usuario;
use App\db\Color;
use App\utils\Datos;
use App\utils\Utilidades;

/* Mme voy si no estoy logeado o si estoy logeado y mi perfil es Normal  */

if(!isset($_SESSION['login']) || isset($_SESSION['login']) && $_SESSION['login'][1] == "Normal") {
    header("Location:usuarios.php");
    die();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) { // El filtro devuelve false si no ha podido recoger la variable
    header("Location:usuarios.php");
    die();
}

if (!$usuario = Usuario::getUsuarioById($id)) { // Cuando es false, significa que no existe, por lo que me voy a articulos
    header("Location:usuarios.php");
    die();
}

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $password = Utilidades::limpiarCadena($_POST["password"]);
    $color_id = isset($_POST['color_id']) ? Utilidades::limpiarCadena($_POST['color_id']) : -1;
    $perfil = (isset($_POST["perfil"]) ? Utilidades::limpiarCadena($_POST["perfil"]) : -1);

    $errores = false;

    if (!Utilidades::esLongitudValida("nombre", $nombre, 3, 50)) {
        $errores = true;
    } else if (Utilidades::existeCampo("nombre", $nombre, $id)) {
        $errores = true;
    }

    if (!Utilidades::esColorValido($color_id)) {
        $errores = true;
    }
    if (!Utilidades::esPerfilValido($perfil)) {
        $errores = true;
    }

    $imagenNueva = $usuario->imagen;

    if (is_uploaded_file($_FILES["imagen"]["tmp_name"])) {
        if (!Utilidades::esImagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
            $errores = true;
        } else {
            $imagenNueva = "img/" . uniqid() . "_" . $_FILES["imagen"]["name"];
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenNueva)) {
                $_SESSION["err_imagen"] = "No se ha podido subir la imagen.";
                $errores = true;
            }
        }
    }

    if ($errores) {
        header("Location:" . $_SERVER["PHP_SELF"] . "?id=$id");
        die();
    }

    (new Usuario)
        ->setNombre($nombre)
        ->setPassword($password)
        ->setColorId($color_id)
        ->setPerfil($perfil)
        ->setImagen($imagenNueva)
        ->update($id);
        
    $imagenAntigua = $usuario->imagen;

    if ($imagenAntigua != $imagenNueva) {
        if (basename($imagenAntigua) != 'default.png') {
            unlink($imagenAntigua);
        }
    }

    $_SESSION["mensaje"] = "Usuario actualizado correctamente.";
    header("Location:usuarios.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de usuarios</title>
    <!-- CDN TAILWIND -->
    <script src="https://cdn.tailwindcss.com"> </script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <h1 class="text-center text-blue-900 font-mono text-xl mt-10 mb-5">Actualizar usuario</h1>
    <form class="max-w-sm mx-auto mt-5" method="post" action="<?= $_SERVER["PHP_SELF"] . "?id=$id" ?>" enctype="multipart/form-data">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu nombre</label>
            <input type="text" name="nombre" value="<?= $usuario->nombre ?>" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?= Utilidades::mostrarErrores('err_nombre') ?>
        </div>

        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu contraseña</label>
            <input type="password" name="password" id="password" value="" placeholder="************" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
        </div>

        <div class="mb-5">
            <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colores</label>
            <select name="color_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?php foreach (Color::getColores() as $color) : ?>
                    <?php $estado = ($usuario->color_id == $color->id) ? "selected" : "" ?>
                    <option value="<?= $color->id ?>" <?= $estado ?>> <?= $color->nombre ?> </option>
                <?php endforeach; ?>
            </select>
            <?= Utilidades::mostrarErrores("err_color"); ?>
        </div>

        <div class="mb-5">
            <div class="flex items-center me-4 mr-3">
                <?php foreach (Datos::getPerfiles() as $perfil) : ?>
                    <?php $estado = ($usuario->perfil == $perfil) ? "checked" : "" ?>
                    <input id="<?= $perfil ?>" type="radio" value="<?= $perfil ?>" <?= $estado ?> name="perfil" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="<?= $perfil ?>" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 mr-4"> <?= $perfil ?> </label>
                <?php endforeach; ?>
            </div>
            <?= Utilidades::mostrarErrores("err_perfil") ?>
        </div>

        <div class="mb-5">
            <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])">
            <img src="<?= $usuario->imagen; ?>" width="200" height="100" id="imgpreview" class="mt-3 mb-3 rounded-lg">
            <?= Utilidades::mostrarErrores("err_imagen") ?>
        </div>

        <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Actualizar usuario</button>
    </form>
</body>