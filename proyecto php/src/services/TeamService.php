<?php

namespace services;

use models\Team;
use models\CustomPokemon;

/**
 * Servicio para gestionar equipos de Pokémon personalizados
 */
class TeamService
{
    private $dataFile = '';
    private $movesFile = '';
    private $pokemonService;

    public function __construct()
    {
        $this->dataFile = __DIR__ . '/../data/teams.json';
        $this->movesFile = __DIR__ . '/../data/moves.json';
        $this->pokemonService = new PokemonService();
        $this->ensureDataFile();
        $this->ensureMovesFile();
    }

    /**
     * Asegura que exista el archivo de equipos
     */
    private function ensureDataFile()
    {
        if (!file_exists($this->dataFile)) {
            $dir = dirname($this->dataFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    /**
     * Asegura que exista el archivo de movimientos
     */
    private function ensureMovesFile()
    {
        if (!file_exists($this->movesFile)) {
            $dir = dirname($this->movesFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            // Crear lista básica de movimientos comunes
            $commonMoves = $this->getCommonMoves();
            file_put_contents($this->movesFile, json_encode($commonMoves));
        }
    }

    /**
     * Obtiene lista de movimientos comunes
     */
    private function getCommonMoves()
    {
        return [
            ['name' => 'Tackle', 'power' => 40, 'accuracy' => 100, 'type' => 'Normal'],
            ['name' => 'Scratch', 'power' => 40, 'accuracy' => 100, 'type' => 'Normal'],
            ['name' => 'Ember', 'power' => 40, 'accuracy' => 100, 'type' => 'Fire'],
            ['name' => 'Water Gun', 'power' => 40, 'accuracy' => 100, 'type' => 'Water'],
            ['name' => 'Vine Whip', 'power' => 45, 'accuracy' => 100, 'type' => 'Grass'],
            ['name' => 'Thunder Shock', 'power' => 40, 'accuracy' => 100, 'type' => 'Electric'],
            ['name' => 'Peck', 'power' => 35, 'accuracy' => 100, 'type' => 'Flying'],
            ['name' => 'Bite', 'power' => 60, 'accuracy' => 100, 'type' => 'Dark'],
            ['name' => 'Pound', 'power' => 40, 'accuracy' => 100, 'type' => 'Normal'],
            ['name' => 'Psychic', 'power' => 90, 'accuracy' => 100, 'type' => 'Psychic'],
            ['name' => 'Ice Beam', 'power' => 90, 'accuracy' => 100, 'type' => 'Ice'],
            ['name' => 'Thunderbolt', 'power' => 90, 'accuracy' => 100, 'type' => 'Electric'],
            ['name' => 'Flamethrower', 'power' => 90, 'accuracy' => 100, 'type' => 'Fire'],
            ['name' => 'Surf', 'power' => 90, 'accuracy' => 100, 'type' => 'Water'],
            ['name' => 'Earthquake', 'power' => 100, 'accuracy' => 100, 'type' => 'Ground'],
            ['name' => 'Dragon Claw', 'power' => 80, 'accuracy' => 100, 'type' => 'Dragon'],
            ['name' => 'Close Combat', 'power' => 120, 'accuracy' => 100, 'type' => 'Fighting'],
            ['name' => 'Stone Edge', 'power' => 100, 'accuracy' => 80, 'type' => 'Rock'],
            ['name' => 'Hyper Beam', 'power' => 150, 'accuracy' => 90, 'type' => 'Normal'],
            ['name' => 'Shadow Ball', 'power' => 80, 'accuracy' => 100, 'type' => 'Ghost']
        ];
    }

    /**
     * Obtiene todos los movimientos disponibles
     */
    public function getAllMoves()
    {
        // Intentar obtener desde PokeAPI
        try {
            $url = 'https://pokeapi.co/api/v2/move?limit=2000';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (isset($data['results']) && is_array($data['results'])) {
                    // Convertir nombres de formato slug a título
                    $moves = array_map(function($move) {
                        return [
                            'name' => ucwords(str_replace('-', ' ', $move['name'])),
                            'original' => $move['name']
                        ];
                    }, $data['results']);
                    
                    // Guardar en caché
                    file_put_contents($this->movesFile, json_encode($moves));
                    return $moves;
                }
            }
        } catch (Exception $e) {
            // Si falla PokeAPI, intentar cargar desde archivo local
        }

        // Fallback: archivo local o movimientos comunes
        if (file_exists($this->movesFile)) {
            $moves = json_decode(file_get_contents($this->movesFile), true);
            if ($moves) return $moves;
        }
        
        return $this->getCommonMoves();
    }

    /**
     * Crea un nuevo equipo
     */
    public function createTeam($name = 'Mi Equipo', $description = '')
    {
        $team = new Team([
            'name' => $name,
            'description' => $description
        ]);
        return $team;
    }

    /**
     * Guarda un equipo en el archivo
     */
    public function saveTeam($team)
    {
        try {
            $teams = json_decode(file_get_contents($this->dataFile), true) ?? [];
            
            // Buscar si el equipo ya existe
            $found = false;
            foreach ($teams as &$t) {
                if ($t['id'] === $team->id) {
                    $t = $team->toArray();
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $teams[] = $team->toArray();
            }
            
            file_put_contents($this->dataFile, json_encode($teams, JSON_PRETTY_PRINT));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene un equipo por ID
     */
    public function getTeam($teamId)
    {
        try {
            $teams = json_decode(file_get_contents($this->dataFile), true) ?? [];
            
            foreach ($teams as $teamData) {
                if ($teamData['id'] === $teamId) {
                    $team = new Team($teamData);
                    // Convertir members a CustomPokemon
                    $team->members = array_map(function($memberData) {
                        return new CustomPokemon($memberData);
                    }, $teamData['members']);
                    return $team;
                }
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtiene todos los equipos
     */
    public function getAllTeams()
    {
        try {
            $teams = json_decode(file_get_contents($this->dataFile), true) ?? [];
            
            return array_map(function($teamData) {
                $team = new Team($teamData);
                $team->members = array_map(function($memberData) {
                    return new CustomPokemon($memberData);
                }, $teamData['members']);
                return $team;
            }, $teams);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Elimina un equipo
     */
    public function deleteTeam($teamId)
    {
        try {
            $teams = json_decode(file_get_contents($this->dataFile), true) ?? [];
            $teams = array_filter($teams, function($t) use ($teamId) {
                return $t['id'] !== $teamId;
            });
            file_put_contents($this->dataFile, json_encode(array_values($teams), JSON_PRETTY_PRINT));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene movimientos de un Pokémon específico
     */
    public function getPokemonMoves($pokemonName)
    {
        try {
            $pokemon = $this->pokemonService->searchByName($pokemonName);
            if (!$pokemon) {
                return [];
            }

            // Obtener movimientos de la API
            $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemonName);
            $context = stream_context_create([
                'http' => ['timeout' => 5, 'ignore_errors' => true]
            ]);
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return [];
            }

            $data = json_decode($response, true);
            if (!isset($data['moves']) || !is_array($data['moves'])) {
                return [];
            }

            // Extraer nombres de movimientos - TODOS
            $moves = [];
            foreach ($data['moves'] as $moveData) {
                if (isset($moveData['move']['name'])) {
                    $moves[] = [
                        'name' => ucwords(str_replace('-', ' ', $moveData['move']['name'])),
                        'original' => $moveData['move']['name']
                    ];
                }
            }

            // Remover duplicados y devolver todos
            return array_values(array_unique($moves, SORT_REGULAR));
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Obtiene habilidades de un Pokémon específico
     */
    public function getPokemonAbilities($pokemonName)
    {
        try {
            if (empty($pokemonName)) {
                error_log("[getPokemonAbilities] Empty pokemon name");
                return [];
            }

            error_log("[getPokemonAbilities] Starting for: $pokemonName");

            // Normalizar el nombre del Pokémon
            $pokemonName = trim($pokemonName);
            $normalizedName = strtolower(str_replace(' ', '-', $pokemonName));
            
            // Obtener datos de la API
            $url = "https://pokeapi.co/api/v2/pokemon/" . urlencode($normalizedName);
            error_log("[getPokemonAbilities] URL: $url");
            
            $context = stream_context_create([
                'http' => ['timeout' => 5, 'ignore_errors' => true]
            ]);
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                error_log("[getPokemonAbilities] First attempt failed, trying original name");
                // Intentar con el nombre original si falla
                $url = "https://pokeapi.co/api/v2/pokemon/" . urlencode(strtolower($pokemonName));
                error_log("[getPokemonAbilities] Retry URL: $url");
                $response = @file_get_contents($url, false, $context);
                if ($response === false) {
                    error_log("[getPokemonAbilities] Both attempts failed");
                    return [];
                }
            }

            $data = json_decode($response, true);
            error_log("[getPokemonAbilities] API Response length: " . strlen($response));
            
            if (!isset($data['abilities']) || !is_array($data['abilities'])) {
                error_log("[getPokemonAbilities] No abilities in response");
                return [];
            }

            error_log("[getPokemonAbilities] Found " . count($data['abilities']) . " abilities");

            // Extraer nombres de habilidades
            $abilities = [];
            foreach ($data['abilities'] as $abilityData) {
                if (isset($abilityData['ability']['name'])) {
                    $ability = [
                        'name' => ucwords(str_replace('-', ' ', $abilityData['ability']['name'])),
                        'original' => $abilityData['ability']['name'],
                        'isHidden' => $abilityData['is_hidden'] ?? false
                    ];
                    $abilities[] = $ability;
                    error_log("[getPokemonAbilities] Added: " . json_encode($ability));
                }
            }

            // Retornar habilidades ordenadas (primero normales, luego oculta)
            usort($abilities, function($a, $b) {
                return ($a['isHidden'] ? 1 : 0) - ($b['isHidden'] ? 1 : 0);
            });

            error_log("[getPokemonAbilities] Returning " . count($abilities) . " abilities");
            return $abilities;
        } catch (\Exception $e) {
            error_log("[getPokemonAbilities] Exception: " . $e->getMessage());
            return [];
        }
    }
}
?>
