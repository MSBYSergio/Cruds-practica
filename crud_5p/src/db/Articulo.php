<?php

namespace App\db;

use PDO;
use PDOException;

class Articulo extends Conexion
{
    const DISPONIBILIDAD = ['SI', 'NO'];

    private int $id;
    private string $nombre;
    private string $imagen;
    private string $descripion;
    private string $disponible;
    private int $categoria_id;


    private static function executeQuery(string $q, $options = [], bool $devolver)
    {
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute($options);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), -1);
        }
        if ($devolver) return $stmt;
    }

    public function create()
    {
        $q = "insert into articulos (nombre,imagen,descripcion,disponible,categoria_id) values (:n,:i,:d,:di,:c)";
        $options = [
            ':n' => $this->nombre,
            ':i' => $this->imagen,
            ':d' => $this->descripion,
            ':di' => $this->disponible,
            ':c' => $this->categoria_id,
        ];
        self::executeQuery($q, $options, false);
    }

    public static function crearArticulos(int $cantidad): void // Método que se va a llamar desde scripts
    {
        $faker = \Faker\Factory::create('es_ES');
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        for ($i = 1; $i <= $cantidad; $i++) {
            $nombre = $faker->unique()->text(10);
            $text = $text = strtoupper($nombre[0] . $nombre[(strpos($nombre, " ") + 1)]);
            $imagen = $faker->fakeImg("./../public/img", 300, 300, true, $text, backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]);
            $disponible = $faker->randomElement(self::DISPONIBILIDAD);
            $descripion = $faker->text(30);
            $categoria_id = $faker->randomElement(Categoria::categoriasIds());

            (new Articulo)
                ->setNombre($nombre)
                ->setImagen($imagen)
                ->setDescripion($descripion)
                ->setDisponible($disponible)
                ->setCategoriaId($categoria_id)
                ->create();
        }
    }

    public static function read()
    {
        $q = "select articulos.*, categorias.nombre as nomcat from articulos,categorias where articulos.categoria_id = categorias.id order by articulos.id DESC";
        $stmt = self::executeQuery($q, [], true);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id = null): bool
    {
        $q = is_null($id) ? "select count(*) as total from articulos where $nombre = :v" : "select count(*) as total from articulos where $nombre =:v AND id != :i";
        $options = is_null($id) ? [':v' => $valor] : [':v' => $valor, ':i' => $id];
        $stmt = self::executeQuery($q, $options, true);
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public static function getArticuloById(?int $id)
    {
        $q = "select * from articulos where id = :i";
        $stmt = self::executeQuery($q, [':i' => $id], true);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getImagenById(int $id)
    {
        $q = "select imagen from articulos where id=:i";
        $stmt = self::executeQuery($q, [':i' => $id], true);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function delete(int $id)
    {
        $q = "delete from articulos where id = :i";
        self::executeQuery($q, [':i' => $id], false);
    }

    public function update(int $id)
    {
        $q = "update articulos set nombre = :n, imagen = :i, descripcion = :d, disponible = :di, categoria_id = :ci where id = :id";
        $options = [':n' => $this->nombre, ':i' => $this->imagen, ':d' => $this->descripion, ':di' => $this->disponible, ':ci' => $this->categoria_id, ':id' => $id];
        self::executeQuery($q, $options, false);
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

    /**
     * Get the value of imagen
     */
    public function getImagen(): string
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of descripion
     */
    public function getDescripion(): string
    {
        return $this->descripion;
    }

    /**
     * Set the value of descripion
     */
    public function setDescripion(string $descripion): self
    {
        $this->descripion = $descripion;

        return $this;
    }

    /**
     * Get the value of disponible
     */
    public function getDisponible(): string
    {
        return $this->disponible;
    }

    /**
     * Set the value of disponible
     */
    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Get the value of categoria_id
     */
    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    /**
     * Set the value of categoria_id
     */
    public function setCategoriaId(int $categoria_id): self
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }
}
