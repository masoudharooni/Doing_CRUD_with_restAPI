<?php
# Authoload function for authomaticly including
spl_autoload_register(function (string $class) {
    $path =  __DIR__ . "/{$class}.php";
    if (!(file_exists($path) and is_readable($path)))
        throw new Exception("File not exist", 1);
    require_once $path;
});
