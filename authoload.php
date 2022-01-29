<?php
# Authoload function for authomaticly including
spl_autoload_register(function (string $class) {
    $path =  __DIR__ . "/{$class}.php";
    require_once $path;
});
