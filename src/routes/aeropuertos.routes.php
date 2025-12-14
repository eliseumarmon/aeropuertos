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

$app->get('/aeropuertos/{id}', function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    $stmt = $pdo->prepare("SELECT * FROM aeropuertos a, ciudades c WHERE id_aeropuerto = ? AND a.id_ciudad = c.id_ciudad;");
    $stmt->execute([$id]);
    $aeropuerto = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($aeropuerto));
    return $res->withHeader("Content-Type", "application/json");
});