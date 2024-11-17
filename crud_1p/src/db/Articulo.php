<?php

namespace App\db;

use \Exception;
use PDOException;
use \PDO;

require __DIR__ . "/../../vendor/autoload.php";

class Articulo extends Conexion
{

    private int $id;
    private string $nombre;
    private float $precio;
    private int $stock;

    public function create(): void // Lo hago público para que pueda crear artículos específicos
    {
        $query = ("insert into articulos (nombre,precio,stock) values (:n,:p,:s)");
        $stmt = parent::getConexion()->prepare($query);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':p' => $this->precio,
                ':s' => $this->stock,
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en crear: " . $ex->getMessage(), 1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function crearDatosFaker(int $cantidad)
    {
        $faker = \Faker\Factory::create('es_ES');
        for ($i = 1; $i <= $cantidad; $i++) {
            $nombre = $faker->unique()->word();
            $precio = $faker->randomFloat(3, 1, 10000); // Tres números de parte decimal y después min y max
            $stock = random_int(1, 25);

            (new Articulo)
                ->setNombre($nombre)
                ->setPrecio($precio)
                ->setStock($stock)
                ->create(); // Este método es el que inserta los datos
        }
    }

    public static function existeArticulo(string $nombre, ?int $id = null): bool
    {
        $query = ($id == null) ? "select count(*) as total from articulos where nombre=:n"
            : "select count(*) as total from articulos where nombre =:n AND id <> :i";
        // Si esto devuelve 1 hay un articulo que se llama igual con distinto ID
        $stmt = parent::getConexion()->prepare($query);
        try {
            ($id == null) ? $stmt->execute([':n' => $nombre]) :
                $stmt->execute([
                    ':n' => $nombre,
                    ':i' => $id
                ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método existeArticulo " . $ex->getMessage(), 1);
        }

        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $resultado = $stmt->fetch()->total; // Accedo con la flecha por la connotación de objeto
        return ($resultado); // Esto es 1 si lo encuentra 0 si no lo encuentra
    }

    public static function read(?int $id = null)
    {
        $query = ($id == null) ? "select * from articulos order by id desc"
            : "select * from articulos where id = :i";
        $stmt = parent::getConexion()->prepare($query);
        try {
            ($id == null) ? $stmt->execute() : $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método leer " . $ex->getMessage(), 1);
        }
        // FETCH_ASSOC Connotación de Array
        // FETCH_OBJ Usando la flecha porque devuelve objetos
        // FETCH_CLASS Mapea la clase directamente a Articulo
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function delete(int $id): void
    {
        $query = ("delete from articulos where id=:i"); // Así borro un artículo específico
        $stmt = parent::getConexion()->prepare($query);
        try {
            $stmt->execute([
                ':i' => $id,
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en delete " . $ex->getMessage(), 1);
        }
    }

    public function update(int $id)
    {
        $query = ("update articulos set nombre=:n,precio=:p,stock=:s where id=:i");
        $stmt = parent::getConexion()->prepare($query);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':p' => $this->precio,
                ':s' => $this->stock,
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método update " . $ex->getMessage(), 1);
        }
    }

    /*
    public static function existeArticulo(int $id, string $nombre): bool
    {
        $query = "select count(*) as total from articulos where nombre=:n AND id <>:i";
        $stmt = parent::getConexion()->prepare($query);
        try {
            $stmt->execute([
                ':n' => $nombre,
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método existe artículo " . $ex->getMessage(), 1);
        }
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $total = $stmt->fetch()->total;
        return ($total > 0);
    } */

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


    public function getPrecio(): float
    {
        return $this->precio;
    }


    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }


    public function getStock(): int
    {
        return $this->stock;
    }


    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
