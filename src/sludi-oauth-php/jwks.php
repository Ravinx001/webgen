<?php
require __DIR__ . '/vendor/autoload.php';

use User\SludiOauthPhp\Controller\AuthController;

$controller = new AuthController();
$controller->jwks();
