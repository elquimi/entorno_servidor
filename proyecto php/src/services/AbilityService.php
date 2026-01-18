<?php

namespace App\Services;

class AbilityService
{
    private array $abilities;

    public function __construct()
    {
        $jsonPath = __DIR__ . '/../data/abilities.json';
        $jsonContent = file_get_contents($jsonPath);
        $this->abilities = json_decode($jsonContent, true);
    }

    /**
     * Obtiene todas las habilidades
     */
    public function getAllAbilities(): array
    {
        return $this->abilities;
    }

    /**
     * Obtiene una habilidad por nombre
     */
    public function getAbility(string $name): ?array
    {
        foreach ($this->abilities as $ability) {
            if (strcasecmp($ability['name'], $name) === 0) {
                return $ability;
            }
        }
        return null;
    }

    /**
     * Calcula el multiplicador de ataque del atacante
     */
    public function getAttackerMultiplier(
        ?string $abilityName,
        string $moveType,
        string $moveCategory,
        int $movePower,
        array $attackerTypes
    ): float {
        if (!$abilityName || $abilityName === 'Ninguna') {
            return 1.0;
        }

        $ability = $this->getAbility($abilityName);
        if (!$ability) {
            return 1.0;
        }

        $multiplier = 1.0;

        switch ($ability['effect']) {
            case 'stat_boost':
                // Huge Power, Pure Power, Hustle
                if ($moveCategory === 'physical' && $ability['stat'] === 'attack') {
                    $multiplier *= $ability['multiplier'];
                } elseif ($moveCategory === 'special' && $ability['stat'] === 'special_attack') {
                    $multiplier *= $ability['multiplier'];
                }
                break;

            case 'type_boost_low_hp':
                // Blaze, Torrent, Overgrow, Swarm (asumimos HP bajo siempre)
                if ($this->translateTypeToEnglish($moveType) === $ability['type']) {
                    $multiplier *= $ability['multiplier'];
                }
                break;

            case 'type_conversion':
                // Pixilate, Aerilate, Refrigerate, Galvanize
                if (isset($ability['boost'])) {
                    $convertFrom = $ability['converts'];
                    $englishMoveType = $this->translateTypeToEnglish($moveType);
                    if ($convertFrom === 'Normal' && $englishMoveType === 'Normal') {
                        $multiplier *= $ability['boost'];
                    }
                }
                break;

            case 'low_power_boost':
                // Technician
                if ($movePower <= $ability['threshold']) {
                    $multiplier *= $ability['multiplier'];
                }
                break;

            case 'contact_boost':
                // Tough Claws (asumimos contacto para físicos)
                if ($moveCategory === 'physical') {
                    $multiplier *= $ability['multiplier'];
                }
                break;

            case 'power_boost_no_effects':
                // Sheer Force
                $multiplier *= $ability['multiplier'];
                break;

            case 'weather_boost':
                // Solar Power, Sand Force (no implementamos clima por ahora)
                break;
        }

        return $multiplier;
    }

    /**
     * Calcula el multiplicador de defensa del defensor
     */
    public function getDefenderMultiplier(
        ?string $abilityName,
        string $moveType,
        string $moveCategory,
        float $typeEffectiveness
    ): float {
        if (!$abilityName || $abilityName === 'Ninguna') {
            return 1.0;
        }

        $ability = $this->getAbility($abilityName);
        if (!$ability) {
            return 1.0;
        }

        $multiplier = 1.0;
        $englishMoveType = $this->translateTypeToEnglish($moveType);

        switch ($ability['effect']) {
            case 'type_resistance':
                // Thick Fat
                if (in_array($englishMoveType, $ability['types'])) {
                    $multiplier *= $ability['multiplier'];
                }
                break;

            case 'type_immunity':
                // Levitate, Water Absorb, Volt Absorb
                if ($englishMoveType === $ability['type']) {
                    return 0.0; // Inmunidad total
                }
                break;

            case 'type_immunity_boost':
                // Flash Fire
                if ($englishMoveType === $ability['type']) {
                    return 0.0; // Inmunidad total
                }
                break;

            case 'effectiveness_reduction':
                // Filter, Solid Rock
                if ($typeEffectiveness > 1.0) {
                    $multiplier *= $ability['multiplier'];
                }
                break;

            case 'hp_based_resistance':
                // Multiscale (asumimos HP completo)
                $multiplier *= $ability['multiplier'];
                break;

            case 'mixed_resistance':
                // Fluffy
                if ($moveCategory === 'physical') {
                    $multiplier *= $ability['resists']['contact'];
                }
                if ($englishMoveType === 'Fire') {
                    $multiplier *= $ability['resists']['Fire'];
                }
                break;
        }

        return $multiplier;
    }

    /**
     * Verifica si la habilidad modifica el STAB
     */
    public function getStabMultiplier(?string $abilityName): float
    {
        if (!$abilityName || $abilityName === 'Ninguna') {
            return 1.5; // STAB normal
        }

        $ability = $this->getAbility($abilityName);
        if (!$ability) {
            return 1.5;
        }

        // Adaptability aumenta STAB de 1.5x a 2x
        if ($ability['effect'] === 'stab_boost') {
            return 2.0;
        }

        return 1.5;
    }

    /**
     * Traduce tipo de español a inglés
     */
    private function translateTypeToEnglish(string $typeSpanish): string
    {
        $translations = [
            'Normal' => 'Normal',
            'Fuego' => 'Fire',
            'Agua' => 'Water',
            'Eléctrico' => 'Electric',
            'Planta' => 'Grass',
            'Hielo' => 'Ice',
            'Lucha' => 'Fighting',
            'Veneno' => 'Poison',
            'Tierra' => 'Ground',
            'Volador' => 'Flying',
            'Psíquico' => 'Psychic',
            'Bicho' => 'Bug',
            'Roca' => 'Rock',
            'Fantasma' => 'Ghost',
            'Dragón' => 'Dragon',
            'Siniestro' => 'Dark',
            'Acero' => 'Steel',
            'Hada' => 'Fairy'
        ];

        return $translations[$typeSpanish] ?? $typeSpanish;
    }
}
