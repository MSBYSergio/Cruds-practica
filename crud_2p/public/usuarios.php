<?php
require __DIR__ . "/../vendor/autoload.php";
session_start();
use App\db\Usuario;
?>

<!DOCTYPE html>
<html lang="en">

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
    <title>Usuarios disponibles</title>
</head>

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <form class="flex justify-center mt-20" action="crear.php" method="POST">
        <button type="submit" name="insertar" class="text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Insertar usuario</button>
    </form>

    <table class="w-1/2 m-auto mt-3 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 rounded-l">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Color
                </th>
                <th scope="col" class="px-20 py-3 rounded-r">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (Usuario::read() as $item) {

                echo <<< TXT
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="{$item->imagen}" alt="{$item->imagen}">
                            <div class="ps-3">
                            <div class="text-base font-semibold">{$item->nombre}</div>
                            <div class="font-normal text-gray-500">{$item->email}</div>
                            </div>
                            </th>
                            <td class="px-6 py-4">
                            {$item->color}
                            </td>
                            <td class="px-6 py-4">
                            <form class="flex justify-evenly" method='POST' action='borrar.php'>
                            <input type="hidden" name="id" value="{$item-> id}" />
                            <a href="update.php?id={$item -> id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit user</a>
                            <button type='submit' value='{$item->id}' name='id' class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
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
                icon: "success",
                title: "{$_SESSION['mensaje']}",
                showConfirmButton: false,
                timer: 1500
            });
                </script>
            TXT;
        unset($_SESSION["mensaje"]);
    }
    ?>
</body>

</html>