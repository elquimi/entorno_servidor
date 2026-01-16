<?php

namespace controllers;

use services\StatsService;

/**
 * Controlador para operaciones de estadísticas
 */
class StatsController
{
    private $statsService;

    public function __construct()
    {
        $this->statsService = new StatsService();
    }

    /**
     * Calcula estadísticas personalizadas
     */
    public function calculateStats($stats)
    {
        try {
            if (empty($stats)) {
                http_response_code(400);
                echo json_encode(['error' => 'Se requieren estadísticas para calcular']);
                return;
            }

            $result = $this->statsService->calculateCustomStats($stats);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Compara dos conjuntos de estadísticas
     */
    public function compareStats($stats1, $stats2)
    {
        try {
            if (empty($stats1) || empty($stats2)) {
                http_response_code(400);
                echo json_encode(['error' => 'Se requieren dos conjuntos de estadísticas']);
                return;
            }

            $result = $this->statsService->compareCustomStats($stats1, $stats2);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
