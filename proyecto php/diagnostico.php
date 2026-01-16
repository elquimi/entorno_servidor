<?php
/**
 * Archivo de diagnóstico rápido
 * Accede a: http://localhost/proyecto php/diagnostico.php
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico - Pokémon Calculator</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #667eea; }
        .check { margin: 15px 0; padding: 10px; border-left: 4px solid #27ae60; background: #e8f8f5; }
        .error { margin: 15px 0; padding: 10px; border-left: 4px solid #c0392b; background: #fadbd8; }
        .warning { margin: 15px 0; padding: 10px; border-left: 4px solid #f39c12; background: #fef5e7; }
        code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Diagnóstico - Pokémon Calculator</h1>
        <p>Este archivo verifica que el proyecto esté correctamente configurado.</p>
        <hr>
";

// 1. Verificar PHP
echo "<h2>1. Verificación de PHP</h2>";
echo "<div class='check'>";
echo "✅ PHP Version: " . phpversion() . "<br>";
echo "✅ PHP SAPI: " . php_sapi_name() . "<br>";
echo "</div>";

// 2. Verificar archivos
echo "<h2>2. Archivos Necesarios</h2>";

$files = [
    'index.php' => __DIR__ . '/index.php',
    'config.php' => __DIR__ . '/config.php',
    'public/index.html' => __DIR__ . '/public/index.html',
    'public/js/script.js' => __DIR__ . '/public/js/script.js',
    'public/css/styles.css' => __DIR__ . '/public/css/styles.css',
    'src/controllers/PokemonController.php' => __DIR__ . '/src/controllers/PokemonController.php',
    'src/models/Pokemon.php' => __DIR__ . '/src/models/Pokemon.php',
    'src/services/PokemonService.php' => __DIR__ . '/src/services/PokemonService.php',
    '.htaccess' => __DIR__ . '/.htaccess',
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<div class='check'>✅ $name</div>";
    } else {
        echo "<div class='error'>❌ $name - NO ENCONTRADO</div>";
    }
}

// 3. Verificar directorios
echo "<h2>3. Directorios</h2>";

$dirs = [
    'src' => __DIR__ . '/src',
    'public' => __DIR__ . '/public',
    'src/controllers' => __DIR__ . '/src/controllers',
    'src/services' => __DIR__ . '/src/services',
    'src/models' => __DIR__ . '/src/models',
    'public/css' => __DIR__ . '/public/css',
    'public/js' => __DIR__ . '/public/js',
];

foreach ($dirs as $name => $path) {
    if (is_dir($path)) {
        echo "<div class='check'>✅ $name</div>";
    } else {
        echo "<div class='error'>❌ $name - NO ENCONTRADO</div>";
    }
}

// 4. Verificar permisos
echo "<h2>4. Permisos de Lectura</h2>";

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        if (is_readable($path)) {
            echo "<div class='check'>✅ $name - Lectura OK</div>";
        } else {
            echo "<div class='error'>❌ $name - Permisos insuficientes</div>";
        }
    }
}

// 5. Verificar extensiones PHP
echo "<h2>5. Extensiones PHP Requeridas</h2>";

$extensions = ['json', 'spl', 'streams'];

foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='check'>✅ $ext</div>";
    } else {
        echo "<div class='error'>❌ $ext - NO INSTALADA</div>";
    }
}

// 6. Verificar conexión a API
echo "<h2>6. Conexión a PokéAPI</h2>";

$context = stream_context_create(['http' => ['timeout' => 5]]);
$response = @file_get_contents('https://pokeapi.co/api/v2/pokemon/1', false, $context);

if ($response !== false) {
    echo "<div class='check'>✅ PokéAPI accesible</div>";
} else {
    echo "<div class='warning'>⚠️ No se puede acceder a PokéAPI (verifica tu conexión a internet)</div>";
}

// 7. Verificar rutas
echo "<h2>7. URLs</h2>";

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base = "$protocol://$host/proyecto php";

echo "<div class='check'>";
echo "Aplicación: <a href='$base/'>$base/</a><br>";
echo "API Test: <a href='$base/api/pokemon/search?name=pikachu'>$base/api/pokemon/search?name=pikachu</a>";
echo "</div>";

// 8. Verificar .htaccess
echo "<h2>8. Reescritura de URLs</h2>";

if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
    echo "<div class='check'>✅ mod_rewrite está habilitado</div>";
} else {
    echo "<div class='warning'>⚠️ No se puede verificar mod_rewrite (normal en algunos servidores)</div>";
}

if (file_exists(__DIR__ . '/.htaccess')) {
    echo "<div class='check'>✅ .htaccess presente</div>";
} else {
    echo "<div class='error'>❌ .htaccess NO ENCONTRADO</div>";
}

// 9. Información de la solicitud
echo "<h2>9. Información de la Solicitud Actual</h2>";

echo "<div class='check'>";
echo "URL: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Método: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "Host: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "</div>";

// Resumen
echo "<h2>📋 Resumen</h2>";

$errors = 0;
foreach ($files as $name => $path) {
    if (!file_exists($path)) $errors++;
}
foreach ($dirs as $name => $path) {
    if (!is_dir($path)) $errors++;
}

if ($errors === 0) {
    echo "<div class='check'>✅ <strong>Todo parece estar OK. Tu proyecto debería funcionar.</strong></div>";
    echo "<p>Accede a: <a href='$base/'><strong>$base/</strong></a></p>";
} else {
    echo "<div class='error'>❌ Se encontraron $errors problemas. Revisa arriba para más detalles.</div>";
}

echo "
    </div>
</body>
</html>
";
?>
