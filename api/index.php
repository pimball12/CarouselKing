<?php

include_once 'core.php';

use Src\Api;
use Src\Helpers\Util;

try {

    if (trim($uri) == '') {

        Util::fd('API is running..', true);
    }

    if (trim($uri) == 'test') {

        (new Api())->test();
        die();
    }

    $uri = explode('/', rtrim($uri, '/'));

    if (count($uri) < 2) {

        throw new Exception('Invalid route.');
    }

    $class = 'Src\\Controllers\\' . ucfirst(Util::toSingular($uri[0]));
    $class = str_replace('-', '', ucwords($class, '-'));
    $method = $uri[1]; 
    $method = str_replace('-', '', ucwords($method, '-'));
    $args = array_slice($uri, 2);
    
    // Util::fd([$class, $method, $args, class_exists($class)], true);

    if (!class_exists($class)) {
        
        throw new Exception('Invalid route.');
    }
    
    $reflection = new ReflectionMethod($class, $method);

    if ($reflection->isStatic() || !$reflection->isPublic()) {

        throw new Exception('Invalid route.');
    }

    (new $class())->$method(...$args);

} catch (ReflectionException | Exception $e) {

    http_response_code(404);
    exit('404 | Route undefined | ' . $e->getMessage());
}