<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use DI\ContainerBuilder;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$containerBuilder = new ContainerBuilder();

$container = $containerBuilder->build();

return $container;