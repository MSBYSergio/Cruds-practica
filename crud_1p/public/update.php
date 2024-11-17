<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";

use App\utils\Utilidades;
use App\db\Articulo;

if (!isset($_GET['id'])) {
    header("Location:articulos.php");
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    header("Location:articulos.php");
}

$articulo = Articulo::read($id);

if (!$articulo) { // Recordamos que el 0 es falso
    header("Location:articulos.php");
}

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $precio = (float) Utilidades::limpiarCadena($_POST["precio"]);
    $stock = (int) Utilidades::limpiarCadena($_POST["stock"]);
    $errores = false;

    if (!Utilidades::validarCadena($nombre, 5, 15)) {
        $errores = true;
    } else {
        // Comprueba los nombre de cuyos nombres sean distintos del que yo estoy introduciendo
        if (Utilidades::existeArticulo($nombre, $id)) {
            $errores = true;
        }
    }

    if (!Utilidades::validarNumero($precio, "precio")) {
        $errores = true;
    }

    if (!Utilidades::validarNumero($stock, "stock")) {
        $errores = true;
    }

    if ($errores) {
        header("Location:update.php?id=$id");
        die();
    }

    // Aquí los datos son válidos

    (new Articulo)
        ->setNombre($nombre)
        ->setPrecio($precio)
        ->setStock($stock)
        ->update($id);

    $_SESSION["mensaje"] = "Artículo actualizado correctamente";
    header("Location:articulos.php"); // Una vez insertado el Artículo vuelvo a mostrar la lista
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN TAILWIND -->
    <script src="https://cdn.tailwindcss.com"> </script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Actualizar artículo</title>
</head>
<!-- Cada vez que quiera irme a otra página le tengo que pasar el id -->

<body class="bg-red-200">
    <h1 class="text-center font-mono text-xl italic mt-20">Actualizar artículo</h1>
    <form class="max-w-sm mx-auto mt-5" method="post" action="<?php echo $_SERVER["PHP_SELF"] . "?id=$id" ?>">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
            <input type='text' name='nombre' value='<?php echo $articulo[0]->nombre ?>' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' />
            <?php
            Utilidades::mostrarErrores("err_nombre");
            ?>
        </div>
        <div class="mb-5">
            <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio</label>
            <input type='text' name='precio' value='<?php echo $articulo[0]->precio ?>' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' />
            <?php
            Utilidades::mostrarErrores("err_precio");
            ?>
        </div>
        <div class="mb-5">
            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock</label>
            <input type="number" name="stock" id="number-input" value='<?php echo $articulo[0]->stock ?>' aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="10" />
            <?php
            Utilidades::mostrarErrores("err_stock");
            ?>
        </div>
        <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Actualizar</button>
    </form>

</body>

</html>