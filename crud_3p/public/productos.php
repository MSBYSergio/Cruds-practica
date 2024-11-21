<?php
require __DIR__ . "/../vendor/autoload.php";
session_start();
use App\db\Producto;
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
    <!-- CDN SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Listado de productos</title>
</head>

<body class="bg-gradient-to-r from-blue-500 via-grey to-black">
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <h1 class="text-center text-2xl font-mono text-white">Listado de productos</h1>
            <a href="nuevo.php"> <!-- Con el uso de un etiqueta a me quito el problema de comprobarlo dentro de insertar (me daba problemas) -->
                <button type="button" name="insertar" class="flex justify-end text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 m-auto">Insertar nuevo producto</button>
            </a>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Producto
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach (Producto::read() as $producto) {
                                echo <<< TXT
                                            <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                            <img class="w-full h-full rounded-full"
                                                src="{$producto->imagen}"
                                                alt="" />
                                            </div>
                                            <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                            {$producto->nombre}
                                            </p>
                                            </div>
                                            </div>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{$producto->descripcion}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                            {$producto->tipo}
                                            </p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm>
                                                <input type="hidden" name="id" value="{$producto->id}"/>
                                                <a href="update.php?id={$producto->id}"><i class="fas fa-edit text-xl hover:text-2xl"></i></a>
                                                <form method="POST" action="delete.php">
                                                <button type="submit" name='id' value='{$producto->id}' ><i class="fas fa-trash text-xl text-red-500 hover:text-2xl"></i></button>
                                                </form>
                                            </td>
                                            </tr>
                                    TXT;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
        if(isset($_SESSION['mensaje'])){
            echo <<<TXT
                <script>
                Swal.fire({
                icon: "success",
                title: "{$_SESSION['mensaje']}",
                showConfirmButton: false,
                timer: 1500
            });
                </script>
            TXT;
            unset($_SESSION['mensaje']);
        }
    ?>
</body>

</html>