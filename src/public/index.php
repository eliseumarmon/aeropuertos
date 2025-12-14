<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/Database.php';

$db = new Database();
$pdo = $db->connect();

$app = AppFactory::create();

require __DIR__ . "/../routes/main.routes.php";

$app->run();