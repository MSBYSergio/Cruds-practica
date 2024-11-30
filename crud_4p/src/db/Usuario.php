<?php

namespace App\db;

use App\utils\Datos;
use Exception;
use PDO;
use PDOException;
use stdClass;

class Usuario extends Conexion
{

    private int $id;
    private string $nombre;
    private string $password;
    private int $color_id;
    private string $perfil;
    private string $imagen;

    private static function executeQuery($q, $options = [], $devolver)
    {
        $stmt = parent::getConexion()->prepare($q);
        try {
            count($options) ? $stmt->execute($options) : $stmt->execute();
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        if ($devolver) {
            return $stmt;
        }
    }

    public function create()
    {
        $q = "insert into usuarios (nombre,password,color_id,perfil,imagen) values (:n,:pass,:c,:p,:i)";
        self::executeQuery($q, [
            ':n' => $this->nombre,
            ':pass' => $this->password,
            ':c' => $this->color_id,
            ':p' => $this->perfil,
            ':i' => $this->imagen
        ], false);
    }

    public static function crearUsuarios(int $cantidad)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));
        for ($i = 0; $i < $cantidad; $i++) {
            $nombre = $faker->unique()->userName();
            $color_id = $faker->randomElement(Color::getColorIds());
            $perfil = $faker->randomElement(Datos::getPerfiles());
            $imagen = $faker->image(dir: "../public/img", width: 250, height: 250);

            (new Usuario)
                ->setNombre($nombre)
                ->setPassword('secret0', false)
                ->setColorId($color_id)
                ->setPerfil($perfil)
                ->setImagen($imagen)
                ->create();
        }
    }

    public static function read()
    {
        // Recuerda que el as es para referirse a ese atributo, con el nombre de la tabla y su punto accedo a esa columna
        $q = "select usuarios.*,colores.nombre as nombreColor from usuarios,colores where color_id = colores.id order by usuarios.id DESC";
        $stmt = self::executeQuery($q, [], true);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id = null)
    {
        $q = is_null($id) ? "select count(*) as total from usuarios where $nombre = :v"
            : "select count(*) as total from usuarios where $nombre = :v AND id <> :i";
        $parametros = is_null($id) ? [':v' => $valor] : [':v' => $valor, ':i' => $id];
        $stmt = self::executeQuery($q, $parametros, true);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt->fetch()->total;
    }

    public static function getUsuarioById(?int $id): stdClass | bool
    {
        $q = "select * from usuarios where id = :i";
        $stmt = self::executeQuery($q, [':i' => $id], true);
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        return (count($resultado)) ? $resultado[0] : false;
    }

    public function update(int $id, string $password): void
    {
        if (empty($password)) { // Encontes aquí en el update, si está vacia no la modifico
            $q = "update usuarios set nombre=:n, color_id =:c, perfil =:p, imagen =:i where id = :id";
            $parametros = [':n' => $this->nombre, ':c' => $this->color_id, ':p' => $this->perfil, ':i' => $this->imagen, ':id' => $id];
        } else { // Si tiene contenido entonces la hasheo y la actualizo
            $q = "update usuarios set nombre =:n, password =:pass, color_id =:c,perfil =:p,imagen =:i where id = :id";
            $parametros = [':n' => $this->nombre, ':pass' => $this->setPassword($password, true), ':c' => $this->color_id, ':p' => $this->perfil, ':i' => $this->imagen, ':id' => $id];
        }
        self::executeQuery($q, $parametros, false);
    }

    public static function delete($id): void
    {
        $q = "delete from usuarios where id = :i";
        self::executeQuery($q, [':i' => $id], false);
    }

    public static function esLoginValido(string $nombre, string $password)
    {
        // Puede ser que el fetch directamente si no lo encontraba devolviera false (?)

        $q = "select nombre,perfil,password from usuarios where nombre = :n"; // Pilla el perfil para luego mas tarde saber si es Administrador o Normal
        $stmt = self::executeQuery($q, [':n' => $nombre], true);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $array = $stmt->fetch();

        if (!$array) return false; // Si no existe ningún usuario me voy
        if (!password_verify($password, $array->password)) return false; // Si la contraseña no coincide con su hash me voy

        return [$nombre, $array->perfil]; // Aquí los datos son válidos
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password, bool $esCadena): self | string 
    {
        // Si es true devuelvo la cadena hasheada sino, devuelvo la cadena para asignarla como parámetro en el update cuando no está vacía
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return ($esCadena) ? $this->password : $this;
    }

    public function getColorId(): int
    {
        return $this->color_id;
    }

    public function setColorId(int $color_id): self
    {
        $this->color_id = $color_id;

        return $this;
    }


    public function getPerfil(): string
    {
        return $this->perfil;
    }


    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

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
