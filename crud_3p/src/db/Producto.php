<?php

namespace App\db;

require __DIR__ . "/../../vendor/autoload.php";

use App\utils\Datos;
use Exception;
use \PDO;
use PDOException;

class Producto extends Conexion
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private string $imagen;
    private string $tipo; // Podrá ser Bazar, Alimentación y limpieza


    public static function read(?int $id = null): array
    {
        $q = ($id == null) ? "select * from productos order by id desc" : "select * from productos where id = :i";
        $stmt = parent::getConexion()->prepare($q);
        try {
            ($id == null) ? $stmt->execute() : $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método read " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function crearRegistros(int $cantidad)
    {
        for ($i = 0; $i < $cantidad; $i++) {
            $faker = \Faker\Factory::create();
            $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));
            $nombre = $faker->unique()->text(8);
            $descripcion = $faker->text(30);
            $imagen = $faker->image(dir: "../public/img", width: 300, height: 300);
            $tipo = Datos::getTipos()[random_int(0, 2)];

            (new self)
                ->setNombre($nombre)
                ->setDescripcion($descripcion)
                ->setImagen($imagen)
                ->setTipo($tipo)
                ->crear();
        }
    }

    public function crear()
    {
        $q = "insert into productos (nombre,descripcion,imagen,tipo) values (:n,:d,:i,:t)";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion,
                ':i' => $this->imagen,
                ':t' => $this->tipo
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método crear " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function delete(int $id)
    {
        $q = "delete from productos where id = :i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método borrar " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id = null): array | bool
    {
        $q = ($id == null) ? "select count(*) as total from productos where $nombre = :v" : "select count(*) as total from productos where $nombre = :v AND id <> $id";
        $stmt = parent::getConexion()->prepare($q);
        try {
            ($id == null) ? $stmt->execute([':v' => $valor]) :
                $stmt->execute([
                    ':v' => $valor]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método existe campo " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ)[0]->total;
    }

    public static function readId(): array
    {
        $q = "select id from productos";
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            throw new Exception("Error en el método leer id " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        $datos = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ids = [];
        foreach ($datos as $item) {
            $ids[] = $item->id;
        }
        return $ids;
    }

    public function update(int $id): void
    {
        $q = "update productos set nombre =:n, descripcion =:d, tipo =:t, imagen = :i where id = :id";
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion,
                ':t' => $this->tipo,
                ':i' => $this->imagen,
                ':id' => $id
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método leer id " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
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

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getImagen(): string
    {
        return $this->imagen;
    }


    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }


    public function getTipo(): string
    {
        return $this->tipo;
    }


    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
