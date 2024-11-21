<?php

namespace App\db;

use \PDO;
use Exception;
use PDOException;
use App\utils\Datos;

class Usuario extends Conexion
{
    private string $nombre;
    private string $email;
    private string $color;
    private string $imagen;

    public function crear(): void
    {
        $q = "insert into users (nombre,email,color,imagen) values (:n,:e,:c,:i)";
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':e' => $this->email,
                ':c' => $this->color,
                ':i' => $this->imagen
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método crear: " . $ex->getMessage(), 1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function generarRegistros(int $cantidad): void
    {
        $faker = \Faker\Factory::create('es_ES');
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker)); // Proovedor de imágenes

        for ($i = 0; $i < $cantidad; $i++) {
            $nombre = $faker->unique()->firstName() . " " . $faker->unique()->lastName();
            $email = str_replace(" ", "", $nombre) . "@" . $faker->freeEmailDomain();
            $color = Datos::getColores()[random_int(0, 2)];
            $imagen = "img/" . $faker->fakeImg(
                dir: './../public/img', // Baja dos directorios porque lo voy a llamar desde scripts
                width: 640,
                height: 480,
                fullPath: false,
                text: $nombre[0] . $nombre[strpos($nombre, " ") + 1],
                backgroundColor: \Mmo\Faker\FakeimgUtils::createColor(random_int(0, 255), random_int(0, 255), random_int(0, 255))
            ); /* Esto devuelve el nombre de la imagen y despues se concantena a img */

            (new Usuario)
                ->setNombre($nombre)
                ->setEmail($email)
                ->setColor($color)
                ->setImagen($imagen)
                ->crear();
        }
    }

    public static function read(?int $id = null) // En el método read le pasaré el id también
    {
        $q = ($id == null) ? "select * from users order by id desc" : "select * from users where id=:i";
        $stmt = parent::getConexion()->prepare($q);
        try {
            ($id == null) ? $stmt->execute() : $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método read " . $ex->getMessage(), 1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update(int $id)
    {
        $q = "update users set nombre = :n, email = :e, color = :c, imagen = :i where id = :id";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':e' => $this->email,
                ':c' => $this->color,
                ':i' => $this->imagen,
                ':id' => $id
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método update " . $ex->getMessage(), 1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function delete(int $id)
    {
        $q  = "delete from users where id=:i";
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute([
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método delete " . $ex->getMessage(), 1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id = null): bool
    {
        /* El nombre se lo paso porque necesito más de un campo
           Mas tarde le pasaré también el id para hacer el update
          Cuando parametrizo el nombre no funciona ? 
        */

        $q = ($id == null) ? "select count(*) as total from users where $nombre = :v" :
                             "select count(*) as total from users where $nombre = :v AND id <> $id";
        $stmt = parent::getConexion()->prepare($q);
        try {
            $stmt->execute([
                ':v' => $valor
            ]);
        } catch (PDOException $ex) {
            throw new Exception("Error en el método existeCampo " . $ex->getMessage(), 1);
        } finally {
            parent::cerrarConexion();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ)[0]->total;
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


    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }


    public function getColor(): string
    {
        return $this->color;
    }


    public function setColor(string $color): self
    {
        $this->color = $color;
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
}
