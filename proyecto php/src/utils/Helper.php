<?php

namespace utils;

/**
 * Clase de utilidades generales
 */
class Helper
{
    /**
     * Valida si un nombre de Pokémon es válido
     */
    public static function isValidPokemonName($name)
    {
        if (empty($name)) {
            return false;
        }

        // Solo letras, números y guiones
        return preg_match('/^[a-z0-9\-]+$/i', $name) === 1;
    }

    /**
     * Limpia un nombre de Pokémon
     */
    public static function cleanPokemonName($name)
    {
        $name = trim($name);
        $name = strtolower($name);
        $name = preg_replace('/[^a-z0-9\-]/', '', $name);
        return $name;
    }

    /**
     * Valida un rango de estadísticas
     */
    public static function isValidStatValue($value)
    {
        $value = (int)$value;
        return $value >= 0 && $value <= 255;
    }

    /**
     * Obtiene el color de tipo de Pokémon
     */
    public static function getTypeColor($type)
    {
        $colors = [
            'normal' => '#A8A878',
            'fire' => '#F08030',
            'water' => '#6890F0',
            'electric' => '#F8D030',
            'grass' => '#78C850',
            'ice' => '#98D8D8',
            'fighting' => '#C03028',
            'poison' => '#A040A0',
            'ground' => '#E0C068',
            'flying' => '#A890F0',
            'psychic' => '#F85888',
            'bug' => '#A8B820',
            'rock' => '#B8A038',
            'ghost' => '#705898',
            'dragon' => '#7038F8',
            'dark' => '#705848',
            'steel' => '#B8B8D0',
            'fairy' => '#EE99AC'
        ];

        $type = strtolower($type);
        return $colors[$type] ?? '#000000';
    }

    /**
     * Formatea una estadística para mostrar
     */
    public static function formatStat($name, $value)
    {
        $labels = [
            'hp' => 'HP',
            'attack' => 'Ataque',
            'defense' => 'Defensa',
            'spAtk' => 'Ataque Especial',
            'spDef' => 'Defensa Especial',
            'speed' => 'Velocidad'
        ];

        return [
            'label' => $labels[$name] ?? $name,
            'value' => $value
        ];
    }

    /**
     * Calcula el porcentaje de una estadística
     */
    public static function getStatPercentage($value, $max = 255)
    {
        return round(($value / $max) * 100, 2);
    }

    /**
     * Obtiene el mejor y peor atributo
     */
    public static function getBestAndWorstStats($stats)
    {
        $best = null;
        $worst = null;
        $bestValue = -1;
        $worstValue = 999;

        foreach ($stats as $name => $value) {
            if ($value > $bestValue) {
                $bestValue = $value;
                $best = $name;
            }
            if ($value < $worstValue) {
                $worstValue = $value;
                $worst = $name;
            }
        }

        return ['best' => $best, 'worst' => $worst];
    }
}
?>
