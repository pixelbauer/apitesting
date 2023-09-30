<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$openapi = \OpenApi\Generator::scan([__DIR__ . '/../src/routes']);

$app = AppFactory::create();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->safeLoad();

// Register middleware
require __DIR__ . '/../src/middleware.php';


require __DIR__ . '/../src/routes/routes_main.php';

$app->run();