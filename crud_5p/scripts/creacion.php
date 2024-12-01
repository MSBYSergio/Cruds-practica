<?php

use App\db\Articulo;
use App\db\Categoria;

require __DIR__ . "/../vendor/autoload.php";

do {
    $n = readline("Introduce la cantidad de registros:\n");
} while ($n < 5 || $n > 25);

Categoria::crearCategorias();
Articulo::crearArticulos($n);
echo "Se han creado $n artículos. \n";
