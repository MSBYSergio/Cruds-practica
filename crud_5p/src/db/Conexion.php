<?php

namespace App\db;

use \PDO;
use \PDOException;

class Conexion
{
    private static ?PDO $conexion = null;


    public static function getConexion(): ?PDO
    {
        if (self::$conexion === null) {
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $usuario = $_ENV['USUARIO'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];
        $port = $_ENV['PORT'];
        $db = $_ENV['DB'];

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];

        try {
            self::$conexion = new PDO($dsn, $usuario, $pass, $options);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), -1);
        }
    }

    public static function cerrarConexion() : void {
        self::$conexion = null;
    }
}
