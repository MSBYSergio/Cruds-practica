<?php
namespace App\db;

use \PDO;
use \PDOException;
use \Exception;

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

    public static function setConexion() : void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $user = $_ENV['USUARIO'];
        $pass = $_ENV['PASSWORD'];
        $port = $_ENV['PORT'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DATABASE'];

        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4;";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];
        try {
            self::$conexion = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método setConexion " . $ex->getMessage(), -1);
        }
    }

    public static function cerrarConexion() : void {
        self::$conexion = null;
    }
}
