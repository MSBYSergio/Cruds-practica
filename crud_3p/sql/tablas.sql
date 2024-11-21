CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) UNIQUE NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(100) DEFAULT 'img/default.jpg',
    tipo ENUM('Bazar', 'Alimentacion', 'Limpieza')
);