<?php
// paso4/app/bootstrap.php
// Inicialización global para la app (BD, autoload, helpers)
// --- Conexión PDO (ajusta usuario/clave si es necesario) ---
$host = '127.0.0.1';
$db = 'dwes_mvc_ej';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
 $pdo = new PDO($dsn, $user, $pass, [
 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
 ]);
} catch (PDOException $e) {
 // En entorno didáctico mostramos el error
 die("Error de conexión a la base de datos: " . $e->getMessage());
}
// --- Autoload simple para controllers y models ---
spl_autoload_register(function($class) {
 $paths = [
 __DIR__ . '/controllers/' . $class . '.php',
 __DIR__ . '/models/' . $class . '.php',
 ];
 foreach ($paths as $file) {
 if (file_exists($file)) {
 require_once $file;
 return;
 }
 }
});
// función helper para escapar HTML
if (!function_exists('esc')) {
 function esc($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}
