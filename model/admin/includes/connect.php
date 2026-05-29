<?php

$configFile = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

if (file_exists($configFile)) {
    $db = require $configFile;
} else {
    $db = [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'user' => getenv('DB_USER') ?: '',
        'pass' => getenv('DB_PASS') ?: '',
        'name' => getenv('DB_NAME') ?: '',
    ];
}

$conn = mysqli_connect(
    $db['host'],
    $db['user'],
    $db['pass'],
    $db['name']
);

if (!$conn) {
    die("Konekcija nije uspjela: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
