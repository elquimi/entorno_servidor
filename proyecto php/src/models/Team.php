<?php

namespace models;

/**
 * Modelo para un equipo de Pokémon personalizado
 */
class Team
{
    public $id;
    public $name;
    public $description;
    public $members = []; // Array de CustomPokemon
    public $createdAt;
    public $updatedAt;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? uniqid('team_');
        $this->name = $data['name'] ?? 'Mi Equipo';
        $this->description = $data['description'] ?? '';
        $this->members = $data['members'] ?? [];
        $this->createdAt = $data['createdAt'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updatedAt'] ?? date('Y-m-d H:i:s');
    }

    public function addMember($customPokemon)
    {
        if (count($this->members) < 6) {
            $this->members[] = $customPokemon;
            $this->updatedAt = date('Y-m-d H:i:s');
            return true;
        }
        return false; // Máximo 6 miembros
    }

    public function removeMember($pokemonId)
    {
        $this->members = array_filter($this->members, function($p) use ($pokemonId) {
            return $p->id !== $pokemonId;
        });
        $this->members = array_values($this->members); // Reindexa el array
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function updateMember($pokemonId, $updatedData)
    {
        foreach ($this->members as &$member) {
            if ($member->id === $pokemonId) {
                foreach ($updatedData as $key => $value) {
                    $member->$key = $value;
                }
                $this->updatedAt = date('Y-m-d H:i:s');
                return true;
            }
        }
        return false;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'members' => array_map(function($m) {
                return $m instanceof CustomPokemon ? $m->toArray() : $m;
            }, $this->members),
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }
}
?>
