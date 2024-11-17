/* Archivo a modo de doumentación para saber cuantas tablas tenemos */
create table articulos (
    id int NOT NULL AUTO_INCREMENT primary key, 
    nombre varchar(100) unique, /* Solamente voy a permitir un nombre único */
    precio float,
    stock int
);
