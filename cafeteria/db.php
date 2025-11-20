<?php
// ---------- CONFIGURACIÓN DE LA BD (ajusta si tu usuario/clave difieren) ------
$host = '127.0.0.1';
$db = 'cafeteria_db';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
       
    ]);
    echo "se conecta";
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

