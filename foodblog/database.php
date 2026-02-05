<?php
declare(strict_types=1);

$host = 'localhost';
$db   = 'foodblog';
$user = 'root';      // pas aan indien nodig
$pass = '';          // pas aan indien nodig
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // geen stille fouten
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // arrays zonder rommel
    PDO::ATTR_EMULATE_PREPARES   => false,                   // echte prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // In productie zou je dit loggen i.p.v. tonen
    http_response_code(500);
    exit('Database connection failed.');
}
