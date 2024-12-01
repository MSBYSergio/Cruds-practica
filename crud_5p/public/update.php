<?php
require __DIR__ . "/../vendor/autoload.php";
session_start();

use App\db\Articulo;
use App\db\Categoria;
use App\utils\Utilidades;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location:articulos.php");
    die();
}

if (!$articulo = Articulo::getArticuloById($id)) {
    header("Location:articulos.php");
    die();
}

if (isset($_POST['submit'])) {

    $nombre = Utilidades::limpiarCadena($_POST["nombre"]);
    $descripcion = Utilidades::limpiarCadena($_POST["descripcion"]);
    $disponible = isset($_POST["disponible"]) ? Utilidades::limpiarCadena($_POST["disponible"]) : -1;
    $categoria = Utilidades::limpiarCadena($_POST["categoria"]);

    $errores = false;

    if (!Utilidades::esCadenaValida("nombre", $nombre, 3, 40)) {
        $errores = true;
    } else if (Utilidades::existeCampo("nombre", $nombre, $id)) {
        $errores = true;
    }

    if (!Utilidades::esCadenaValida("descripcion", $descripcion, 3, 250)) {
        $errores = true;
    }

    if (!Utilidades::esDisponibleValido($disponible)) {
        $errores = true;
    }

    if (!Utilidades::esCategoriaValida($categoria)) {
        $errores = true;
    }

    $imagen = $articulo->imagen;

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        if (!Utilidades::esImagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
            $errores = true;
        } else {
            $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name'];
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
                $_SESSION["err_imagen"] = "No se ha podido mover la imagen.";
                $errores = true;
            }
        }
    }

    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }

    (new Articulo)
        ->setNombre($nombre)
        ->setImagen($imagen)
        ->setDescripion($descripcion)
        ->setDisponible($disponible)
        ->setCategoriaId($categoria)
        ->update($id);

    $antigua = $articulo->imagen;

    if ($imagen != $antigua) {
        if ($antigua != "default.png") {
            unlink($antigua);
        }
    }

    $_SESSION["mensaje"] = "Articulo actualizado correctamente.";
    header("Location:articulos.php");
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de productos</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%">
    <form class="max-w-sm mx-auto" action="<?= $_SERVER['PHP_SELF'] . "?id=$id" ?>" method="POST" enctype="multipart/form-data">
        <h1 class="text-center font-mono text-xl mt-5 mb-5">Actualizar producto</h1>

        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= $articulo->nombre ?>" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" />
            <?= Utilidades::mostrarErrores("err_nombre"); ?>
        </div>

        <div class="mb-5">
            <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="¿En qué consiste el producto?"><?= $articulo->descripcion ?></textarea>
            <?= Utilidades::mostrarErrores("err_descripcion"); ?>
        </div>

        <div class="mb-5">
            <label for="group" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Disponibilidad</label>
            <div class="flex items-center me-4 mr-3">
                <?php foreach (Articulo::DISPONIBILIDAD as $item) : ?>
                    <?php $estado = ($articulo->disponible == $item) ? "checked" : ""; ?>
                    <input id="<?= $item ?>" type="radio" name="disponible" value="<?= $item ?>" <?= $estado ?> class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="<?= $item ?>" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 mr-4"> <?= $item ?> </label>
                <?php endforeach; ?>
            </div>
            <?= Utilidades::mostrarErrores("err_disponible"); ?>
        </div>

        <div class="mb-5">
            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categorías</label>
            <select id="categorias" name="categoria" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option>Elige una categoría</option>
                <?php foreach (Categoria::getCategorias() as $item) : ?>
                    <?php $estado = ($articulo->categoria_id == $item->id) ? "selected" : ""; ?>
                    <option value="<?= $item->id ?>" <?= $estado ?>> <?= $item->nombre ?> </option>
                <?php endforeach; ?>
            </select>
            <?= Utilidades::mostrarErrores("err_categoria"); ?>
        </div>
        <div class="mb-5">
            <img class="rounded-full w-20 h-20" src="<?= $articulo->imagen ?>" id="imgpreview" alt="image description">
            <input class="mt-3" type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])" />
            <?= Utilidades::mostrarErrores("err_imagen"); ?>
        </div>
        <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Actualizar producto</button>
    </form>

</body>

</html>