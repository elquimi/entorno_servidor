<?php
class Proyect{
    private function __construct ( 
    private int $id, 
    private string $name, 
    private string $status = "Pending"
    ) {}




    public function __toString(): string{
        return "id: {$this-> id}, nombre: {$this -> name}, status: {$this -> status}";
    }

    public function getId():int{
        return $this->name;
    }
    public function getStatus(): string{
        return $this->status;
    }

    


  
}






?>
