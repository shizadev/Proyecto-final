-- # Listo

CREATE DATABASE ProyectoFin

USE ProyectoFin

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario TEXT NOT NULL,
    falla TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    solucionado BOOLEAN DEFAULT FALSE
);

