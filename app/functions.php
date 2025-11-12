<?php
function dd(mixed ...$args): void
{
    echo '<pre>';
    foreach ($args as $arg) {
        print_r($arg);
        echo "\n";
    }
    echo '</pre>';
    die();
}


function achievement_config()
{
    $config = require __DIR__ . '/achievement_config.php';
    return $config;
}