<?php

require __DIR__ . "/../vendor/autoload.php";

use App\db\Usuario;

do {
    $n = readline("Introduce la cantidad de registros que quieres introducir: ");
} while ($n < 1 || $n > 20);

Usuario::generarRegistros($n);
