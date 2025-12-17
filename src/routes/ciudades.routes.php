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

$app->post("/ciudades", function (Request $req, Response $res) use ($pdo) {
    $contents = json_decode($req->getBody(), true);
    $idCiudad = $contents["id_ciudad"];
    $nombreCiudad = $contents["nombre_ciudad"];
    $idPais = $contents["id_pais"];

    try {
        $stmt = $pdo->prepare("INSERT INTO ciudades VALUES (?, ?, ?);");
        $stmt->execute([$idCiudad, $nombreCiudad, $idPais]);
        $nuevaCiudad = ["id_ciudad" => $idCiudad, "nombre_ciudad" => $nombreCiudad, "id_pais" => $idPais];

        $res->getBody()->write(json_encode(["mensaje" => "Ciudad insertada con éxito", "ciudad" => $nuevaCiudad]));
        return $res->withStatus(201)->withHeader("Content-Type", "application/json");
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }

});
$app->put("/ciudades/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idCiudad = $args["id"];
    $contents = json_decode($req->getBody(), true);

    try {
        $stmtCiudadOriginal = $pdo->prepare("SELECT * FROM ciudades WHERE id_ciudad = ?;");
        $stmtCiudadOriginal->execute([$idCiudad]);
        $ciudadOriginal = $stmtCiudadOriginal->fetch(PDO::FETCH_ASSOC);

        $nuevoNombre = $contents["nombre_ciudad"] ?? $ciudadOriginal["nombre_ciudad"];
        $nuevoIdPais = $contents["id_pais"] ?? $ciudadOriginal["id_pais"];

        $stmtUpdate = $pdo->prepare("UPDATE ciudades SET nombre_ciudad = ?, id_pais = ? WHERE id_ciudad = ?;");
        $stmtUpdate->execute([$nuevoNombre, $nuevoIdPais, $idCiudad]);

        $ciudadModificada = ["id_ciudad" => $idCiudad, "nombre_ciudad" => $nuevoNombre, "id_pais" => $nuevoIdPais];
        $res->getBody()->write(json_encode(["mensaje" => "Ciudad modificada con éxito", "ciudad" => $ciudadModificada]));
        return $res->withStatus(200)->withHeader("Content-Type", "application/json");
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }
});

$app->delete("/ciudades/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idCiudad = $args["id"];
    $stmtAeropuertos = $pdo->prepare("SELECT id_aeropuerto FROM aeropuertos WHERE id_ciudad = ?;");
    $stmtAeropuertos->execute([$idCiudad]);
    $aeropuertos = $stmtAeropuertos->fetchAll(PDO::FETCH_ASSOC);

    if (!$aeropuertos) {
        try {
            $stmt = $pdo->prepare("DELETE FROM ciudades WHERE id_ciudad = ?;");
            $stmt->execute([$idCiudad]);
            return $res->withStatus(204);

        } catch (PDOException $err) {
            $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
            return $res->withStatus(500)->withHeader("Content-Type", "application/json");
        }
    }

    $res->getBody()->write(json_encode(["error" => ["Debes eliminar primero estos aeropuertos:" => $aeropuertos]]));
    return $res->withStatus(400)->withHeader("Content-Type", "application/json");
});