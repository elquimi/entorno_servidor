<?php
// Script para transformar el JSON de movimientos al formato necesario

$inputFile = __DIR__ . '/../src/data/moves.json';
$outputFile = __DIR__ . '/../src/data/moves_transformed.json';

// Leer el JSON original
$jsonContent = file_get_contents($inputFile);
$moves = json_decode($jsonContent, true);

$transformedMoves = [];

foreach ($moves as $key => $move) {
    // Solo incluir movimientos con poder de ataque (ignorar movimientos de estado)
    if (isset($move['basePower']) && $move['basePower'] > 0) {
        $transformedMoves[] = [
            'name' => $move['name'],
            'power' => $move['basePower'],
            'accuracy' => $move['accuracy'] === true ? 100 : (int)$move['accuracy'],
            'type' => $move['type'],
            'category' => strtolower($move['category'])
        ];
    }
}

// Guardar el JSON transformado
file_put_contents($outputFile, json_encode($transformedMoves, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Transformación completada!\n";
echo "Total de movimientos con poder de ataque: " . count($transformedMoves) . "\n";
echo "Archivo guardado en: moves_transformed.json\n";
