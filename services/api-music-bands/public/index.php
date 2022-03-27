<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/php_config.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$settings = require __DIR__ . '/../config/settings.php';
$app = new Slim\App([
    'settings' => $settings
]);

(require __DIR__ . '/../config/error_handler.php')($app);
(require __DIR__ . '/../config/routes.php')($app);
$app->run();
