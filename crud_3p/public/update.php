<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";

use App\db\Producto;
use App\utils\Datos;
use App\utils\Utilidades;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location:productos.php");
    die();
}

if (!Producto::existeCampo('id', $id)) {
    header("Location:productos.php");
    die();
}

$producto = Producto::read($id)[0];

if (isset($_POST["submit"])) {
    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $descripcion = Utilidades::limpiarCadena($_POST["descripcion"]);
    $tipo = isset($_POST["tipos"]) ? Utilidades::limpiarCadena($_POST["tipos"]) : -1;
    $errores = false;

    if (!Utilidades::isLongitudValida("nombre", $nombre, 3, 60)) {
        $errores = true;
    } else {
        if (Utilidades::esDuplicado("nombre", $nombre, $id)) {
            $errores = true;
        }
    }
    if (!Utilidades::isLongitudValida("descripcion", $descripcion, 5, 100)) {
        $errores = true;
    }
    if (!Utilidades::esTipoValido($tipo)) {
        $errores = true;
    }

    $imagen = $producto->imagen;

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) { // Si se ha subido una imagen
        if (!Utilidades::esImagenValida($_FILES['imagen']['size'], $_FILES['imagen']['type'])) { // La valido
            $errores = true;
        } else {
            $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name']; // Si es correcta le pongo un id único
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) { // Después compruebo si se ha podido subir
                $_SESSION["err_imagen"] = "No se ha podido subir la imagen";
                $errores = true;
            }
        }
    }

    (new Producto)
        ->setNombre($nombre)
        ->setDescripcion($descripcion)
        ->setTipo($tipo)
        ->setImagen($imagen)
        ->update($id);

    $imagenAntigua = $producto->imagen;

    if ($imagen != $imagenAntigua) { // Después la elimino si la imagen nueva es distinta de la antigua 
        if ((basename($imagenAntigua) != "default.webp")) {
            unlink($imagenAntigua);
        }
    }

    $_SESSION["mensaje"] = "Producto actualizado correctamente.";
    header("Location:productos.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN TAILWIND -->
    <script src="https://cdn.tailwindcss.com"> </script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Actualizar producto</title>
</head>


<body class="bg-gradient-to-r from-blue-500 via-grey to-black">
    <h1 class="text-center text-2xl font-mono text-white mt-20 mb-3">Actualizar producto existente</h1>
    <form method="POST" action="<?= $_SERVER["PHP_SELF"] . "?id=$id" ?>" class="max-w-sm mx-auto" enctype="multipart/form-data">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= $producto->nombre ?>" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?php Utilidades::mostrarErrores("err_nombre") ?>
        </div>
        <div class="mb-5">
            <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"> <?= $producto->descripcion ?></textarea>
            <?php Utilidades::mostrarErrores("err_descripcion") ?>
        </div>
        <div class="mb-5">
            <?php
            foreach (Datos::getTipos() as $item) {
                $estado = "";
                if($producto -> tipo == $item) {$estado = "checked";}
                echo <<< TXT
                        <div class="flex items-center me-4">
                        <input id="$item" type="radio" value="$item" $estado name="group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="$item" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">$item</label>
                        </div>  
                    TXT;
            }
            ?>
        </div>
        <div class="flex items-center me-4">
        </div>
        <?php Utilidades::mostrarErrores("err_tipo") ?>
        <input type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])" class="mt-5">
        <img src="<?= $producto->imagen ?>" width="200" height="100" id="imgpreview" class="mt-3 mb-3 rounded-lg">
        <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-5">Actualizar producto</button>
    </form>

    <?php
    $prueba = true;
    ?>
</body>

</html>