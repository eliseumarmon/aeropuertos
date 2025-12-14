USE aeropuerto;

-- 1. Trigger: Conexiones entre ciudades prohibidas
DELIMITER //
CREATE TRIGGER bloquear_vuelos_internos_ciudad
BEFORE INSERT ON conexiones
FOR EACH ROW
BEGIN
    DECLARE ciudad_origen INT;
    DECLARE ciudad_destino INT;
    SELECT id_ciudad INTO ciudad_origen FROM aeropuertos WHERE id_aeropuerto = NEW.id_origen;
    SELECT id_ciudad INTO ciudad_destino FROM aeropuertos WHERE id_aeropuerto = NEW.id_destino;
    IF ciudad_origen = ciudad_destino THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Vuelo dentro de la misma ciudad';
    END IF;
END //
DELIMITER ;