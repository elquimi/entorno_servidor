<?php
// Simple diagnostic endpoint

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

$request = $_SERVER['REQUEST_URI'];

// Extract the path without query string
$request = parse_url($request, PHP_URL_PATH);

// Remove the base path if it exists
$request = str_replace('/temp/proyecto%20php', '', $request);
$request = str_replace('/temp/proyecto php', '', $request);

// Normalize the request path
$request = preg_replace('/^\/+/', '/', $request);

echo json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'full_uri' => $_SERVER['REQUEST_URI'],
    'extracted_path' => $request,
    'server_name' => $_SERVER['SERVER_NAME'],
    'php_version' => phpversion()
]);
?>
