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

    private static function setConexion(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $user = $_ENV["USUARIO"];
        $pass = $_ENV["PASSWORD"];
        $db = $_ENV["DB"];
        $host = $_ENV["HOST"];
        $port = $_ENV["PORT"];
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];
        try {
            self::$conexion = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método establecer conexión: " . $ex->getMessage(), -1);
        }
    }

    public static function cerrarConexion(): void {
        self::$conexion = null;
    }
}
