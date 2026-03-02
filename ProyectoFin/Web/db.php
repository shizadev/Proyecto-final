<?php

$host = 'base_de_datos';
$dbname = 'ProyectoFin';
$username = 'usuario';
$password = 'password_seguro_123';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>