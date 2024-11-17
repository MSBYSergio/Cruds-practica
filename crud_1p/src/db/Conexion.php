<?php

namespace App\db;

use \PDO;
use \PDOException;

class Conexion
{
    private static ?PDO $conexion = null;

    public static function getConexion()
    {
        if (self::$conexion === null) {
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../"); // Esto busca el archivo .env
        $dotenv->load();
        $user = $_ENV['USUARIO'];
        $port = $_ENV['PORT'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];
        $pass = $_ENV['PASSWORD'];
        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];
        try {
            self::$conexion = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $ex) {
            throw new PDOException("Error en conexion: " . $ex->getMessage(), -1);
        }
    }

    public static function cerrarConexion()
    {
        self::$conexion = null;
    }
}
