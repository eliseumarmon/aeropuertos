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