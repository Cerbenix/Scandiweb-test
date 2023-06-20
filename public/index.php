<?php declare(strict_types=1);

$container = require_once '../bootstrap.php';

$router = $container->get(App\Core\Router::class);
$response = $router->getResponse();

if ($response instanceof \App\Core\JsonResponse) {
    header('Content-Type: application/json');
    echo($response->getData());
} else {
    header('Content-Type: text/html');
    require_once('dist/index.html');
}


