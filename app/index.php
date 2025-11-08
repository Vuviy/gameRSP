<?php

use App\Controllers\GameController;

require __DIR__ . '/functions.php';
require __DIR__ . '/bootstrap.php';


session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$controller = new GameController();

if ($uri === '/') {
    $controller->index();
} else if ($uri === '/game') {
    $controller->play();
}