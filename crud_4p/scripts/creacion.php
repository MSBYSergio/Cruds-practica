<?php
// Recuerda siempre que tienes que inicializar los datos que pertenezcan a la relación N 
use App\db\Usuario;
require __DIR__ . "/../vendor/autoload.php";

Usuario::crearUsuarios(1);

