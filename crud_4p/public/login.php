<?php

use App\utils\Utilidades;

session_start();
require __DIR__ . "/../vendor/autoload.php";

// En el login tienen que estar los usuarios que están dentro de la base de datos

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $password = Utilidades::limpiarCadena($_POST["password"]);
    $errores = false;

    if (!Utilidades::esLongitudValida("nombre", $nombre, 3, 50)) {
        $errores = true;
    }
    if (!Utilidades::esLongitudValida("password", $password, 3, 80)) {
        $errores = true;
    }
    if (!Utilidades::esLoginValido($nombre, $password)) {
        $errores = true;
    }
    if ($errores) {
        header("Location:login.php");
        die();
    }
    header("Location:usuarios.php");
}

?>
<!DOCTYPE html>
<html lang="es">

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
<!-- Usuario que ya existe -->

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <h1 class="text-center text-blue-900 font-mono text-xl mt-10 mb-5">Login</h1>
    <form class="max-w-sm mx-auto mt-5" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu nombre</label>
            <input type="text" id="nombre" name="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            <?= Utilidades::mostrarErrores("err_nombre"); ?>
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu contraseña</label>
            <input type="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            <?= Utilidades::mostrarErrores("err_password"); ?>
        </div>
        <?=Utilidades::mostrarErrores("err_login"); ?>
        <button type="submit" name="submit" class="block w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-primary-800 mb-3">Iniciar sesión</button>
    </form>

</body>

</html>