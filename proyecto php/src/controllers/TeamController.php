<?php

namespace controllers;

use services\TeamService;
use models\Team;
use models\CustomPokemon;

/**
 * Controlador para operaciones de equipos personalizados
 */
class TeamController
{
    private $teamService;

    public function __construct()
    {
        $this->teamService = new TeamService();
    }

    /**
     * Obtiene todos los equipos
     */
    public function getAll()
    {
        try {
            $teams = $this->teamService->getAllTeams();
            echo json_encode([
                'success' => true,
                'data' => array_map(function($team) { return $team->toArray(); }, $teams)
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtiene un equipo específico
     */
    public function getTeam($teamId)
    {
        try {
            $team = $this->teamService->getTeam($teamId);
            if (!$team) {
                http_response_code(404);
                echo json_encode(['error' => 'Equipo no encontrado']);
                return;
            }
            echo json_encode([
                'success' => true,
                'data' => $team->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Crea un nuevo equipo
     */
    public function create()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'] ?? 'Mi Equipo';
            $description = $data['description'] ?? '';

            $team = $this->teamService->createTeam($name, $description);
            $this->teamService->saveTeam($team);

            echo json_encode([
                'success' => true,
                'data' => $team->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Agrega un Pokémon al equipo
     */
    public function addPokemon($teamId)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $team = $this->teamService->getTeam($teamId);
            if (!$team) {
                http_response_code(404);
                echo json_encode(['error' => 'Equipo no encontrado']);
                return;
            }

            $customPokemon = new CustomPokemon($data);
            if (!$team->addMember($customPokemon)) {
                http_response_code(400);
                echo json_encode(['error' => 'Equipo completo (máximo 6 Pokémon)']);
                return;
            }

            $this->teamService->saveTeam($team);

            echo json_encode([
                'success' => true,
                'data' => $team->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Actualiza un Pokémon en el equipo
     */
    public function updatePokemon($teamId, $pokemonId)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $team = $this->teamService->getTeam($teamId);
            if (!$team) {
                http_response_code(404);
                echo json_encode(['error' => 'Equipo no encontrado']);
                return;
            }

            if (!$team->updateMember($pokemonId, $data)) {
                http_response_code(404);
                echo json_encode(['error' => 'Pokémon no encontrado en el equipo']);
                return;
            }

            $this->teamService->saveTeam($team);

            echo json_encode([
                'success' => true,
                'data' => $team->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Elimina un Pokémon del equipo
     */
    public function removePokemon($teamId, $pokemonId)
    {
        try {
            $team = $this->teamService->getTeam($teamId);
            if (!$team) {
                http_response_code(404);
                echo json_encode(['error' => 'Equipo no encontrado']);
                return;
            }

            $team->removeMember($pokemonId);
            $this->teamService->saveTeam($team);

            echo json_encode([
                'success' => true,
                'data' => $team->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtiene todos los movimientos disponibles
     */
    public function getMoves()
    {
        try {
            $moves = $this->teamService->getAllMoves();
            echo json_encode([
                'success' => true,
                'data' => $moves
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtiene movimientos de un Pokémon específico
     */
    public function getPokemonMoves($pokemonName)
    {
        try {
            $moves = $this->teamService->getPokemonMoves($pokemonName);
            echo json_encode([
                'success' => true,
                'data' => $moves
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtiene habilidades de un Pokémon específico
     */
    public function getPokemonAbilities($pokemonName)
    {
        try {
            error_log("[getPokemonAbilities] Called with: $pokemonName");
            $abilities = $this->teamService->getPokemonAbilities($pokemonName);
            error_log("[getPokemonAbilities] Result: " . json_encode($abilities));
            echo json_encode([
                'success' => true,
                'data' => $abilities
            ]);
        } catch (\Exception $e) {
            error_log("[getPokemonAbilities] Exception: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Elimina un equipo
     */
    public function delete($teamId)
    {
        try {
            if (!$this->teamService->deleteTeam($teamId)) {
                http_response_code(400);
                echo json_encode(['error' => 'No se pudo eliminar el equipo']);
                return;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Equipo eliminado'
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
