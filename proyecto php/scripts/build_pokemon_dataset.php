<?php
/**
 * Script para construir dataset local de todos los Pokémon
 * Guarda en database/pokemon.json
 *
 * Uso:
 *   php scripts/build_pokemon_dataset.php
 */

define('BASE_PATH', dirname(__DIR__));
$target = BASE_PATH . '/database/pokemon.json';
$apiBase = 'https://pokeapi.co/api/v2/pokemon';
$limit = 1010; // Ajusta si necesitas más

function http_get_json($url, $timeout = 10) {
    $context = stream_context_create([ 'http' => [ 'timeout' => $timeout ] ]);
    $body = @file_get_contents($url, false, $context);
    if ($body === false) return null;
    return json_decode($body, true);
}

echo "Descargando listado de Pokémon...\n";
$list = http_get_json($apiBase . '?limit=' . $limit);
if (!$list || !isset($list['results'])) {
    fwrite(STDERR, "No se pudo obtener el listado.\n");
    exit(1);
}

$results = [];
$count = 0;
foreach ($list['results'] as $item) {
    $name = $item['name'];
    // Derivar ID desde URL
    $url = $item['url'];
    $parts = explode('/', trim($url, '/'));
    $id = intval(end($parts));

    // Obtener detalles
    $detail = http_get_json($apiBase . '/' . $name);
    if (!$detail) {
        echo "Saltando $name (sin respuesta)\n";
        continue;
    }

    // Tipos
    $types = [];
    foreach ($detail['types'] as $typeInfo) {
        $types[] = $typeInfo['type']['name'];
    }

    // Stats
    $statsMap = [];
    foreach ($detail['stats'] as $stat) {
        $statsMap[$stat['stat']['name']] = $stat['base_stat'];
    }

    // Imagen
    $image = '';
    if (isset($detail['sprites']['other']['official-artwork']['front_default'])) {
        $image = $detail['sprites']['other']['official-artwork']['front_default'];
    } elseif (isset($detail['sprites']['front_default'])) {
        $image = $detail['sprites']['front_default'];
    }

    $results[] = [
        'id' => $id,
        'name' => ucfirst($detail['name']),
        'type' => implode(', ', $types),
        'hp' => $statsMap['hp'] ?? 0,
        'attack' => $statsMap['attack'] ?? 0,
        'defense' => $statsMap['defense'] ?? 0,
        'spAtk' => $statsMap['special-attack'] ?? ($statsMap['sp-atk'] ?? 0),
        'spDef' => $statsMap['special-defense'] ?? ($statsMap['sp-def'] ?? 0),
        'speed' => $statsMap['speed'] ?? 0,
        'image' => $image
    ];

    $count++;
    if ($count % 25 === 0) {
        echo "Procesados $count...\n";
    }
}

// Ordenar por id y guardar
usort($results, function($a,$b){ return ($a['id'] ?? 0) <=> ($b['id'] ?? 0); });
file_put_contents($target, json_encode($results, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

echo "Dataset guardado en $target (" . count($results) . " registros).\n";
