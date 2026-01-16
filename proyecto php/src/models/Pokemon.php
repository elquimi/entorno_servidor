<?php

namespace models;

/**
 * Modelo de datos para Pokémon
 */
class Pokemon
{
    public $id;
    public $name;
    public $type;
    public $hp;
    public $attack;
    public $defense;
    public $spAtk;
    public $spDef;
    public $speed;
    public $image;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->hp = $data['hp'] ?? 0;
        $this->attack = $data['attack'] ?? 0;
        $this->defense = $data['defense'] ?? 0;
        $this->spAtk = $data['spAtk'] ?? 0;
        $this->spDef = $data['spDef'] ?? 0;
        $this->speed = $data['speed'] ?? 0;
        $this->image = $data['image'] ?? '';
    }

    /**
     * Obtiene el total de estadísticas
     */
    public function getTotalStats()
    {
        return $this->hp + $this->attack + $this->defense + $this->spAtk + $this->spDef + $this->speed;
    }

    /**
     * Convierte el objeto a array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'hp' => $this->hp,
            'attack' => $this->attack,
            'defense' => $this->defense,
            'spAtk' => $this->spAtk,
            'spDef' => $this->spDef,
            'speed' => $this->speed,
            'totalStats' => $this->getTotalStats(),
            'image' => $this->image
        ];
    }
}
?>
