<?php

namespace App\db;

use PDO;
use PDOException;

class Libro extends Conexion
{

    const LIBROS = [
        'El Ultimo Refugio',
        'La Ciudad de los Sueños',
        'El Jardín de la Memoria',
        'La Llamada del Pasado',
        'El Secreto de la Montaña',
        'La Historia Olvidada',
        'El Futuro de la Energía'
    ];

    private int $id;
    private string $nombre;

    public static function executeQuery(string $q, array $options = [], $devolver)
    {
        $stmt = parent::getConexion()->prepare($q);
        try {
            count($options) ? $stmt->execute($options) : $stmt->execute();
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), -1);
        }
        if ($devolver) return $stmt;
    }

    public static function crearLibros()
    {
        foreach (self::LIBROS as $item) {
            (new Libro)
                ->setNombre($item)
                ->create();
        }
    }

    public function create()
    {
        $q = "insert into libros (nombre) values (:n)";
        self::executeQuery($q, [':n' => $this -> nombre], false);
    }

    public static function getLibrosIds() {
        $q = "select id from libros";
        $stmt = self::executeQuery($q,[],true);
        $ids = [];
        foreach($stmt -> fetchAll(PDO::FETCH_OBJ) as $item) {
            $ids[] = $item -> id;
        }      
        return $ids;
    }

    public static function getLibros() {
        $q = "select id,nombre from libros";
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
