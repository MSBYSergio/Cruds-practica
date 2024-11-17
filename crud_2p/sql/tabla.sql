create table users (
    id int auto_increment primary key,
    nombre varchar(200) unique not null,
    email varchar(200) unique not null,
    color ENUM("Rojo","Azul","Amarillo") DEFAULT("Amarillo"),
    imagen varchar(200) DEFAULT "img/rana.png"
);