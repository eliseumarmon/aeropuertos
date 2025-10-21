<?php
require __DIR__ . '/../src/vendor/autoload.php';
require './../src/db.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$db = new Database();
$pdo = $db->connect();


/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
*/

$app = AppFactory::create();

require '../src/routes/routes.php';

// Define global endpoint
$app->get("/", function (Request $req, Response $res) {
    return $res;
});

// Run app
$app->run();