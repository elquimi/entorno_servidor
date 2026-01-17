<?php

namespace services;

/**
 * Servicio para manejar la efectividad de tipos en Pokémon
 */
class TypeService
{
    private $types;
    private $effectiveness;

    public function __construct()
    {
        $this->loadTypeData();
    }

    /**
     * Carga los datos de tipos desde el archivo JSON
     */
    private function loadTypeData()
    {
        $typesFile = __DIR__ . '/../data/types.json';
        
        if (file_exists($typesFile)) {
            $data = json_decode(file_get_contents($typesFile), true);
            $this->types = $data['types'] ?? [];
            $this->effectiveness = $data['effectiveness'] ?? [];
        } else {
            // Fallback con datos básicos si el archivo no existe
            $this->types = [];
            $this->effectiveness = [];
        }
    }

    /**
     * Obtiene el multiplicador de daño para un movimiento
     * 
     * @param string $moveType - Tipo del movimiento
     * @param array|string $defenderTypes - Tipo(s) del defensor (puede ser string con comas o array)
     * @return float - Multiplicador de daño (0.25, 0.5, 1, 2, 4)
     */
    public function getDamageMultiplier($moveType, $defenderTypes)
    {
        // Normalizar los tipos del defensor a un array
        if (is_string($defenderTypes)) {
            $types = array_map('trim', explode(',', $defenderTypes));
        } else {
            $types = (array) $defenderTypes;
        }

        $multiplier = 1.0;

        // Verificar si el Pokémon es inmune al movimiento
        foreach ($types as $type) {
            if ($this->isImmune($moveType, $type)) {
                return 0; // Daño nulo si es inmune
            }
        }

        // Calcular multiplicador acumulativo
        foreach ($types as $type) {
            if ($this->isWeakTo($moveType, $type)) {
                $multiplier *= 2;
            } elseif ($this->isResistantTo($moveType, $type)) {
                $multiplier *= 0.5;
            }
        }

        return $multiplier;
    }

    /**
     * Verifica si un movimiento es débil al tipo del defensor
     */
    private function isWeakTo($moveType, $defenderType)
    {
        $moveType = $this->normalizeType($moveType);
        $defenderType = $this->normalizeType($defenderType);

        if (isset($this->effectiveness[$defenderType]['weak_to'])) {
            return in_array($moveType, $this->effectiveness[$defenderType]['weak_to']);
        }

        return false;
    }

    /**
     * Verifica si un movimiento es resistido por el tipo del defensor
     */
    private function isResistantTo($moveType, $defenderType)
    {
        $moveType = $this->normalizeType($moveType);
        $defenderType = $this->normalizeType($defenderType);

        if (isset($this->effectiveness[$defenderType]['resists'])) {
            return in_array($moveType, $this->effectiveness[$defenderType]['resists']);
        }

        return false;
    }

    /**
     * Verifica si un movimiento es inmune al tipo del defensor
     */
    private function isImmune($moveType, $defenderType)
    {
        $moveType = $this->normalizeType($moveType);
        $defenderType = $this->normalizeType($defenderType);

        if (isset($this->effectiveness[$defenderType]['immune_to'])) {
            return in_array($moveType, $this->effectiveness[$defenderType]['immune_to']);
        }

        return false;
    }

    /**
     * Normaliza el nombre del tipo para la comparación
     */
    private function normalizeType($type)
    {
        // Remover espacios y convertir a formato consistente
        return trim($type);
    }

    /**
     * Obtiene información completa de efectividad de un tipo
     */
    public function getTypeEffectiveness($type)
    {
        $type = $this->normalizeType($type);
        
        if (isset($this->effectiveness[$type])) {
            return $this->effectiveness[$type];
        }

        return null;
    }

    /**
     * Obtiene la matriz completa de efectividades
     */
    public function getAllEffectiveness()
    {
        return $this->effectiveness;
    }

    /**
     * Obtiene la lista de todos los tipos
     */
    public function getAllTypes()
    {
        return $this->types;
    }
}
?>
