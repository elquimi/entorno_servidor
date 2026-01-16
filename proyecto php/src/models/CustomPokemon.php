<?php

namespace models;

/**
 * Modelo para Pokémon personalizado (miembro del equipo)
 */
class CustomPokemon
{
    public $id; // ID único del pokémon personalizado
    public $nickname; // Mote del pokémon
    public $isCustom; // true si es personalizado, false si es de la API
    public $basePokemonName; // Nombre del pokémon base (si isCustom=false)
    public $basePokemonId; // ID del pokémon base (si isCustom=false)
    
    // Estadísticas
    public $hp;
    public $attack;
    public $defense;
    public $spAtk;
    public $spDef;
    public $speed;
    
    // Habilidades y ataques
    public $ability;
    public $moves = []; // Array de ataques
    public $type; // Tipo(s)
    public $image; // URL de imagen
    
    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? uniqid('pokemon_');
        $this->nickname = $data['nickname'] ?? '';
        $this->isCustom = $data['isCustom'] ?? true;
        $this->basePokemonName = $data['basePokemonName'] ?? '';
        $this->basePokemonId = $data['basePokemonId'] ?? null;
        $this->hp = $data['hp'] ?? 0;
        $this->attack = $data['attack'] ?? 0;
        $this->defense = $data['defense'] ?? 0;
        $this->spAtk = $data['spAtk'] ?? 0;
        $this->spDef = $data['spDef'] ?? 0;
        $this->speed = $data['speed'] ?? 0;
        $this->ability = $data['ability'] ?? '';
        $this->moves = $data['moves'] ?? [];
        $this->type = $data['type'] ?? '';
        $this->image = $data['image'] ?? '';
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'isCustom' => $this->isCustom,
            'basePokemonName' => $this->basePokemonName,
            'basePokemonId' => $this->basePokemonId,
            'hp' => $this->hp,
            'attack' => $this->attack,
            'defense' => $this->defense,
            'spAtk' => $this->spAtk,
            'spDef' => $this->spDef,
            'speed' => $this->speed,
            'ability' => $this->ability,
            'moves' => $this->moves,
            'type' => $this->type,
            'image' => $this->image
        ];
    }
}
?>
