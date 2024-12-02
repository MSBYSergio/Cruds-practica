<?php
require __DIR__ . "/../vendor/autoload.php";
session_start();

use App\db\User;

$usuarios = User::read();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-purple-200 p-4">
    <h3 class="py-2 text-center text-xl">Listados de Usuarios</h3>
    <div class="relative overflow-x-auto">
        <div class="flex justify-center mb-4">
            <a href="nuevo.php" class="p-2 rounded-xl bg-green-500 hover:bg-green-700">
                <i class="fas fa-add mr-2"></i>NUEVO
            </a>
        </div>
        <table class="w-1/2 m-auto text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Imagen
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Username
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Libro
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Perfil
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($usuarios as $item) : ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">
                            <img src="<?= $item->imagen ?>" class="w-10 h-10 rounded-full" />
                        </td>
                        <td class="px-6 py-4"> <?= $item->username ?> </td>

                        <td class="px-6 py-4">
                            <?= $item->email ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $item->nomLib ?>
                        </td>
                        <td class="px-6 py-4"> <?= $item->perfil ?></td>
                        <td class="px-6 py-4">
                            <form action='borrar.php' method='POST'>
                                <input type='hidden' name='id' value='<?= $item->id ?>' />
                                <a href="update.php?id=<?= $item->id ?>">
                                    <i class='fas fa-edit text-blue-500 hover:text-xl mr-2'></i>
                                </a>
                                <button type='submit'>
                                    <i class='fas fa-trash text-red-500 hover:text-xl'></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_SESSION['mensaje'])) : ?>
        <script>
            Swal.fire({
                position: "center",
                icon: "success",
                title: "<?= $_SESSION['mensaje'] ?>",
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    <?php
        unset($_SESSION['mensaje']);
    endif; ?>

</body>

</html>