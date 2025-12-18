<?php
$host     = 'bezzfegrojuoqujfyllu-mysql.services.clever-cloud.com';
$dbname   = 'bezzfegrojuoqujfyllu'; // pon aquí el nombre de la BD de Clever Cloud
$username = 'u5hvv6m6tdetm25i';
$password = 'eLKU7XXHpwdZeuItrZI3';
$port     = '3306';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}