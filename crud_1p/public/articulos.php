<?php
include __DIR__ . "/../vendor/autoload.php";

session_start(); // Se me olvidaba poner el session_start() para el mensaje de script

use App\db\Articulo;

/* Para eliminar un articulo,no es recomendable hacerlo con una etiqueta <a> 
   lo suyo sería hacerlo con un input hidden dentro de un formulario  
*/

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
    <!-- CDN Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Artículos disponibles</title>
</head>

<body class="bg-red-200">
    <h1 class="w-1/2 m-auto mt-20 font-mono text-xl italic">Listado de artículos</h1>
    <!-- El botón lo hago un formulario porque necesito saber si se ha pulsado o no -->
    <div class="w-1/2 m-auto mt-5">
        <form method="post" action="insertar.php">
            <button type="submit" name="insertar" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Insertar artículo</button>
        </form>
    </div>

    <div class="w-1/2 m-auto mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="rounded-sm px-6 py-2">
                        Nombre
                    </th>
                    <th class="rounded-sm px-6 py-2">
                        Precio
                    </th>
                    <th class="rounded-sm px-6 py-2">
                        Stock
                    </th>
                    <th class="rounded-sm px-6 py-2">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach (Articulo::read() as $item) {
                    echo <<< TXT
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {$item->nombre}
                                </td>
                                <td class="px-6 py-4">
                                {$item->precio}
                                </td>
                                <td class="px-6 py-4">
                                {$item->stock}
                                </td>
                                <td>
                                    <form action='borrar.php' method='POST' class='flex justify-evenly'>
                                        <input type='hidden' name='id' value='{$item -> id}'/>
                                        <a href='update.php?id={$item -> id}'> <i class="fa-solid fa-highlighter"> </i> </a>
                                        <button type='submit'> <i class="fa-sharp fa-solid fa-trash"> </i> </button>
                                    </form>
                                </td>
                                </tr>
                        TXT;
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    if (isset($_SESSION["mensaje"])) {
        echo <<< TXT
                    <script>
                    Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "{$_SESSION['mensaje']}",
                    showConfirmButton: false,
                    timer: 1500
                    });
                    </script>
            TXT;
        unset($_SESSION["mensaje"]); // Para que no aparezca mas
    }
    ?>
</body>

</html>