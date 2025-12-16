<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
global $pdo;

$app->get("/paises", function (Request $req, Response $res) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM paises;");
    $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $res->getBody()->write(json_encode($paises));
    return $res->withStatus(200)->withHeader("Content-Type", "application/json");
});

$app->post('/paises', function (Request $req, Response $res) use ($pdo) {
    $data = json_decode($req->getBody(), true);
    $nombre = $data["nombre_pais"];
    $idPais = $data["id_pais"];

    try {
        $stmt = $pdo->prepare("INSERT INTO paises (id_pais, nombre_pais) VALUES (?, ?);");
        $stmt->execute([$idPais, $nombre]);

        $result = [
            "mensaje" => "País agregado correctamente.",
            "contenido" => [
                'id_pais' => $idPais,
                'nombre' => $nombre,
            ]
        ];

        $res->getBody()->write(json_encode($result));
        return $res->withStatus(200)->withHeader("Content-Type", "application/json");
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }
});

$app->put("/paises/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idPais = $args["id"];
    $contents = json_decode($req->getBody(), true);
    try {
        $stmt = $pdo->prepare("UPDATE paises SET nombre_pais = ? WHERE id_pais = ?;");
        $stmt->execute([$contents["nombre_pais"], $idPais]);
        $res->getBody()->write(json_encode(["mensaje" => "Nombre de país cambiado correctamente."]));
        return $res->withStatus(200)->withHeader("Content-Type", "application/json");
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }

});

$app->delete("/paises/{id}", function (Request $req, Response $res, array $args) use ($pdo) {
    $idPais = $args["id"];
    try {
        $stmt = $pdo->prepare("DELETE FROM paises WHERE id_pais = ?;");
        $stmt->execute([$idPais]);
        return $res->withStatus(204);
    } catch (PDOException $err) {
        $res->getBody()->write(json_encode(["error" => $err->getMessage()]));
        return $res->withStatus(500)->withHeader("Content-Type", "application/json");
    }
});