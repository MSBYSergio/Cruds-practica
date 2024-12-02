<?php

use App\db\Libro;
use App\db\User;
require __DIR__ . "/../vendor/autoload.php";
Libro::crearLibros();
User::crearRegistrosFaker(10);
echo "Se han creado los registros.";