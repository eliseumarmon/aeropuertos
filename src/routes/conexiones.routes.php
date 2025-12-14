<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
global $pdo;

$app->get("/conexiones", function(Request $req, Response $res) use ($pdo) {
    $stmt = $pdo->query("SELECT id_origen, a.nombre_aeropuerto AS Origen, id_destino, b.nombre_aeropuerto as Destino FROM conexiones, aeropuertos a, aeropuertos b WHERE id_origen = a.id_aeropuerto AND id_destino = b.id_aeropuerto ORDER BY id_origen;");
    $conexiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($conexiones));
    return $res->withHeader("Content-Type", "application/json");
});

$app->get("/conexiones/{from}/{to}", function(Request $req, Response $res, array $args) use ($pdo) {
    $from = $args["from"];
    $to = $args["to"];
    $stmt = $pdo->prepare("SELECT id_origen, a.nombre_aeropuerto AS Origen, id_destino, b.nombre_aeropuerto as Destino FROM conexiones, aeropuertos a, aeropuertos b WHERE id_origen = a.id_aeropuerto AND id_destino = b.id_aeropuerto AND id_origen = ? AND id_destino = ?;");
    $stmt->execute([$from, $to]);

    $conexiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($conexiones)) {
        $res->getBody()->write(json_encode($conexiones));
        return $res->withHeader("Content-Type", "application/json");
    }
    else {
    $res->getBody()->write(json_encode(["error" => "Conexión directa no encontrada."]));
        return $res->withStatus(404)->withHeader("Content-Type", "application/json");
    }
});

