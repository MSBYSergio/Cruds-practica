<?php
require __DIR__ . "/../vendor/autoload.php";
session_start();

use App\db\Usuario;
use App\db\Color;
use App\utils\Datos;
use App\utils\Utilidades;

$colores = Color::getColores();

if(!isset($_SESSION['login'])) { /* Solamente podrán acceder usuarios logeados */
    header("Location:usuarios.php");
    die();
}

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $password = Utilidades::limpiarCadena($_POST["password"]);
    $color_id = (int) (isset($_POST["color"])) ? Utilidades::limpiarCadena($_POST["color"]) : -1;
    $perfil = (isset($_POST["perfil"]) ? Utilidades::limpiarCadena($_POST["perfil"]) : -1);
   
    $errores = false;

    if (!Utilidades::esLongitudValida("nombre", $nombre, 3, 50)) {
        $errores = true;
    } else if (Utilidades::existeCampo("nombre", $nombre)) {
        $errores = true;
    }
    if (!Utilidades::esLongitudValida("pass", $password, 3, 80)) {
        $errores = true;
    }
    if(!Utilidades::esColorValido($color_id)) {$errores = true;}
    if(!Utilidades::esPerfilValido($perfil)) {$errores = true;}
    
    $imagen = "img/default.png";

    if(is_uploaded_file($_FILES["imagen"]["tmp_name"])) {
        if(!Utilidades::esImagenValida($_FILES['imagen']['type'],$_FILES['imagen']['size'])) {
            $errores = true;
        } else {
            $imagen = "img/" . uniqid() . "_" . $_FILES["imagen"]["name"];
            if(!move_uploaded_file($_FILES['imagen']['tmp_name'],$imagen)) {
                $_SESSION["err_imagen"] = "No se ha podido subir la imagen.";
                $errores = true;
            }
        }
    }

    if($errores) {
        header("Location:" . $_SERVER["PHP_SELF"]);
        die();
    }

    (new Usuario)
    -> setNombre($nombre)
    -> setPassword($password,false)
    -> setColorId($color_id)
    -> setPerfil($perfil)
    -> setImagen($imagen)
    -> create();

    $_SESSION["mensaje"] = "Usuario registrado correctamente.";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"> </script>
</head>

<body class="bg-indigo-200">
    <h1 class="text-center mt-20 text-xl font-mono">Registrar usuario</h1>
    <form class="max-w-sm mx-auto mt-5" method="post" action="<?= $_SERVER["PHP_SELF"] ?>" enctype="multipart/form-data">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu nombre</label>
            <input type="text" name="nombre" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?= Utilidades::mostrarErrores('err_nombre'); ?>
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu contraseña</label>
            <input type="password" name="password" id="password" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?= Utilidades::mostrarErrores('err_pass'); ?>
        </div>
        <div class="mb-5">
            <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Colores</label>
            <select id="color" name="color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option>__ELIGE UN COLOR__</option>
                <?php foreach ($colores as $color) : ?>
                    <option value="<?= $color->id ?>"> <?= $color->nombre ?> </option>
                <?php endforeach; ?>
            </select>
            <?= Utilidades::mostrarErrores("err_color"); ?>
        </div>

        <div class="mb-5">
            <div class="flex items-center me-4 mr-3">
                <?php foreach (Datos::getPerfiles() as $perfil) : ?>
                    <input id="<?= $perfil ?>" type="radio" value="<?= $perfil ?>" name="perfil" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="<?= $perfil ?>" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 mr-4"> <?= $perfil ?> </label>
                <?php endforeach; ?>
            </div>
            <?= Utilidades::mostrarErrores("err_perfil"); ?>
        </div>

        <div class="mb-5">
            <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])">
            <img src="img/default.png" width="200" height="100" id="imgpreview" class="mt-3 mb-3 rounded-lg">
            <?= Utilidades::mostrarErrores("err_imagen"); ?>
        </div>
        <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar usuario</button>
    </form>
</body>