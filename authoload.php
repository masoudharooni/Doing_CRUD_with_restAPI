<?php
# JWT Token constant
const USERS = [
    ["id" => 1, "name" => "Masoud", "sirName" => "Harooni", 'email' => "masoudharooni50@gmail.com"],
    ["id" => 2, "name" => "Ali", "sirName" => "Alizadeh", 'email' => 'ali@gmail.com'],
    ["id" => 3, "name" => "Asghar", "sirName" => "Asghary", 'email' => 'asghar@gmail.com']
];
const JWT_KEY = "MasoudaasdfasdfasdfqwedfaszxcvfgqwaHarooniasdfasdfreqoiwefjasd";
const JWT_ALG = "HS256";
# Cache constant
const CACHE_DIR = __DIR__ . "/cache";
# Authoload function for authomaticly including
spl_autoload_register(function (string $class) {
    $path =  __DIR__ . "/{$class}.php";
    if (!(file_exists($path) and is_readable($path)))
        throw new Exception("File not exist", 1);
    require_once $path;
});
require_once "vendor/autoload.php";
