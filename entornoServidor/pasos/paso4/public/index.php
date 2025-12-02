<?php
// paso4/public/index.php
// Front controller simple para Paso 4
// Ajusta el path al bootstrap de la app
require __DIR__ . '/../app/bootstrap.php';
// Parsear la ruta
// Se espera que el servidor sirva esta carpeta como base: / (ej: http://localhost/paso4/public/)
// Obtenemos la ruta solicitada: /clientes/edit/5 o /clientes
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Si la aplicación está en un subdirectorio (como /paso4/public), queremos eliminar esa parte.
// Obtenlo calculando el "base" relativo al SCRIPT_NAME.
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base = rtrim($scriptName, '/');
if ($base && strpos($uri, $base) === 0) {
 $path = substr($uri, strlen($base));
} else {
 $path = $uri;
}
$path = trim($path, '/'); // e.g. "clientes/edit/5" or ""
// Default: clientes
if ($path === '') {
 $segments = [];
} else {
 $segments = explode('/', $path);
}
// Decide controlador: por ahora solo 'clientes'
$resource = $segments[0] ?? 'clientes';
$action = $segments[1] ?? 'index';
$params = array_slice($segments, 2);
// Mapear rutas simples
switch ($resource) {
 case 'clientes':
 // Aseguramos que la clase ClienteController esté disponible (autoload lo carga)
 $controller = new ClienteController($pdo);
 // mapear acciones a métodos; pasar params cuando sea necesario
 if ($action === 'add') {
 $controller->add();
 } elseif ($action === 'edit') {
 $controller->edit($params);
 } elseif ($action === 'delete') {
 $controller->delete($params);
 } else {
 // 'index' / 'list' / default
 $controller->index();
 }
 break;
 default:
 // 404 simple
 header("HTTP/1.0 404 Not Found");
 echo "<h1>404 - Recurso no encontrado</h1>";
 echo "<p>Recurso: " . esc($resource) . "</p>";
 break;
}