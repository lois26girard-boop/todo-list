<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_todo');
define('DB_USER', 'nom d\'utilisateur');
define('DB_PASS', 'mot de passe');

function getPDO(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    return $pdo;
}