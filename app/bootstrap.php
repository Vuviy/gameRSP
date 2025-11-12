<?php

use App\Base\CheckerRegistry;
use App\Base\Container;
use App\Base\ViewFactory;
use App\Core\Achievement\FiveWinsStreakChecker;
use App\Core\Achivment\TenWinsChecker;

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$container =  Container::getInstance();
$container->set(ViewFactory ::class, function (){
    return new ViewFactory();
});

$container->set(CheckerRegistry::class, function () {
    $registry = new CheckerRegistry();

    $registry->register('10_wins', new TenWinsChecker());
    $registry->register('5_streak', new FiveWinsStreakChecker());

    return $registry;
});
