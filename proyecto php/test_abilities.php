<?php

// Test script to debug abilities endpoint
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar autoloader
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/src/services/TeamService.php';

use services\TeamService;

$service = new TeamService();

// Test con algunos Pokémon comunes
$testPokemons = ['pikachu', 'charizard', 'blastoise', 'venusaur'];

foreach ($testPokemons as $pokemon) {
    echo "\n=== Testing $pokemon ===\n";
    $abilities = $service->getPokemonAbilities($pokemon);
    echo json_encode(['pokemon' => $pokemon, 'abilities' => $abilities], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "\n";
}
?>
