<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
use App\db\Usuario;

// A la hora de editar la contraseña no se modifica si la dejas vacía  si pones algo si
// Recuerda que en el login validas la longitud y a partir de ahí te haces un método específico

$esLogin = false;

if (isset($_SESSION['login'])) {
    $esLogin = true;
    $nombre = $_SESSION['login'][0];
    $perfil = $_SESSION['login'][1];
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

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <h1 class="text-center text-blue-900 font-mono text-xl mt-10 mb-5">Listado de usuarios</h1>
    <div class="mt-4 w-3/4 mx-auto">
        <div class="flex justify-end mb-4">
            <?php if (!$esLogin) : ?>
                <div class="mt-1 mr-20">
                    <h1 class="mr-4 inline text-xl font-mono italic">No logeado</h1>
                    <a href="login.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Ir a login</a>
                </div>
            <?php else : ?>
                <div class="mt-1 mr-20">
                    <h1 class="mr-4 inline text-xl font-mono italic">Bienvenido <?= $nombre ?></h1>
                    <a href="logout.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Cerrar sesión</a>
                </div>
                <a href="register.php" class="p-2 rounded-xl text-white bg-blue-500 hover:bg-blue-800 font-semibold">
                    <i class="fas fa-add mr-2"></i>REGISTRAR
                </a>
            <?php endif; ?>
        </div>
        <div class="m-auto relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Color
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Perfil
                        </th>

                        <th scope="col" class="px-3 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (Usuario::read() as $usuario) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                <img class="rounded-full w-10 h-10" src="<?= $usuario->imagen ?>" alt="<?= $usuario->nombre ?>">
                                <div class="ps-3">
                                    <div class="text-base font-semibold"><?= $usuario->nombre ?></div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                <?= $usuario->nombreColor ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php
                                    $color = match (true) {
                                        ($usuario->perfil == "Administrador") => "bg-red-500",
                                        default => "bg-blue-700"
                                    }
                                    ?>
                                    <div class="h-3 w-3 rounded-full <?= $color ?> me-2"></div> <?= $usuario->perfil ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($esLogin && $perfil == "Normal"): ?>
                                    <form class="flex justify-evenly" method="POST" action='delete.php'>
                                        <input type="hidden" name="id" value="<?= $usuario->id ?>" />
                                        <button type="submit"><i class="fas fa-trash text-xl text-red-500 hover:text-2xl"></i></button>
                                    </form>
                                <?php endif; ?>
                                
                                <?php if ($esLogin && $perfil == "Administrador"): ?>
                                    <form class="flex justify-evenly" method="POST" action='delete.php'>
                                        <input type="hidden" name="id" value="<?= $usuario->id ?>" />
                                        <a href="update.php?id=<?= $usuario->id ?>"><i class="fas fa-edit text-xl hover:text-2xl"></i></a>
                                        <button type="submit"><i class="fas fa-trash text-xl text-red-500 hover:text-2xl"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($_SESSION["mensaje"])) : ?>
            <script>
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "<?= $_SESSION['mensaje'] ?>",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        <?php
            unset($_SESSION['mensaje']);
        endif;
        ?>
</body>

</html>