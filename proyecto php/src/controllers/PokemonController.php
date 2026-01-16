<?php

namespace controllers;

use services\PokemonService;

/**
 * Controlador para operaciones de Pokémon
 */
class PokemonController
{
    private $pokemonService;

    public function __construct()
    {
        $this->pokemonService = new PokemonService();
    }

    /**
     * Busca un Pokémon
     */
    public function search($name)
    {
        try {
            $pokemon = $this->pokemonService->searchByName($name);

            if (!$pokemon) {
                http_response_code(404);
                echo json_encode(['error' => 'Pokémon no encontrado']);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $pokemon->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Compara dos Pokémon
     */
    public function compare($name1, $name2)
    {
        try {
            if (empty($name1) || empty($name2)) {
                http_response_code(400);
                echo json_encode(['error' => 'Se requieren dos Pokémon para comparar']);
                return;
            }

            $pokemon1 = $this->pokemonService->searchByName($name1);
            $pokemon2 = $this->pokemonService->searchByName($name2);

            if (!$pokemon1 || !$pokemon2) {
                http_response_code(404);
                echo json_encode(['error' => 'Uno o ambos Pokémon no encontrados']);
                return;
            }

            $comparison = $this->pokemonService->comparePokemon($pokemon1, $pokemon2);

            echo json_encode([
                'success' => true,
                'data' => $comparison
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Lista todos los Pokémon (orden pokédex)
     */
    public function list()
    {
        try {
            $list = $this->pokemonService->getAllList();

            echo json_encode([
                'success' => true,
                'data' => $list
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
