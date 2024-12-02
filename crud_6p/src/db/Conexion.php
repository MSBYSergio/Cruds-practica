<?php

namespace App\db;

use \PDO;
use PDOException;

class Conexion
{
    private static ?PDO $conexion = null;

    protected static function getConexion() : ?PDO
    {
        if (is_null(self::$conexion)) {
            self::setConexion();
        }
        return self::$conexion;
    }
    
    private static function setConexion()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $usuario = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $port = $_ENV['PORT'];
        $db = $_ENV['DB'];
        $options =[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT=>true
        ];
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

        try {
            self::$conexion = new PDO($dsn,$usuario,$pass,$options);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), -1);
        }
    }

    protected static function cerrarConexion() : void {
        self::$conexion = null;
    }
}
