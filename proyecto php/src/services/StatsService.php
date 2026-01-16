<?php

namespace services;

/**
 * Servicio para cálculos de estadísticas personalizadas
 */
class StatsService
{
    /**
     * Calcula estadísticas personalizadas
     */
    public function calculateCustomStats($stats)
    {
        $cleanStats = [];
        
        // Validar y limpiar estadísticas
        $validStats = ['hp', 'attack', 'defense', 'spAtk', 'spDef', 'speed'];
        
        foreach ($validStats as $stat) {
            $value = $stats[$stat] ?? 0;
            $cleanStats[$stat] = (int)$value;
        }

        return [
            'stats' => $cleanStats,
            'total' => array_sum($cleanStats),
            'average' => round(array_sum($cleanStats) / count($cleanStats), 2),
            'max' => max($cleanStats),
            'min' => min($cleanStats)
        ];
    }

    /**
     * Compara dos conjuntos de estadísticas personalizadas
     */
    public function compareCustomStats($stats1, $stats2)
    {
        $calc1 = $this->calculateCustomStats($stats1);
        $calc2 = $this->calculateCustomStats($stats2);

        $statNames = ['hp', 'attack', 'defense', 'spAtk', 'spDef', 'speed'];
        $comparison = [];

        foreach ($statNames as $stat) {
            $value1 = $calc1['stats'][$stat];
            $value2 = $calc2['stats'][$stat];

            if ($value1 > $value2) {
                $winner = 'Stats 1';
            } elseif ($value2 > $value1) {
                $winner = 'Stats 2';
            } else {
                $winner = 'Empate';
            }

            $comparison[$stat] = [
                'stats1' => $value1,
                'stats2' => $value2,
                'winner' => $winner
            ];
        }

        return [
            'stats1_total' => $calc1['total'],
            'stats2_total' => $calc2['total'],
            'overall_winner' => $calc1['total'] > $calc2['total'] ? 'Stats 1' : ($calc2['total'] > $calc1['total'] ? 'Stats 2' : 'Empate'),
            'detailed_comparison' => $comparison
        ];
    }
}
?>
