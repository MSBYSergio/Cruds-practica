
create table colores (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(40) UNIQUE NOT NULL
);

create table usuarios (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) unique not null,
    password varchar(80) not NULL,
    color_id int not null,
    perfil enum("Normal","Administrador"),
    imagen varchar(150) DEFAULT "img/rana.png",
    constraint fk_color_id FOREIGN key(color_id) REFERENCES colores(id) on delete cascade
);