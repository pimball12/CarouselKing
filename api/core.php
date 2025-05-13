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

$dir = __DIR__ . '/src/Controllers';
$rii = new RecursiveDirectoryIterator($dir);
foreach($rii as $file) {

    if ($file->isDir()){

        continue;
    }

    require_once $file->getPathname();
}

use Src\Api;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = str_replace('/' . $_ENV['PROJECT_FOLDER'] . '/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

Api::cors();