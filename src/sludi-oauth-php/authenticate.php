<?php

require __DIR__ . '/vendor/autoload.php';

use User\SludiOauthPhp\Controller\AuthController;

header('Content-Type: application/json');
$controller = new AuthController();
$controller->authenticate();
