<?php

namespace App\db;

use PDO;
use PDOException;

class User extends Conexion
{

    const PERFILES = ['Admin', 'Normal'];

    private int $id;
    private int $libro_id;
    private string $username;
    private string $email;
    private string $pass;
    private string $perfil;
    private string $imagen;

    private static function executeQuery(string $q, $options, bool $devolver)
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
        $q = "insert into users (libro_id,username,email,pass,perfil,imagen) values (:li,:u,:e,:pass,:per,:i)";
        try {
            $options = [':li' => $this->libro_id, ':u' => $this->username, ':e' => $this->email, ':pass' => $this->pass, ':per' => $this->perfil, ':i' => $this->imagen];
            self::executeQuery($q, $options, false);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), -1);
        }
    }

    public static function crearRegistrosFaker(int $cantidad)
    {

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        for ($i = 0; $i < $cantidad; $i++) {
            $libro_id = $faker->randomElement(Libro::getLibrosIds());
            $username = $faker->userName();
            $email = str_replace(" ", "", $username) . "@" . $faker->freeEmailDomain();
            $perfil = $faker->randomElement(User::PERFILES);
            $text = strtoupper(substr($username, 0, 2));
            $imagen =  "img/" . $faker->fakeImg(dir: "./../public/img", width: 400, height: 400, fullPath: false, text: $text, backgroundColor: \Mmo\Faker\FakeimgUtils::createColor(random_int(0, 255), random_int(0, 255), random_int(0, 255)));

            (new self)
                ->setLibroId($libro_id)
                ->setUsername($username)
                ->setEmail($email)
                ->setPass('secret0')
                ->setPerfil($perfil)
                ->setImagen($imagen)
                ->create();
        }
    }

    public static function read()
    {
        $q = "select users.*,libros.nombre as nomLib from users,libros where users.libro_id = libros.id order by nomLib";
        $stmt = self::executeQuery($q, [], true);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function existeCampo(string $nombre, string $valor, ?int $id = null): bool
    {
        $q = is_null($id) ? "select count(*) as total from users where $nombre = :v" : "select count(*) as total from users where $nombre = :v AND id <> :i";
        $options = is_null($id) ? [':v' => $valor] : [':v' => $valor, ':i' => $id];
        $stmt = self::executeQuery($q, $options, true);
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public static function getUserById(int $id)
    {
        $q = "select * from users where id = :i";
        $stmt = self::executeQuery($q, [':i' => $id], true);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update(int $id, string $pass)
    {
        if (empty($pass)) {
            $q = "update users set libro_id = :li, username = :u, email = :e, perfil = :pe, imagen = :i where id = :id";
            $parametros = [':li' => $this->libro_id, ':u' => $this->username, ':e' => $this->email, ':pe' => $this->perfil, ':i' => $this->imagen, ':id' => $id];
        } else {
            $q = "update users set libro_id = :li, username = :u, email = :e, pass = :p, perfil = :pe, imagen = :i where id = :id";
            $parametros = [':li' => $this->libro_id, ':u' => $this->username, ':e' => $this->email, ':p' => $this->pass, ':pe' => $this->perfil, ':i' => $this->imagen, ':id' => $id];
        }
        self::executeQuery($q, $parametros, false);
    }

    public static function delete(int $id)
    {
        $q = "delete from users where id = :i";
        self::executeQuery($q, [':i' => $id], false);
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
     * Get the value of libro_id
     */
    public function getLibroId(): int
    {
        return $this->libro_id;
    }

    /**
     * Set the value of libro_id
     */
    public function setLibroId(int $libro_id): self
    {
        $this->libro_id = $libro_id;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of pass
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * Set the value of pass
     */
    public function setPass(string $pass): self
    {
        $this->pass = password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }

    /**
     * Get the value of perfil
     */
    public function getPerfil(): string
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     */
    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

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
}
