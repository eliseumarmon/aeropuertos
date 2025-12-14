SET NAMES utf8mb4;
DROP DATABASE IF EXISTS aeropuerto;
CREATE DATABASE IF NOT EXISTS aeropuerto;
USE aeropuerto;

-- 1. Tabla Paises
CREATE TABLE paises (
    id_pais INT PRIMARY KEY,
    nombre_pais VARCHAR(100) NOT NULL
);

-- 2. Tabla Ciudades
CREATE TABLE ciudades (
    id_ciudad INT PRIMARY KEY,
    nombre_ciudad VARCHAR(100) NOT NULL,
    id_pais INT NOT NULL,
    CONSTRAINT fk_ciudades_paises FOREIGN KEY (id_pais) REFERENCES paises(id_pais)
);

-- 3. Tabla Aeropuertos
CREATE TABLE aeropuertos (
    id_aeropuerto INT PRIMARY KEY,
    nombre_aeropuerto VARCHAR(100) NOT NULL,
    codigo_iata VARCHAR(3),
    id_ciudad INT NOT NULL,
    CONSTRAINT fk_aeropuertos_ciudades FOREIGN KEY (id_ciudad) REFERENCES ciudades(id_ciudad)
);

-- 4. Tabla Conexiones
CREATE TABLE conexiones (
    id_origen INT NOT NULL,
    id_destino INT NOT NULL,
    PRIMARY KEY (id_origen, id_destino),
    CONSTRAINT fk_con_origen FOREIGN KEY (id_origen) REFERENCES aeropuertos(id_aeropuerto),
    CONSTRAINT fk_con_destino FOREIGN KEY (id_destino) REFERENCES aeropuertos(id_aeropuerto),
    CONSTRAINT chk_dif_airport CHECK (id_origen != id_destino)
);