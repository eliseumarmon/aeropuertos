DROP DATABASE IF EXISTS aeropuerto;

CREATE DATABASE IF NOT EXISTS aeropuerto;

USE aeropuerto;

CREATE TABLE ciudades (
    id_ciudad INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(30) NOT NULL
);

CREATE TABLE aeropuertos (
    id_aeropuerto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(30) NOT NULL,
    id_ciudad INT NOT NULL,
    CONSTRAINT fk_aeropuertos_ciudades
        FOREIGN KEY (id_ciudad) REFERENCES ciudades(id_ciudad)
);

CREATE TABLE conexiones (
    id_origen INT NOT NULL,
    id_destino INT NOT NULL,
    PRIMARY KEY (id_origen, id_destino),
    CONSTRAINT fk_conexiones_aeropuertos_origen
        FOREIGN KEY (id_origen) REFERENCES aeropuertos(id_aeropuerto),
    CONSTRAINT fk_conexiones_aeropuertos_destino
        FOREIGN KEY (id_destino) REFERENCES aeropuertos(id_aeropuerto),
    CONSTRAINT CHK_aeropuertos
        CHECK (id_origen != id_destino)
);