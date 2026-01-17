<?php
/**
 * Punto de entrada principal de la aplicación
 * Pokemon Calculator - Calculadora y Comparador de Pokémon
 */

// Configuración
define('BASE_PATH', __DIR__);
define('SRC_PATH', BASE_PATH . '/src');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Cargar configuración
require_once BASE_PATH . '/config.php';

// Autoloader simple
spl_autoload_register(function ($class) {
    $file = SRC_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Rutas - Obtener ruta relativa desde el REQUEST_URI
$basePath = '/temp/proyecto php';
$request = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

error_log("[Router] REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("[Router] Parsed: " . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
error_log("[Router] After urldecode: " . $request);

// Remover la ruta base del proyecto (maneja espacios)
if (strpos($request, $basePath) === 0) {
    $request = substr($request, strlen($basePath));
    error_log("[Router] After removing base: " . $request);
}

// Asegurar que empiece con /
if (empty($request) || $request[0] !== '/') {
    $request = '/' . $request;
    error_log("[Router] After ensuring /: " . $request);
}

// Router simple
if ($request === '/' || $request === '' || $request === '/index.php') {
    include PUBLIC_PATH . '/index.html';
} elseif (strpos($request, '/api/') === 0) {
    header('Content-Type: application/json');
    
    // Rutas API
    if ($request === '/api/pokemon/search') {
        try {
            $controller = new controllers\PokemonController();
            $controller->search($_GET['name'] ?? '');
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } elseif ($request === '/api/pokemon/search-partial') {
        try {
            $controller = new controllers\PokemonController();
            $controller->searchPartial($_GET['q'] ?? '');
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } elseif ($request === '/api/pokemon/compare') {
        $controller = new controllers\PokemonController();
        $controller->compare($_POST['pokemon1'] ?? '', $_POST['pokemon2'] ?? '');
    } elseif ($request === '/api/pokemon/list') {
        $controller = new controllers\PokemonController();
        $controller->list();
    } elseif ($request === '/api/pokemon/stats') {
        $controller = new controllers\StatsController();
        $controller->calculateStats($_POST['stats'] ?? []);
    } elseif ($request === '/api/stats/damage') {
        $controller = new controllers\StatsController();
        $controller->calculateDamageWithTypes();
    } elseif ($request === '/api/team/all') {
        $controller = new controllers\TeamController();
        $controller->getAll();
    } elseif ($request === '/api/team/create') {
        $controller = new controllers\TeamController();
        $controller->create();
    } elseif ($request === '/api/team/moves') {
        $controller = new controllers\TeamController();
        $controller->getMoves();
    } elseif (preg_match('#^/api/team/pokemon/abilities/([^/]+)$#', $request, $matches)) {
        error_log("[Router] Matched abilities route with pokemonName: " . $matches[1]);
        $pokemonName = urldecode($matches[1]);
        error_log("[Router] After urldecode: " . $pokemonName);
        $controller = new controllers\TeamController();
        $controller->getPokemonAbilities($pokemonName);
    } elseif (preg_match('#^/api/team/pokemon/moves/([^/]+)$#', $request, $matches)) {
        $pokemonName = urldecode($matches[1]);
        $controller = new controllers\TeamController();
        $controller->getPokemonMoves($pokemonName);
    } elseif (preg_match('#^/api/team/([^/]+)$#', $request, $matches)) {
        $teamId = $matches[1];
        $controller = new controllers\TeamController();
        $controller->getTeam($teamId);
    } elseif (preg_match('#^/api/team/([^/]+)/pokemon/moves/([^/]+)$#', $request, $matches)) {
        $pokemonName = urldecode($matches[2]);
        $controller = new controllers\TeamController();
        $controller->getPokemonMoves($pokemonName);
    } elseif (preg_match('#^/api/team/([^/]+)/pokemon/add$#', $request, $matches)) {
        $teamId = $matches[1];
        $controller = new controllers\TeamController();
        $controller->addPokemon($teamId);
    } elseif (preg_match('#^/api/team/([^/]+)/pokemon/([^/]+)/update$#', $request, $matches)) {
        $teamId = $matches[1];
        $pokemonId = $matches[2];
        $controller = new controllers\TeamController();
        $controller->updatePokemon($teamId, $pokemonId);
    } elseif (preg_match('#^/api/team/([^/]+)/pokemon/([^/]+)/remove$#', $request, $matches)) {
        $teamId = $matches[1];
        $pokemonId = $matches[2];
        $controller = new controllers\TeamController();
        $controller->removePokemon($teamId, $pokemonId);
    } elseif (preg_match('#^/api/team/([^/]+)/delete$#', $request, $matches)) {
        $teamId = $matches[1];
        $controller = new controllers\TeamController();
        $controller->delete($teamId);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }
} else {
    http_response_code(404);
    include PUBLIC_PATH . '/404.html';
}
?>
