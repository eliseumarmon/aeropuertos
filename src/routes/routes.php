<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

global $pdo;

$app->get('/ciudades', function (Request $req, Response $res) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM ciudades;");
    $ciudades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($ciudades));
    return $res->withHeader('Content-Type', 'application/json');
});

// La especificación de la ruta dinámica con `:` está obsoleta -> usar {} y pasarlo a través del array $args
$app->get('/ciudad/{id}', function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    $stmt = $pdo->prepare("SELECT * FROM ciudades WHERE id_ciudad = ?;");
    $stmt->execute([$id]);
    $ciudad = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($ciudad));
    return $res->withHeader("Content-Type", "application/json");
});

// el modo FETCH_ASSOC sobreescribe columnas con el mismo nombre -> aeropuertos tienen nombre y ciudades tienen nombre por lo que sólo se queda con una
// para evitar eliminar columnas necesarias se debe añadir un alias con `AS`
$app->get('/aeropuertos', function(Request $req, Response $res) use ($pdo) {
    $stmt = $pdo->query("SELECT id_aeropuerto, a.nombre AS aeropuerto, c.nombre AS ciudad FROM aeropuertos a, ciudades c WHERE a.id_ciudad = c.id_ciudad;");
    $aeropuertos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($aeropuertos));
    return $res->withHeader("Content-Type", "application/json");
});

$app->get('/aeropuerto/{id}', function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    $stmt = $pdo->prepare("SELECT id_aeropuerto, a.nombre AS aeropuerto, c.nombre AS ciudad FROM aeropuertos a, ciudades c WHERE id_aeropuerto = ? AND a.id_ciudad = c.id_ciudad;");
    $stmt->execute([$id]);
    $ciudad = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($ciudad));
    return $res->withHeader("Content-Type", "application/json");
});


$app->post('/ciudades', function (Request $req, Response $res) use($pdo){
    $data = json_decode($req->getBody(), true);
    $nombre = $data["nombre"];

    $stmt = $pdo->prepare("INSERT INTO ciudades (nombre) VALUES(?);");
    $stmt->execute([$nombre]);

    $result = [
        'id_ciudad' => $pdo->lastInsertId(),
        'nombre' => $nombre
    ];

    $res->getBody()->write(json_encode($result));
    return $res->withHeader("Content-Type", "application/json");
});

$app->post('/aeropuertos', function (Request $req, Response $res) use ($pdo)  {
    $data = json_decode($req->getBody(), true);
    $nombre = $data["nombre"];
    $ciudad = $data["id_ciudad"];

    $stmt = $pdo->prepare("INSERT INTO aeropuertos (nombre, id_ciudad) VALUES(?,?);");
    $stmt->execute([$nombre, $ciudad]);

    $result = [
        'id_aeropuerto' => $pdo->lastInsertId(),
        'nombre' => $nombre,
        'id_ciudad' => $ciudad
    ];

    $res->getBody()->write(json_encode($result));
    return $res->withHeader("Content-Type", "application/json");
});