<?php

use App\db\Producto;
use App\utils\Datos;
use App\utils\Utilidades;

require __DIR__ . "/../vendor/autoload.php";
session_start();

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $descripcion = Utilidades::limpiarCadena($_POST["descripcion"]);
    $tipo = isset($_POST["tipos"]) ? Utilidades::limpiarCadena($_POST["tipos"]) : -1;
    $errores = false;

    if (!Utilidades::isLongitudValida("nombre", $nombre, 3, 60)) {
        $errores = true;
    } else {
        if (Utilidades::esDuplicado("nombre", $nombre,$id)) {
            $errores = true;
        }
    }
    if (!Utilidades::isLongitudValida("descripcion", $descripcion, 5, 100)) {
        $errores = true;
    }
    if (!Utilidades::esTipoValido($tipo)) {
        $errores = true;
    }

    // Poner una imagen por defecto, si sube una se la cambio sino le pongo directamente esa

    $imagen = "img/default.webp";

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) { // Si se ha subido una imagen al servidor
        if (!Utilidades::esImagenValida($_FILES['imagen']['size'], $_FILES['imagen']['type'])) { // Entonces, compruebo si es una imagen
            $errores = true;
        } else {
            $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name']; // El uniqid es necesario porque sino, se machacan
            // Ahora quiero mover la imagen de la carpeta temp a la actual
            if(!move_uploaded_file($_FILES['imagen']['tmp_name'],$imagen)) {
                $_SESSION["err_imagen"] = "Error, al mover al directorio img";
                $errores = true;
            }
        }
    }
    
    if ($errores) {
        header("Location:" . $_SERVER["PHP_SELF"]);
        die();
    }

    (new Producto)
        ->setNombre($nombre)
        ->setDescripcion($descripcion)
        ->setTipo($tipo)
        ->setImagen($imagen)
        ->crear();

    $_SESSION["mensaje"] = "Producto insertado correctamente.";
    header("Location:productos.php");
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

<body class="bg-gradient-to-r from-blue-500 via-grey to-black">
    <h1 class="text-center text-2xl font-mono text-white mt-20 mb-3">Insertar nuevo producto</h1>
    <form class="max-w-sm m-auto" method="POST" action="<?= $_SERVER["PHP_SELF"] ?>" enctype="multipart/form-data">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?php Utilidades::mostrarErrores("err_nombre") ?>
        </div>
        <div class="mb-5">
            <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
            <?php Utilidades::mostrarErrores("err_descripcion") ?>
        </div>
        <div class="mb-5">
            <div class="flex">
                <?php
                foreach (Datos::getTipos() as $tipo) {
                    echo <<< TXT
                        <div class="flex items-center me-4">
                        <input id="$tipo" type="radio" value="$tipo" name="tipos" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="$tipo" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">$tipo</label>
                        </div>
                    TXT;
                }
                ?>
            </div>
            <?php Utilidades::mostrarErrores("err_tipo") ?>
        </div>

        <div>
            <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])">
            <img src="img/default.webp" width="200" height="100" id="imgpreview" class="mt-3 mb-3 rounded-lg">
            <?php Utilidades::mostrarErrores("err_imagen") ?>
        </div>
        <button type="submit" name="submit" class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Insertar</button>
    </form>
</body>