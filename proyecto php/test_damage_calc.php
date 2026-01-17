<?php
/**
 * Test para verificar que la calculadora de daño funciona
 */

// Test de endpoints
echo "=== TEST DE ENDPOINTS ===\n\n";

// Verificar que /api/pokemon/list retorna Pokémon
echo "1. Probando /api/pokemon/list\n";
$ch = curl_init('http://localhost/temp/proyecto%20php/api/pokemon/list');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "   ✓ Endpoint retorna Pokémon\n";
    echo "   - Total de Pokémon: " . count($data['data']) . "\n";
    echo "   - Primer Pokémon: " . ($data['data'][0]['name'] ?? 'N/A') . "\n";
} else {
    echo "   ✗ Error en endpoint\n";
    echo "   Response: " . $response . "\n";
}

echo "\n2. Probando /api/team/all\n";
$ch = curl_init('http://localhost/temp/proyecto%20php/api/team/all');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if ($data && $data['success']) {
    echo "   ✓ Endpoint retorna equipos\n";
    echo "   - Total de equipos: " . count($data['data']) . "\n";
} else {
    echo "   ✗ Error en endpoint\n";
    echo "   Response: " . $response . "\n";
}

echo "\n=== VERIFICACIÓN DE ARCHIVOS JS ===\n\n";

// Verificar que los archivos JS existen
echo "3. Verificando script.js\n";
if (file_exists(__DIR__ . '/public/js/script.js')) {
    $content = file_get_contents(__DIR__ . '/public/js/script.js');
    
    $functions = [
        'openAttackerSelector',
        'openDefenderSelector',
        'calculateDamage',
        'switchSelectorSource',
        'renderSelectorList',
        'selectPokemonForDamageCalc',
        'displayAttackerInfo',
        'displayDefenderInfo'
    ];
    
    foreach ($functions as $func) {
        if (strpos($content, "function $func") !== false) {
            echo "   ✓ Función $func encontrada\n";
        } else {
            echo "   ✗ Función $func NO encontrada\n";
        }
    }
} else {
    echo "   ✗ script.js no existe\n";
}

echo "\n4. Verificando HTML\n";
if (file_exists(__DIR__ . '/public/index.html')) {
    $content = file_get_contents(__DIR__ . '/public/index.html');
    
    $elements = [
        'selectorModal' => 'Modal de selector',
        'attackerInfo' => 'Caja de información del atacante',
        'defenderInfo' => 'Caja de información del defensor',
        'moveNameDmg' => 'Input de nombre del movimiento',
        'movePowerDmg' => 'Input de poder del movimiento',
        'moveTypeDmg' => 'Select de tipo del movimiento',
        'damageResults' => 'Div de resultados',
        'openAttackerSelector' => 'Llamada a openAttackerSelector'
    ];
    
    foreach ($elements as $id => $desc) {
        if (strpos($content, $id) !== false) {
            echo "   ✓ $desc encontrado\n";
        } else {
            echo "   ✗ $desc NO encontrado\n";
        }
    }
} else {
    echo "   ✗ index.html no existe\n";
}

echo "\n=== TEST COMPLETADO ===\n";
?>
