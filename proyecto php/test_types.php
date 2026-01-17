<?php
/**
 * Script de prueba para el sistema de tipos de Pokémon
 */

// Incluir rutas
define('BASE_PATH', __DIR__);
define('SRC_PATH', BASE_PATH . '/src');

// Autoloader
spl_autoload_register(function ($class) {
    $file = SRC_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use services\TypeService;
use services\StatsService;

echo "========== PRUEBAS DEL SISTEMA DE TIPOS DE POKÉMON ==========\n\n";

// Crear servicios
$typeService = new TypeService();
$statsService = new StatsService();

// Prueba 1: Multiplicador simple (Fuego vs Agua)
echo "PRUEBA 1: Multiplicador simple (Movimiento Fuego vs Defensor Agua)\n";
$mult1 = $typeService->getDamageMultiplier('Fuego', 'Agua');
echo "Multiplicador: {$mult1}x (esperado: 0.5x - poco efectivo)\n";
echo "Resultado: " . ($mult1 == 0.5 ? "✓ CORRECTO" : "✗ ERROR") . "\n\n";

// Prueba 2: Multiplicador dual (Roca vs Planta - débil por ambos tipos)
echo "PRUEBA 2: Multiplicador dual (Movimiento Planta vs Defensor Roca/Tierra)\n";
$mult2 = $typeService->getDamageMultiplier('Planta', 'Roca, Tierra');
echo "Multiplicador: {$mult2}x (esperado: 4x - muy efectivo por ambos tipos)\n";
echo "Resultado: " . ($mult2 == 4 ? "✓ CORRECTO" : "✗ ERROR") . "\n\n";

// Prueba 3: Inmunidad (Movimiento Normal vs Defensor Fantasma)
echo "PRUEBA 3: Inmunidad (Movimiento Normal vs Defensor Fantasma)\n";
$mult3 = $typeService->getDamageMultiplier('Normal', 'Fantasma');
echo "Multiplicador: {$mult3}x (esperado: 0x - inmune)\n";
echo "Resultado: " . ($mult3 == 0 ? "✓ CORRECTO" : "✗ ERROR") . "\n\n";

// Prueba 4: Cálculo de daño completo
echo "PRUEBA 4: Cálculo de daño completo (Charizard ataca a Venusaur)\n";
$attacker = [
    'name' => 'Charizard',
    'hp' => 78,
    'attack' => 84,
    'defense' => 78,
    'spAtk' => 109,
    'spDef' => 85,
    'speed' => 100
];

$defender = [
    'name' => 'Venusaur',
    'hp' => 80,
    'attack' => 82,
    'defense' => 83,
    'spAtk' => 100,
    'spDef' => 100,
    'speed' => 80,
    'type' => 'Planta'
];

$move = [
    'name' => 'Lanza Llamas',
    'power' => 90,
    'type' => 'Fuego'
];

$result = $statsService->calculateDamageWithType($attacker, $defender, $move, 50);

if (isset($result['error'])) {
    echo "Error: " . $result['error'] . "\n";
} else {
    echo "Movimiento: {$result['moveName']} ({$result['moveType']}, poder {$result['movePower']})\n";
    echo "Atacante: {$result['attackerName']} (Ataque Esp: {$result['attackerStat']})\n";
    echo "Defensor: {$result['defenderName']} ({$result['defenderType']}, Defensa Esp: {$result['defenderStat']})\n";
    echo "Daño base: {$result['baseDamage']}\n";
    echo "Multiplicador de tipo: {$result['typeMultiplier']}x ({$result['effectiveness']})\n";
    echo "Daño con tipo: {$result['damageWithType']}\n";
    echo "Daño final: {$result['minDamage']} - {$result['maxDamage']}\n";
    echo "Porcentaje HP: {$result['percentMin']}% - {$result['percentMax']}%\n";
    echo "KOs necesarios: {$result['kos']}\n";
}

echo "\n========== FIN DE PRUEBAS ==========\n";
?>
