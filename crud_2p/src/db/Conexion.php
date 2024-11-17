<?php

namespace App\db;

use Exception;
use \PDO;
use PDOException;

class Conexion
{
    private static ?PDO $conexion = null;

    public static function getConexion(): ?PDO
    {
        if (self::$conexion == null) {
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $usuario = $_ENV['USUARIO'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];
        $port = $_ENV['PORT'];

        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];

        try {
            self::$conexion = new PDO($dsn, $usuario, $pass, $options);
        } catch (PDOException $ex) {
            throw new Exception("Error al establecer la conexión: " . $ex->getMessage(), 1);
        }
    }

    public static function cerrarConexion() {
        self::$conexion = null;
    }
}
