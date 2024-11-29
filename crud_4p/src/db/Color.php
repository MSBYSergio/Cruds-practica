<?php

namespace App\db;
use App\utils\Datos;
use Exception;
use PDO;
use PDOException;

class Color extends Conexion
{
    private int $id;
    private string $nombre;

    private static function executeQuery(string $q, $options = [], bool $devolver) {
        $stmt = parent::getConexion() -> prepare($q);
        try {
            count($options) ? $stmt -> execute($options) : $stmt -> execute();
        } catch(PDOException $ex) {
            throw new Exception($ex -> getMessage(),-1);
        } finally {
            parent::cerrarConexion();
        }
        if($devolver) return $stmt;
    }

    public static function crearRegistros() // Método para inicializar la tabla
    {
        foreach (Datos::getColores() as $color) {
            (new Color)
                ->setNombre($color)
                ->create();
        }
    }

    public function create()
    {
        $q = "insert into colores (nombre) values (:n)";
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute([':n' => $this->nombre]);
        } catch (PDOException $ex) {
            throw new Exception("Error al crear los nombres: " . $ex->getMessage(), -1);
        }
    }

    public static function getColorIds(): array // Método para generar el id aleatorio en Usuarios
    {
        $q = "select id from colores";
        $stmt = self::executeQuery($q,[],true);
        foreach($stmt -> fetchAll(PDO::FETCH_OBJ) as $item) {
            $ids[] = $item -> id;
        }
        return $ids;
    }

    public static function getColores() {
        $q = "select id,nombre from colores order by id";
        $stmt = self::executeQuery($q,[],true);
        return $stmt -> fetchAll(PDO::FETCH_OBJ);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }


    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
