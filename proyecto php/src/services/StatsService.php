<?php

namespace services;

/**
 * Servicio para cálculos de estadísticas personalizadas
 */
class StatsService
{
    private $typeService;

    public function __construct()
    {
        $this->typeService = new TypeService();
    }

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

    /**
     * Calcula daño entre dos Pokémon considerando tipos
     * 
     * @param array $attacker - Datos del Pokémon atacante
     * @param array $defender - Datos del Pokémon defensor
     * @param array $move - Datos del movimiento (name, power, type)
     * @param int $level - Nivel del atacante (default 50)
     * @return array - Información del daño calculado
     */
    public function calculateDamageWithType($attacker, $defender, $move, $level = 50)
    {
        // Validar datos mínimos
        if (!$attacker || !$defender || !$move) {
            return ['error' => 'Datos incompletos'];
        }

        $movePower = (int)($move['power'] ?? 0);
        $moveType = trim($move['type'] ?? 'Normal');
        $moveName = trim($move['name'] ?? 'Movimiento');

        if ($movePower <= 0) {
            return ['error' => 'El poder del movimiento debe ser mayor a 0'];
        }

        // Estadísticas del atacante
        $attackerHP = (int)($attacker['hp'] ?? 100);
        $attackerAtk = (int)($attacker['attack'] ?? 100);
        $attackerSpAtk = (int)($attacker['spAtk'] ?? 100);

        // Estadísticas del defensor
        $defenderDef = (int)($defender['defense'] ?? 100);
        $defenderSpDef = (int)($defender['spDef'] ?? 100);
        $defenderHP = (int)($defender['hp'] ?? 100);
        $defenderType = trim($defender['type'] ?? 'Normal');

        // Determinar si es movimiento físico o especial
        $specialTypes = ['Fuego', 'Agua', 'Eléctrico', 'Planta', 'Hielo', 'Psíquico', 'Dragón', 'Hada'];
        $isSpecialMove = in_array($moveType, $specialTypes);

        $attack = $isSpecialMove ? $attackerSpAtk : $attackerAtk;
        $defense = $isSpecialMove ? $defenderSpDef : $defenderDef;

        // Fórmula oficial: damage = ((((2 * level / 5 + 2) * power * attack / defense) / 50) + 2) * modifiers
        $baseDamage = ((((2 * $level / 5 + 2) * $movePower * $attack / $defense) / 50) + 2);

        // Obtener multiplicador de tipo
        $typeMultiplier = $this->typeService->getDamageMultiplier($moveType, $defenderType);

        // Si es inmune, daño es 0
        if ($typeMultiplier === 0) {
            return [
                'success' => true,
                'immune' => true,
                'moveName' => $moveName,
                'moveType' => $moveType,
                'defenderName' => $defender['name'] ?? 'Desconocido',
                'defenderType' => $defenderType,
                'effectiveness' => 'Inmune',
                'typeMultiplier' => 0,
                'minDamage' => 0,
                'maxDamage' => 0
            ];
        }

        // Aplicar multiplicador de tipo
        $damageWithType = $baseDamage * $typeMultiplier;

        // Aplicar variación (85% - 100%)
        $minDamage = (int)floor($damageWithType * 0.85);
        $maxDamage = (int)floor($damageWithType * 1.0);

        // Calcular porcentaje de HP
        $percentMin = round(($minDamage / $defenderHP) * 100);
        $percentMax = round(($maxDamage / $defenderHP) * 100);

        // Calcular KOs necesarios
        $kos = (int)ceil($defenderHP / $maxDamage);

        // Generar descripción de efectividad
        $effectiveness = 'Normal';
        if ($typeMultiplier > 1) {
            $effectiveness = 'Muy efectivo';
        } elseif ($typeMultiplier < 1) {
            $effectiveness = 'Poco efectivo';
        }

        return [
            'success' => true,
            'immune' => false,
            'moveName' => $moveName,
            'moveType' => $moveType,
            'movePower' => $movePower,
            'isSpecialMove' => $isSpecialMove,
            'attackerName' => $attacker['name'] ?? 'Atacante',
            'attackerStat' => $attack,
            'attackerStatType' => $isSpecialMove ? 'Ataque Especial' : 'Ataque',
            'defenderName' => $defender['name'] ?? 'Defensor',
            'defenderType' => $defenderType,
            'defenderStat' => $defense,
            'defenderStatType' => $isSpecialMove ? 'Defensa Especial' : 'Defensa',
            'defenderHP' => $defenderHP,
            'typeMultiplier' => $typeMultiplier,
            'effectiveness' => $effectiveness,
            'minDamage' => $minDamage,
            'maxDamage' => $maxDamage,
            'percentMin' => $percentMin,
            'percentMax' => $percentMax,
            'kos' => $kos,
            'baseDamage' => (int)$baseDamage,
            'damageWithType' => (int)$damageWithType
        ];
    }
}
?>
