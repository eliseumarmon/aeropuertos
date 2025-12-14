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

$app->get('/ciudades/{id}', function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    $stmt = $pdo->prepare("SELECT * FROM ciudades WHERE id_ciudad = ?;");
    $stmt->execute([$id]);
    $ciudad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ciudad) {
        $res->getBody()->write(json_encode(['error' => 'Ciudad no encontrada']));
        return $res->withStatus(404)->withHeader("Content-Type", "application/json");
    }

    $stmt_aeropuerto = $pdo->prepare("SELECT * FROM aeropuertos WHERE id_ciudad = ?;");
    $stmt_aeropuerto->execute([$id]);
    $aeropuertos = $stmt_aeropuerto->fetchAll(PDO::FETCH_ASSOC);

    $ciudad["aeropuertos"] = $aeropuertos;

    $res->getBody()->write(json_encode($ciudad));
    return $res->withHeader("Content-Type", "application/json");
});
