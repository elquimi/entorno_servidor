<?php

namespace services;

use models\Pokemon;

/**
 * Servicio para obtener datos de Pokémon
 * Usa la API de PokéAPI
 */
class PokemonService
{
    private $apiUrl = 'https://pokeapi.co/api/v2/pokemon';
    private $cache = [];

    /**
     * Busca un Pokémon por nombre
     */
    public function searchByName($name)
    {
        if (empty($name)) {
            return null;
        }

        $name = strtolower(trim($name));

        // Intentar obtener de la API
        $data = $this->fetchFromAPI($name);

        if ($data) {
            return $this->parsePokemonData($data);
        }

        return null;
    }

    /**
     * Obtiene datos de la API de PokéAPI
     */
    private function fetchFromAPI($name)
    {
        try {
            // Normalizar nombres especiales de Pokémon
            $nameMap = [
                'nidoran-m' => 'nidoran-m',
                'nidoran-f' => 'nidoran-f',
                'type-null' => 'type-null',
                'mr-mime' => 'mr-mime'
            ];
            $searchName = $nameMap[strtolower($name)] ?? strtolower($name);
            $url = $this->apiUrl . '/' . $searchName;
            
            // Usar file_get_contents con contexto
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'ignore_errors' => true
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);
            
            // Si no es array o está vacío, probablemente hubo error
            if (!is_array($data) || empty($data)) {
                return null;
            }
            
            // Si tiene 'error' o 'detail', devolver null
            if (isset($data['error']) || isset($data['detail'])) {
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Mapea tipos de inglés a español
     */
    private function translateType($enType)
    {
        $typeMap = [
            'normal' => 'Normal',
            'fire' => 'Fuego',
            'water' => 'Agua',
            'electric' => 'Eléctrico',
            'grass' => 'Planta',
            'ice' => 'Hielo',
            'fighting' => 'Lucha',
            'poison' => 'Veneno',
            'ground' => 'Tierra',
            'flying' => 'Volador',
            'psychic' => 'Psíquico',
            'bug' => 'Bicho',
            'rock' => 'Roca',
            'ghost' => 'Fantasma',
            'dragon' => 'Dragón',
            'dark' => 'Siniestro',
            'steel' => 'Acero',
            'fairy' => 'Hada'
        ];
        return $typeMap[strtolower($enType)] ?? ucfirst($enType);
    }

    /**
     * Parsea los datos de la API al modelo Pokemon
     */
    private function parsePokemonData($data)
    {
        if (!isset($data['id'], $data['name'], $data['stats'])) {
            return null;
        }

        // Mapear tipos
        $types = [];
        foreach ($data['types'] as $typeInfo) {
            $enType = $typeInfo['type']['name'];
            $types[] = $this->translateType($enType);
        }

        // Mapear estadísticas
        $stats = [];
        foreach ($data['stats'] as $stat) {
            $statName = $stat['stat']['name'];
            $stats[$statName] = $stat['base_stat'];
        }

        // Obtener imagen
        $image = '';
        if (isset($data['sprites']['other']['official-artwork']['front_default'])) {
            $image = $data['sprites']['other']['official-artwork']['front_default'];
        } elseif (isset($data['sprites']['front_default'])) {
            $image = $data['sprites']['front_default'];
        }

        $pokemon = new Pokemon([
            'id' => $data['id'],
            'name' => ucfirst($data['name']),
            'type' => implode(', ', $types),
            'hp' => $stats['hp'] ?? 0,
            'attack' => $stats['attack'] ?? 0,
            'defense' => $stats['defense'] ?? 0,
            'spAtk' => $stats['special-attack'] ?? 0,
            'spDef' => $stats['special-defense'] ?? 0,
            'speed' => $stats['speed'] ?? 0,
            'image' => $image
        ]);

        return $pokemon;
    }

    /**
     * Compara dos Pokémon
     */
    public function comparePokemon($pokemon1, $pokemon2)
    {
        if (!$pokemon1 || !$pokemon2) {
            return null;
        }

        return [
            'pokemon1' => $pokemon1->toArray(),
            'pokemon2' => $pokemon2->toArray(),
            'comparison' => $this->getComparison($pokemon1, $pokemon2)
        ];
    }

    /**
     * Genera comparación de estadísticas
     */
    private function getComparison($p1, $p2)
    {
        $stats = ['hp', 'attack', 'defense', 'spAtk', 'spDef', 'speed'];
        $comparison = [];

        foreach ($stats as $stat) {
            $value1 = $p1->$stat;
            $value2 = $p2->$stat;
            
            if ($value1 > $value2) {
                $winner = $p1->name;
                $difference = $value1 - $value2;
            } elseif ($value2 > $value1) {
                $winner = $p2->name;
                $difference = $value2 - $value1;
            } else {
                $winner = 'Empate';
                $difference = 0;
            }

            $comparison[$stat] = [
                'pokemon1' => $value1,
                'pokemon2' => $value2,
                'winner' => $winner,
                'difference' => $difference
            ];
        }

        // Estadísticas totales
        $total1 = $p1->getTotalStats();
        $total2 = $p2->getTotalStats();

        if ($total1 > $total2) {
            $totalWinner = $p1->name;
        } elseif ($total2 > $total1) {
            $totalWinner = $p2->name;
        } else {
            $totalWinner = 'Empate';
        }

        $comparison['total'] = [
            'pokemon1' => $total1,
            'pokemon2' => $total2,
            'winner' => $totalWinner
        ];

        return $comparison;
    }

    /**
     * Devuelve la lista completa desde datos locales o PokéAPI (id, name, image)
     */
    public function getAllList()
    {
        // Preferir datos locales si están habilitados y existen
        if (defined('USE_LOCAL_DATA') && USE_LOCAL_DATA === true && defined('LOCAL_DATA_PATH') && file_exists(LOCAL_DATA_PATH)) {
            $json = file_get_contents(LOCAL_DATA_PATH);
            $data = json_decode($json, true);
            if (is_array($data)) {
                // Mapear a estructura ligera y ordenar por id
                $list = array_map(function($p){
                    return [
                        'id' => $p['id'] ?? null,
                        'name' => $p['name'] ?? '',
                        'image' => $p['image'] ?? ''
                    ];
                }, $data);
                usort($list, function($a,$b){ return ($a['id'] ?? 0) <=> ($b['id'] ?? 0); });
                return $list;
            }
        }

        // Fallback sencillo: obtener listado de PokéAPI (sin detalles)
        $listUrl = $this->apiUrl . '?limit=1300';
        $response = @file_get_contents($listUrl);
        if ($response === false) {
            return [];
        }
        $payload = json_decode($response, true);
        if (!isset($payload['results']) || !is_array($payload['results'])) {
            return [];
        }

        // Derivar id desde la URL de cada recurso
        $list = [];
        foreach ($payload['results'] as $item) {
            $url = $item['url'] ?? '';
            $id = null;
            if ($url) {
                // URLs como .../pokemon/25/
                $parts = explode('/', trim($url, '/'));
                $id = intval(end($parts));
            }
            $image = $id ? "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/{$id}.png" : '';
            $list[] = [
                'id' => $id,
                'name' => ucfirst($item['name'] ?? ''),
                'image' => $image
            ];
        }
        usort($list, function($a,$b){ return ($a['id'] ?? 0) <=> ($b['id'] ?? 0); });
        return $list;
    }
}
?>
