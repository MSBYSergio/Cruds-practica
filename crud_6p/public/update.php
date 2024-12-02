<?php
session_start();

use App\utils\Utilidades;
use App\db\Libro;
use App\db\User;

require __DIR__ . "/../vendor/autoload.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location:usuarios.php");
    die();
}

if (!$user = User::getUserById($id)) {
    header("Location:usuarios.php");
    die();
}

if (isset($_POST["submit"])) {
    $username = Utilidades::limpiarCadena($_POST['username']);
    $email = Utilidades::limpiarCadena($_POST['email']);
    $password = Utilidades::limpiarCadena($_POST['password']);
    $libro_id = Utilidades::limpiarCadena($_POST['libro_id']);
    $perfil = isset($_POST['perfil']) ? Utilidades::limpiarCadena($_POST['perfil']) : -1;

    $errores = false;

    if (!Utilidades::esLongitudValida("username", $username, 3, 15)) {
        $errores = true;
    } else if (Utilidades::existeCampo("username", $username, $id)) {
        $errores = true;
    }

    if (!Utilidades::esEmailValido($email)) {
        $errores = true;
    } else if (Utilidades::existeCampo("email", $email, $id)) {
        $errores = true;
    }

    if (!Utilidades::esLibroIdValido($libro_id)) {
        $errores = true;
    }

    if (!Utilidades::esPerfilValido($perfil)) {
        $errores = true;
    }

    if ($errores) {
        header("Location:" . $_SERVER['PHP_SELF'] . "?id=$id");
        die();
    }

    $imagen = $user->imagen;

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        if (!Utilidades::esImagenValida($_FILES['imagen']['type'], $_FILES['imagen']['size'])) {
            $errores = true;
        } else {
            $imagen = "img/" . uniqid() . "_" . $_FILES['imagen']['name'];
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
                $_SESSION['err_imagen'] = "No se ha podido mover la imagen.";
                $errores = true;
            }
        }
    }

    (new User)
        ->setUsername($username)
        ->setEmail($email)
        ->setPass($password)
        ->setLibroId($libro_id)
        ->setPerfil($perfil)
        ->setImagen($imagen)
        ->update($id, $password);

    $antigua = $user->imagen;

    if ($antigua != $imagen) {
        if (basename($antigua) != "default.png") {
            unlink($antigua);
        }
    }

    $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
    header("Location:usuarios.php");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-purple-200 p-8">
    <h3 class="py-2 text-center text-xl">Editar Usuario</h3>
    <div class="w-1/2 mx-auto border-2 rounded-xl p-4 shadow-xl border-black">
        <form method="POST" action="<?= $_SERVER['PHP_SELF'] . "?id=$id" ?>" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" id="username" name="username" value="<?= $user->username ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?= Utilidades::mostrarError("err_username"); ?>
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="text" id="email" name="email" value="<?= $user->email ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <?= Utilidades::mostrarError("err_email"); ?>
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="*******">
                <?= Utilidades::mostrarError("err_password"); ?>
            </div>

            <div class="mb-4">
                <label for="libro_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría Artículo</label>
                <select id="libro_id" name="libro_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <?php foreach (Libro::getLibros() as $item) : ?>
                        <?php $estado = ($item->id == $user->libro_id) ? "selected" : "" ?>
                        <option value="<?= $item->id ?>"> <?= $item->nombre ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?= Utilidades::mostrarError("err_libro_id") ?>

            <div class="mb-4">
                <label for="perfil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Perfil</label>
                <div class="flex">
                    <?php
                    $checkNormal = ($user->perfil == "Normal") ? "checked" : "";
                    $checkAdmin = ($user->perfil == "Admin") ? "checked" : ""; ?>
                    <div class="flex items-center me-4">
                        <input id="Normal" type="radio" <?= $checkNormal ?> value="Normal" name="perfil" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="Normal" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Normal</label>
                    </div>
                    <div class="flex items-center me-4">
                        <input id="Admin" type="radio" <?= $checkAdmin ?> value="Admin" name="perfil" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="Admin" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Admin</label>
                    </div>
                </div>
                <?= Utilidades::mostrarError("err_perfil"); ?>
            </div>

            <div class="mb-4">
                <img class="rounded-full w-20 h-20" src="<?= $user->imagen ?>" id="imgpreview" alt="image description">
                <input class="mt-3" type="file" name="imagen" accept="image/*" oninput="imgpreview.src=window.URL.createObjectURL(this.files[0])">
            </div>

            <div class="flex flex-row-reverse mb-2">
                <button type="submit" name="submit" class="font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-save mr-2"></i>GUARDAR
                </button>
                <button type="reset" class="mr-2 font-bold text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-paintbrush mr-2"></i>RESET
                </button>
                <a href="articulos.php" class="mr-2 font-bold text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <i class="fas fa-home mr-2"></i>VOLVER
                </a>
            </div>

        </form>

    </div>
</body>

</html>