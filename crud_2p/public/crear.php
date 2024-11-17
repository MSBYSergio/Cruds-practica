<?php

use App\utils\Datos;
use App\utils\Utilidades;
use App\db\Usuario;

require __DIR__ . "/../vendor/autoload.php";
session_start();

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $email = Utilidades::limpiarCadena($_POST["email"]);
    $color = Utilidades::limpiarCadena($_POST["colores"] ?? -1);
    
    $errores = false;

    if (!Utilidades::esCadenaValida("nombre", $nombre, 5, 200)) {
        $errores = true;
    } else if (Utilidades::esCampoDuplicado("nombre", $nombre)) {
        $errores = true;
    }
    if (!Utilidades::esEmailValido($email)) {
        $errores = true;
    } else if (Utilidades::esCampoDuplicado("email", $email)) {
        $errores = true;
    }
    if (!Utilidades::existeColor($color)) {
        $errores = true;
    } else if (!Utilidades::esColorValido($color)) {
        $errores = true;
    }

    /* Antes intentaba validar con la imagen por defecto pero como no se sube como tal
       Voy a crearme una variable con la imagen por defecto y le voy a cambiar el valor si se ha subido algo 
    */

    $imagen = "img/rana.jpg";

    if (is_uploaded_file($_FILES["imagen"]["tmp_name"])) {
        if (!Utilidades::esImagenValida($_FILES["imagen"]["type"], $_FILES["imagen"]["size"])) {
            $errores = true;
        } else { // He subido un archivo correcto
            $imagen = "img/" . uniqid() .  $_FILES["imagen"]["name"];
            if(!move_uploaded_file($_FILES["imagen"]["tmp_name"],$imagen)) { // Si no se ha movido de la carpeta tmp
                $_SESSION["err_imagen"] = "No se ha podido subir la imagen";
                $errores = true;
            }
        }
    }

    // De forma si el usuario sube algo le cambio el valor a la imagen o sino sube nada le doy la imagen por defecto

    if ($errores) {
        header("Location:crear.php");
        die();
    }

    (new Usuario)
    -> setNombre($nombre)
    -> setEmail($email)
    -> setColor($color)
    -> setImagen($imagen)
    -> crear();

    $_SESSION["mensaje"] = "Usuario insertado correctamente";
    header("Location:usuarios.php");
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CDN TAILWIND -->
    <script src="https://cdn.tailwindcss.com"> </script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Insertar usuario</title>
</head>

<body class="bg-blue-200">
    <h1 class="text-center text-2xl text-blue-600 mt-20">Insertar usuario</h1>
    <form class="max-w-sm m-auto" method="POST" action="<?= $_SERVER["PHP_SELF"] ?>" enctype="multipart/form-data">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?php Utilidades::mostrarErrores("err_nombre") ?>
        </div>
        <div class="mb-5">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu email</label>
            <input type="email" id="email" name="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="ejemplo@email.com" />
            <?php Utilidades::mostrarErrores("err_email") ?>
        </div>
        <div class="mb-5">
            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Colores</h3>
            <div class="flex"> <!-- Para formar el grupo dentro del radio button tienen que tener el mismo nombre en el atributo name -->
                <?php
                foreach (Datos::getColores() as $color) {
                    echo <<< TXT
                                <div class="flex items-center me-4">
                                <input id="$color" type="radio" value="$color" name="colores" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="$color" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">$color</label>
                                </div>
                        TXT;
                }
                ?>
            </div>
            <?php Utilidades::mostrarErrores("err_color") ?>
        </div>

        <div>
            <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])">
            <img src="img/rana.jpg" width="200" height="100" id="imgpreview" class="mt-3 mb-3 rounded-lg">
            <?php Utilidades::mostrarErrores("err_imagen") ?>
        </div>
        <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Insertar usuario</button>
    </form>
</body>