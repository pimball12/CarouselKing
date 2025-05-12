<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Api.php';
require_once __DIR__ . '/src/Helpers/LocalStore.php';
require_once __DIR__ . '/src/Helpers/Util.php';
require_once __DIR__ . '/src/Database/Database.php';
require_once __DIR__ . '/src/Auth/JWTManager.php';

use Src\Api;
use Dotenv\Dotenv;
use Src\Helpers\Util;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = str_replace('/' . $_ENV['PROJECT_FOLDER'] . '/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Util::fd($uri);
// die();

try {

    $reflection = new ReflectionMethod(Api::class, $uri);

    if ($reflection->isStatic() || !$reflection->isPublic()) {

        throw new Exception('Invalid route.');
    }

    (new Api())->$uri();
} catch (ReflectionException | Exception $e) {

    http_response_code(404);
    exit('404 | Route undefined | ' . $e->getMessage());
}