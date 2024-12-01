<?php

namespace App\db;

use \PDO;
use PDOException;
use PDOStatement;

class Categoria extends Conexion
{
    const CATEGORIAS = ['Entretenimiento', 'Bootcamp', 'Bazar', 'Limpieza', 'Variados'];
    private int $id;
    private string $nombre;

    private static function executeQuery(string $q, $options = [], bool $devolver)
    {
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute($options);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        if ($devolver) return $stmt;
    }

    public static function crearCategorias() : void
    {
        foreach (self::CATEGORIAS as $categoria) {
            (new self)
                ->setNombre($categoria)
                ->create();
        }
    }

    public function create() : void
    {
        $q = "insert into categorias (nombre) values (:n)";
        self::executeQuery($q, [':n' => $this->nombre], false);
    }

    public static function categoriasIds() {
        $q = "select id from categorias order by id";
        $stmt = self::executeQuery($q,[],true);
        foreach ($stmt -> fetchAll(PDO::FETCH_OBJ) as $item) {
            $ids[] = $item -> id;
        }
        return $ids;
    }

    public static function getCategorias() {
        $q = "select nombre,id from categorias";
        $stmt = self::executeQuery($q,[],true);
        return $stmt -> fetchAll(PDO::FETCH_OBJ);
    }
    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
