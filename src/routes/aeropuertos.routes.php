<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
global $pdo;

$app->get('/aeropuertos', function (Request $req, Response $res) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM aeropuertos a, ciudades c WHERE a.id_ciudad = c.id_ciudad;");
    $aeropuertos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($aeropuertos));
    return $res->withHeader("Content-Type", "application/json");
});

$app->get('/aeropuertos/{id}/conexiones', function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    // $stmt = $pdo->prepare("SELECT id_origen, a.nombre AS Origen, id_destino, b.nombre as Destino FROM conexiones, aeropuertos a, aeropuertos b WHERE id_origen = ? AND id_origen = a.id_aeropuerto AND id_destino = b.id_aeropuerto;");
    $stmt = $pdo->prepare("SELECT id_origen, nombre_aeropuerto, codigo_iata, nombre_ciudad FROM conexiones INNER JOIN aeropuertos ON id_origen = id_aeropuerto INNER JOIN ciudades ON aeropuertos.id_ciudad = ciudades.id_ciudad WHERE id_origen = ?;");
    $stmt->execute([$id]);
    $origen = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$origen) {
        $res->getBody()->write(json_encode(["error" => "Ciudad de origen no válida."]));
        return $res->withHeader("Content-Type", "application/json");
    }
    $stmt_destinos = $pdo->prepare("SELECT id_destino, nombre_aeropuerto, codigo_iata, nombre_ciudad FROM conexiones INNER JOIN aeropuertos ON id_destino = id_aeropuerto INNER JOIN ciudades ON aeropuertos.id_ciudad = ciudades.id_ciudad WHERE id_origen = ?;");
    $stmt_destinos->execute([$id]);
    $destinos = $stmt_destinos->fetchAll(PDO::FETCH_ASSOC);

    $origen["destinos"] = $destinos;

    $res->getBody()->write(json_encode($origen));
    return $res->withHeader("Content-Type", "application/json");
});

$app->get('/aeropuertos/{id}', function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    $stmt = $pdo->prepare("SELECT * FROM aeropuertos a, ciudades c WHERE id_aeropuerto = ? AND a.id_ciudad = c.id_ciudad;");
    $stmt->execute([$id]);
    $aeropuerto = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($aeropuerto));
    return $res->withHeader("Content-Type", "application/json");
});

$app->get("/aeropuertos/{id}/conexiones/escala", function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];

    $stmt = $pdo->prepare(
        "SELECT c1.id_origen, ini.nombre_aeropuerto AS Origen, 
                c2.id_origen AS id_escala, escala.nombre_aeropuerto AS Escala, 
                c2.id_destino, dest.nombre_aeropuerto AS Destino 
                FROM conexiones c1 
                INNER JOIN conexiones c2 ON c1.id_destino = c2.id_origen
                INNER JOIN aeropuertos ini ON c1.id_origen = ini.id_aeropuerto
                INNER JOIN aeropuertos escala ON c2.id_origen = escala.id_aeropuerto
                INNER JOIN aeropuertos dest ON c2.id_destino = dest.id_aeropuerto
                WHERE c1.id_origen = ? AND c1.id_origen != c2.id_destino;"
    );

    $stmt->execute([$id]);
    $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $res->getBody()->write(json_encode($rutas));
    return $res->withStatus(200)->withHeader("Content-Type", "application/json");
});

$app->post("/aeropuertos", function (Request $req, Response $res) use ($pdo) {
    $contents = json_decode($req->getBody(), true);
    $idAeropuerto = $contents["id_aeropuerto"];
    $nombreAeropuerto = $contents["nombre_aeropuerto"];
    $iata = $contents["codigo_iata"];
    $idCiudad = $contents["id_ciudad"];

    $stmtCiudad = $pdo->prepare("SELECT * FROM ciudades WHERE id_ciudad = ?;");
    $stmtCiudad->execute([$idCiudad]);
    $ciudad = $stmtCiudad->fetch(PDO::FETCH_ASSOC);

    if (!$ciudad) {
        $res->getBody()->write(json_encode(["error" => "La ciudad no existe."]));
        return $res->withStatus(404)->withHeader("Contet-Type", "application/json");
    }

    $stmt = $pdo->prepare("INSERT INTO aeropuertos (id_aeropuerto, nombre_aeropuerto, codigo_iata, id_ciudad) VALUES (?,?,?,?);");
    $stmt->execute([$idAeropuerto, $nombreAeropuerto, $iata, $idCiudad]);

    $result = [
        "mensaje" => "Aeropuerto creado correctamente",
        "contenido" => [
            "id_aeropuerto" => $idAeropuerto,
            "nombre_aeropuerto" => $nombreAeropuerto,
            "codigo_iata" => $iata,
            "id_ciudad" => $idCiudad
        ]
    ];

    $res->getBody()->write(json_encode($result));
    return $res->withStatus(200)->withHeader("Content-Type", "application/json");
});

$app->put("/aeropuertos/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idAeropuerto = $args["id"];
    $contents = json_decode($req->getBody(), true); // Más corto que json_decode($req->getBody(), true)

    try {
        $stmt = $pdo->prepare("SELECT * FROM aeropuertos WHERE id_aeropuerto = ?;");
        $stmt->execute([$idAeropuerto]);
        $original = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }

    $nuevoNombre = $contents["nombre_aeropuerto"] ?? $original["nombre_aeropuerto"];
    $nuevoIata = $contents["codigo_iata"] ?? $original["codigo_iata"];
    $nuevoIdCiudad = $contents["id_ciudad"] ?? $original["id_ciudad"];

    // TODO: getCiudadPorId($id)
    $stmtCiudad = $pdo->prepare("SELECT * FROM ciudades WHERE id_ciudad = ?;");
    $stmtCiudad->execute([$nuevoIdCiudad]);
    $ciudad = $stmtCiudad->fetch(PDO::FETCH_ASSOC);

    if (!$ciudad) {
        $res->getBody()->write(json_encode(["error" => "La ciudad no existe."]));
        return $res->withStatus(404)->withHeader("Contet-Type", "application/json");
    }

    $stmtUpdate = $pdo->prepare("UPDATE aeropuertos SET nombre_aeropuerto = ?, codigo_iata = ?, id_ciudad = ? WHERE id_aeropuerto = ?;");
    $stmtUpdate->execute([$nuevoNombre, $nuevoIata, $nuevoIdCiudad, $idAeropuerto]);

    $result = [
        "mensaje" => "Aeropuerto modificado correctamente",
        "contenido" => [
            "id_aeropuerto" => $idAeropuerto,
            "nombre_aeropuerto" => $nuevoNombre,
            "codigo_iata" => $nuevoIata,
            "id_ciudad" => $nuevoIdCiudad
        ]
    ];

    $res->getBody()->write(json_encode($result));
    return $res->withStatus(200)->withHeader("Contet-Type", "application/json");

});

$app->delete("/aeropuertos/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idAeropuerto = $args["id"];
    $stmtConexiones = $pdo->prepare("SELECT id_origen, id_destino FROM conexiones 
                                    WHERE id_origen = ? OR id_destino = ?;");
    $stmtConexiones->execute([$idAeropuerto, $idAeropuerto]);
    $conexiones = $stmtConexiones->fetchAll(PDO::FETCH_ASSOC);
   
    if (!$conexiones) {
        try {
            $stmt = $pdo->prepare("DELETE FROM aeropuertos WHERE id_aeropuerto = ?;");
            $stmt->execute([$idAeropuerto]);
            // $res->getBody()->write(json_encode(["mensaje" => "Aeropuerto borrado con éxito."])); // Lo ponemos con código 200
            return $res->withStatus(204); //->withHeader("Content-Type", "application/json");

        } catch (PDOException $err) {
            $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
            return $res->withStatus(500)->withHeader("Content-Type", "application/json");
        }
    }

    $res->getBody()->write(json_encode(["error" => ["Debes eliminar primero estas conexiones:" => $conexiones]]));
    return $res->withStatus(400)->withHeader("Content-Type", "application/json");
});