<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

spl_autoload_register(function ($class) {

    $path = str_replace('\\', '/', $class) . '.php';

    if (is_file($path)) {
        require $path;
        return;
    }

    throw new \LogicException(sprintf('Class "%s" not found in "%s"', $class, $path));
});

require __DIR__ . '/func.php';