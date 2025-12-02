<?php



class AnimalModel
{
    private $pdo;
    public function __construct(PDO $pdo){
        $this -> pdo = $pdo;
    }



    public function getAll():array{
        $consulta = $this->pdo->query('SELECT * FROM animales ORDER BY id ASC');
        return $consulta->fetchAll();
    }

    public function getByName($nombre):?array{
    $obtener_animal = $this->pdo->prepare("SELECT * FROM animales where nombre = ?");
    $obtener_animal->execute([$nombre]);
    $animal_encontrado = $obtener_animal->fetch();

    return $animal_encontrado ?:null;
    }

    public function update($id,$nombre,$especie,$edad):bool
    {
        $actualizar = $this->pdo->prepare("UPDATE animales SET nombre = ? ,especie = ? , edad = ? WHERE id = ?");
        return $actualizar->execute([$nombre,$especie,$edad,$id]);

    }

    public function create($nombre,$especie,$edad):bool{
        $crear = $this->pdo->prepare("INSERT INTO animales (nombre,especie,edad) values (?,?,?)");
        return $crear->execute([$nombre ?? '',$especie ?? 'desconocida',$edad ?? 'desconocida']);
    }

    public function eliminar($id):bool{
        $eliminar = $this->pdo->prepare("DELETE FROM animales where id = ?");
        return $eliminar->execute([$id]);


        
    }

    public function getById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM animales WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); // Devuelve el array del producto o false
}

public function vaciar(){
        $vaciar = $this->pdo->exec("truncate table animales");
    }









}



?>