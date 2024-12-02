create table users(
    id int auto_increment primary key,
    libro_id int not null,
    username varchar(50) unique not null,
    imagen varchar(80) not null,
    email varchar(60) unique not null,
    pass varchar(200) not null,
    perfil enum("Admin", "Normal") default "Normal",
    constraint fk_libros_id Foreign Key (libro_id) REFERENCES libros(id) on delete cascade
);

create table libros (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) unique not NULL  
);