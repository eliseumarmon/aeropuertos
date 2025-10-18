DROP DATABASE IF EXISTS aeropuerto;

CREATE DATABASE IF NOT EXISTS aeropuerto;

USE aeropuerto;

CREATE TABLE ciudades (
    id_ciudad INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(30)
);

CREATE TABLE aeropuertos (
    id_aeropuerto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(30),
    id_ciudad INT,
    CONSTRAINT fk_aeropuertos_ciudades
        FOREIGN KEY (id_ciudad) REFERENCES ciudades(id_ciudad)
);