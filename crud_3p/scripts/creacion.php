<?php
require __DIR__ . "/../vendor/autoload.php";
use App\db\Producto;
Producto::crearRegistros(25);
echo "\nRegistros creados correctamente.";



