<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";

use App\db\Articulo;


if (isset($_POST['disponible'])) {

    $id = filter_input(INPUT_POST, 'id');
    $articulo = Articulo::getArticuloById($id);
    $cambio = ($articulo -> disponible == "SI") ? "NO" : "SI";
    (new Articulo)
        ->setNombre($articulo->nombre)
        ->setImagen($articulo->imagen)
        ->setDescripion($articulo->descripcion)
        ->setDisponible($cambio)
        ->setCategoriaId($articulo->categoria_id)
        ->update($id);
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
    <h1 class="text-center font-mono text-xl mt-5 mb-6">Listado de productos</h1>

    <div class="flex justify-center">
        <a href="nuevo.php" class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-6">Insertar producto</a>
    </div>

    <table class="w-1/2 m-auto text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Producto
                </th>
                <th scope="col" class="px-6 py-3">
                    Descripcion
                </th>
                <th scope="col" class="px-6 py-3">
                    Categoría
                </th>
                <th scope="col" class="px-6 py-3">
                    Disponible
                </th>
                <th scope="col" class="px-6 py-3">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (Articulo::read() as $producto): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <img class="w-10 h-10 rounded-full" src="<?= $producto->imagen ?>" alt="<?= $producto->nombre ?>">
                        <div class="ps-3">
                            <div class="text-base font-semibold"><?= $producto->nombre ?></div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        <?= $producto->descripcion ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center"> <?= $producto->nomcat ?> </div>
                    </td>
                    <td class="px-8 py-4">
                        <?php $color = match (true) {
                            $producto->disponible == "NO" => "bg-red-400",
                            $producto->disponible == "SI" => "bg-blue-400",
                            default => "bg-cyan-400"
                        } ?>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="hidden" name="id" value="<?= $producto->id ?>">
                            <button type="submit" name="disponible" class="text-white font-mono rounded-lg <?= $color ?> border-radius py-2.5 px-6 text-center"><?= $producto->disponible ?></button>
                        </form>
                    </td>

                    <td class="px-6 py-4">
                        <!-- Aquí van los iconos de eliminar y actualizar -->
                        <form action="delete.php" method="POST">
                            <input type="hidden" name="id" value="<?= $producto->id ?>">
                            <a href="update.php?id=<?= $producto->id ?>"><i class="fas fa-edit text-blue-500 text-xl hover:text-2xl"></i></a>
                            <button type="submit"><i class="fas fa-trash text-red-500 text-xl hover:text-2xl"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php if (isset($_SESSION['mensaje'])): ?>
        <script>
            Swal.fire({
                title: "<?= $_SESSION['mensaje'] ?>",
                text: "Acción satisfactoria",
                icon: "success"
            });
        </script>
    <?php
        unset($_SESSION['mensaje']);
    endif;
    ?>
</body>

</html>