SET CHARACTER SET utf8mb4;

USE aeropuerto;

-- Ciudades (id_ciudad INT PRIMARY KEY AUTO_INCREMENT, nombre VARCHAR(30) NOT NULL)
INSERT INTO ciudades (nombre) VALUES ('Madrid');     -- id_ciudad = 1
INSERT INTO ciudades (nombre) VALUES ('Barcelona');  -- id_ciudad = 2
INSERT INTO ciudades (nombre) VALUES ('Bogotá');     -- id_ciudad = 3
INSERT INTO ciudades (nombre) VALUES ('Medellín');   -- id_ciudad = 4
INSERT INTO ciudades (nombre) VALUES ('Ciudad de México'); -- id_ciudad = 5
INSERT INTO ciudades (nombre) VALUES ('Cancún');     -- id_ciudad = 6
INSERT INTO ciudades (nombre) VALUES ('Buenos Aires'); -- id_ciudad = 7
INSERT INTO ciudades (nombre) VALUES ('Santiago');   -- id_ciudad = 8
INSERT INTO ciudades (nombre) VALUES ('Lima');       -- id_ciudad = 9
INSERT INTO ciudades (nombre) VALUES ('Guadalajara');-- id_ciudad = 10

-- Aeropuertos (id_aeropuerto INT PRIMARY KEY AUTO_INCREMENT, nombre VARCHAR(30) NOT NULL, id_ciudad INT NOT NULL)
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Madrid-Barajas (MAD)', 1);        -- ID = 1
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Madrid-Cuatro Vientos', 1);       -- ID = 2
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Barcelona-El Prat (BCN)', 2);     -- ID = 3
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('El Dorado (BOG)', 3);             -- ID = 4
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Aeropuerto Guaymaral', 3);        -- ID = 5
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('J.M. Córdova (MDE)', 4);          -- ID = 6
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Benito Juárez (MEX)', 5);         -- ID = 7
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Gral. Felipe Ángeles (NLU)', 5);  -- ID = 8
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Cancún (CUN)', 6);                -- ID = 9
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Ministro Pistarini (EZE)', 7);    -- ID = 10
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Jorge Newbery (AEP)', 7);         -- ID = 11
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Arturo Merino (SCL)', 8);         -- ID = 12
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Jorge Chávez (LIM)', 9);          -- ID = 13
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Don M. Hgo y C. (GDL)', 10);      -- ID = 14
INSERT INTO aeropuertos (nombre, id_ciudad) VALUES ('Aeropuerto del Norte (MTY)', 10); -- ID = 15

-- Conexiones (id_origen INT NOT NULL, id_destino INT NOT NULL, PRIMARY KEY (id_origen, id_destino), CHECK (id_origen != id_destino))

-- RUTAS PRINCIPALES (18 Conexiones)
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 4); -- MAD -> BOG
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 1); -- BOG -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 7); -- MAD -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 1); -- MEX -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 10); -- MAD -> EZE
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 1); -- EZE -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (3, 7); -- BCN -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 3); -- MEX -> BCN
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 7); -- BOG -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 4); -- MEX -> BOG
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 6); -- BOG -> MDE (Nacional)
INSERT INTO conexiones (id_origen, id_destino) VALUES (6, 4); -- MDE -> BOG (Nacional)
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 9); -- MEX -> CUN (Nacional)
INSERT INTO conexiones (id_origen, id_destino) VALUES (9, 7); -- CUN -> MEX (Nacional)
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 12); -- EZE -> SCL
INSERT INTO conexiones (id_origen, id_destino) VALUES (12, 10); -- SCL -> EZE
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 13); -- MEX -> LIM
INSERT INTO conexiones (id_origen, id_destino) VALUES (13, 7); -- LIM -> MEX

-- CONEXIONES SECUNDARIAS E INTERNACIONALES (20 Conexiones)
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 9);  -- BOG -> CUN
INSERT INTO conexiones (id_origen, id_destino) VALUES (9, 4);  -- CUN -> BOG
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 12); -- MAD -> SCL
INSERT INTO conexiones (id_origen, id_destino) VALUES (12, 1); -- SCL -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 13); -- MAD -> LIM
INSERT INTO conexiones (id_origen, id_destino) VALUES (13, 1); -- LIM -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (3, 10); -- BCN -> EZE
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 3); -- EZE -> BCN
INSERT INTO conexiones (id_origen, id_destino) VALUES (6, 14); -- MDE -> GDL
INSERT INTO conexiones (id_origen, id_destino) VALUES (14, 6); -- GDL -> MDE
INSERT INTO conexiones (id_origen, id_destino) VALUES (8, 9);  -- NLU -> CUN
INSERT INTO conexiones (id_origen, id_destino) VALUES (9, 8);  -- CUN -> NLU
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 13); -- EZE -> LIM
INSERT INTO conexiones (id_origen, id_destino) VALUES (13, 10); -- LIM -> EZE
INSERT INTO conexiones (id_origen, id_destino) VALUES (12, 13); -- SCL -> LIM
INSERT INTO conexiones (id_origen, id_destino) VALUES (13, 12); -- LIM -> SCL
INSERT INTO conexiones (id_origen, id_destino) VALUES (14, 7); -- GDL -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 14); -- MEX -> GDL
INSERT INTO conexiones (id_origen, id_destino) VALUES (12, 4); -- SCL -> BOG
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 12); -- BOG -> SCL

-- RUTAS DE BAJO TRÁFICO Y ALTERNATIVAS (37 Conexiones)
INSERT INTO conexiones (id_origen, id_destino) VALUES (2, 3);  -- Cuatro Vientos -> El Prat
INSERT INTO conexiones (id_origen, id_destino) VALUES (3, 2);  -- El Prat -> Cuatro Vientos
INSERT INTO conexiones (id_origen, id_destino) VALUES (5, 4);  -- Guaymaral -> El Dorado
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 5);  -- El Dorado -> Guaymaral
INSERT INTO conexiones (id_origen, id_destino) VALUES (11, 10); -- Newbery -> Pistarini
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 11); -- Pistarini -> Newbery
INSERT INTO conexiones (id_origen, id_destino) VALUES (15, 7); -- MTY Norte -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 15); -- MEX -> MTY Norte
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 3);  -- MAD -> BCN
INSERT INTO conexiones (id_origen, id_destino) VALUES (3, 1);  -- BCN -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 12); -- MEX -> SCL
INSERT INTO conexiones (id_origen, id_destino) VALUES (12, 7); -- SCL -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 6);  -- MEX -> MDE
INSERT INTO conexiones (id_origen, id_destino) VALUES (6, 7);  -- MDE -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (8, 14); -- NLU -> GDL
INSERT INTO conexiones (id_origen, id_destino) VALUES (14, 8); -- GDL -> NLU
INSERT INTO conexiones (id_origen, id_destino) VALUES (13, 9); -- LIM -> CUN
INSERT INTO conexiones (id_origen, id_destino) VALUES (9, 13); -- CUN -> LIM
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 6);  -- MAD -> MDE
INSERT INTO conexiones (id_origen, id_destino) VALUES (6, 1);  -- MDE -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 14); -- EZE -> GDL
INSERT INTO conexiones (id_origen, id_destino) VALUES (14, 10); -- GDL -> EZE
INSERT INTO conexiones (id_origen, id_destino) VALUES (4, 13); -- BOG -> LIM
INSERT INTO conexiones (id_origen, id_destino) VALUES (13, 4); -- LIM -> BOG
INSERT INTO conexiones (id_origen, id_destino) VALUES (3, 6);  -- BCN -> MDE
INSERT INTO conexiones (id_origen, id_destino) VALUES (6, 3);  -- MDE -> BCN
INSERT INTO conexiones (id_origen, id_destino) VALUES (9, 11); -- CUN -> AEP
INSERT INTO conexiones (id_origen, id_destino) VALUES (11, 9); -- AEP -> CUN
INSERT INTO conexiones (id_origen, id_destino) VALUES (15, 1); -- MTY Norte -> MAD
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 15); -- MAD -> MTY Norte
INSERT INTO conexiones (id_origen, id_destino) VALUES (12, 6); -- SCL -> MDE
INSERT INTO conexiones (id_origen, id_destino) VALUES (6, 12); -- MDE -> SCL
INSERT INTO conexiones (id_origen, id_destino) VALUES (7, 10); -- MEX -> EZE
INSERT INTO conexiones (id_origen, id_destino) VALUES (10, 7); -- EZE -> MEX
INSERT INTO conexiones (id_origen, id_destino) VALUES (1, 14); -- MAD -> GDL
INSERT INTO conexiones (id_origen, id_destino) VALUES (14, 1); -- GDL -> MAD