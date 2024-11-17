<?php

use App\db\Articulo;

require __DIR__ . "/../../vendor/autoload.php";

do {
    $cantidad = readline("Introduce la cantidad de artículos que quieres crear:");
} while ($cantidad < 0 || $cantidad > 20);

Articulo::crearDatosFaker($cantidad);