create table categorias(
    id int AUTO_INCREMENT primary key,
    nombre varchar(40) unique not null
);

create table articulos(
    id int AUTO_INCREMENT primary key,
    nombre varchar(40) unique not null,
    imagen varchar(80) not null,
    descripcion text not null,
    disponible enum("SI", "NO") not null,
    categoria_id int not null,
    constraint fk_art_cat FOREIGN key(categoria_id) REFERENCES categorias(id) on delete cascade
);