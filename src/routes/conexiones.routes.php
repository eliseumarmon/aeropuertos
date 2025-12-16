<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
global $pdo;

function buscarVuelosDirectos(PDO $pdo, $origen, $destino) {
    $stmt = $pdo->prepare("SELECT id_origen, a.nombre_aeropuerto AS Origen, id_destino, b.nombre_aeropuerto as Destino FROM conexiones, aeropuertos a, aeropuertos b WHERE id_origen = a.id_aeropuerto AND id_destino = b.id_aeropuerto AND id_origen = ? AND id_destino = ?;");
    $stmt->execute([$origen, $destino]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$app->get("/conexiones", function (Request $req, Response $res) use ($pdo) {
    $stmt = $pdo->query("SELECT id_origen, a.nombre_aeropuerto AS Origen, id_destino, b.nombre_aeropuerto as Destino FROM conexiones, aeropuertos a, aeropuertos b WHERE id_origen = a.id_aeropuerto AND id_destino = b.id_aeropuerto ORDER BY id_origen;");
    $conexiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($conexiones));
    return $res->withHeader("Content-Type", "application/json");
});

$app->get("/conexiones/{from}/{to}", function (Request $req, Response $res, array $args) use ($pdo) {
    $from = $args["from"];
    $to = $args["to"];

    if ($from == $to) {
        $res->getBody()->write(json_encode(["mensaje" => "Origen y destino iguales."]));
        return $res->withStatus(400)->withHeader("Content-Type", "application/json");
    }

    $conexionesDirectas = buscarVuelosDirectos($pdo, $from, $to);
    if (count($conexionesDirectas)) {
        $res->getBody()->write(json_encode($conexionesDirectas));
        return $res->withHeader("Content-Type", "application/json");
    } else {
        $res->getBody()->write(json_encode(["error" => "Conexión directa no encontrada."]));
        return $res->withStatus(404)->withHeader("Content-Type", "application/json");
    }
});

$app->get("/conexiones/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $id = $args["id"];
    $stmt = $pdo->prepare("SELECT ini.id_aeropuerto AS id_origen, ini.nombre_aeropuerto AS nombre_origen,
                                    dest.id_aeropuerto AS id_destino, dest.nombre_aeropuerto AS nombre_destino
                                    FROM conexiones 
                                    INNER JOIN aeropuertos ini ON ini.id_aeropuerto = id_origen
                                    INNER JOIN aeropuertos dest ON dest.id_aeropuerto = id_destino
                                    WHERE id_origen = ?;");
    $stmt->execute([$id]);
    $conexiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$conexiones) {
        $res->getBody()->write(json_encode(["error" => "No se han encontrado destinos."]));
        return $res->withStatus(404)->withHeader("Content-Type", "application/json");
    }
    $res->getBody()->write(json_encode($conexiones));
    return $res->withStatus(200)->withHeader("Content-Type", "application/json");
});

$app->get("/conexiones/escala/{from}/{to}", function (Request $req, Response $res, array $args) use ($pdo) {
    $from = $args["from"];
    $to = $args["to"];

    if ($from == $to) {
        $res->getBody()->write(json_encode(["mensaje" => "Origen y destino son iguales."]));
        return $res->withStatus(400)->withHeader("Content-Type", "application/json");
    }

    $conexionesDirectas = buscarVuelosDirectos($pdo, $from, $to);
    if (count($conexionesDirectas)) {
        $res->getBody()->write(json_encode(["mensaje" => "Existen conexiones directas."]));
        return $res->withStatus(200)->withHeader("Content-Type", "application/json");
    }

    $stmt = $pdo->prepare(
        "SELECT c1.id_origen, ini.nombre_aeropuerto AS Origen, 
                c2.id_origen AS id_escala, escala.nombre_aeropuerto AS Escala, 
                c2.id_destino, dest.nombre_aeropuerto AS Destino 
                FROM conexiones c1 
                INNER JOIN conexiones c2 ON c1.id_destino = c2.id_origen
                INNER JOIN aeropuertos ini ON c1.id_origen = ini.id_aeropuerto
                INNER JOIN aeropuertos escala ON c2.id_origen = escala.id_aeropuerto
                INNER JOIN aeropuertos dest ON c2.id_destino = dest.id_aeropuerto
                WHERE c1.id_origen = ? AND c2.id_destino = ?;"
    );
    $stmt->execute([$from, $to]);
    $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rutas) {
        $res->getBody()->write(json_encode(["mensaje" => "No se han encontrado rutas."]));
        return $res->withStatus(404)->withHeader("Content-Type", "application/json");
    }
    $res->getBody()->write(json_encode($rutas));
    return $res->withStatus(200)->withHeader("Content-Type", "application/json");
});

$app->post("/conexiones", function (Request $req, Response $res, array $args) use ($pdo) {
    $contents = json_decode($req->getBody(), true);
    $origen = $contents["id_origen"];
    $destino = $contents["id_destino"];

    try {
        $pdo->beginTransaction();

        $stmt_ida = $pdo->prepare("INSERT INTO conexiones (id_origen, id_destino) VALUES (?,?);");
        $stmt_ida->execute([$origen, $destino]);
        $stmt_vuelta = $pdo->prepare("INSERT INTO conexiones (id_origen, id_destino) VALUES (?,?);");
        $stmt_vuelta->execute([$destino, $origen]);
        $pdo->commit();

        $res->getBody()->write(json_encode(["mensaje" => "Conexión insertada con éxito"]));
        return $res->withStatus(201)->withHeader("Content-Type", "application/json");
    } catch (PDOException $err) {
        $pdo->rollBack();
        $res->getBody()->write(json_encode(["mensaje" => "Error creando la conexión.", "detalles" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }
});

$app->delete("/conexiones/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idAeropuerto = $args["id"];
    try {
        $stmt = $pdo->prepare("DELETE FROM conexiones WHERE id_destino = ? OR id_origen = ?;");
        $stmt->execute([$idAeropuerto, $idAeropuerto]);
        return $res->withStatus(204);
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["mensaje" => "Error eliminando la conexión."]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }
});