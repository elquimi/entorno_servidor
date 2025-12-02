<?php



class ProductoModel
{
    private $pdo;
    public function __construct(PDO $pdo){
        $this -> pdo = $pdo;
    }



    public function getAll():array{
        $consulta = $this->pdo->query('SELECT * FROM productos ORDER BY id ASC');
        return $consulta->fetchAll();
    }

    public function getByName($nombre):?array{
    $obtener_producto = $this->pdo->prepare("SELECT * FROM productos where nombre = ?");
    $obtener_producto->execute([$nombre]);
    $producto_encontrado = $obtener_producto->fetch();

    return $producto_encontrado ?:null;
    }

    public function update($id,$cantidad):bool
    {
        $actualizar = $this->pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        return $actualizar->execute([$cantidad,$id]);

    }

    public function create($nombre,$cantidad):bool{
        $crear = $this->pdo->prepare("INSERT INTO productos (nombre,stock) values (?,?)");
        return $crear->execute([$nombre ?? '',$cantidad ?? 0]);
    }

    public function vaciar(){
        $vaciar = $this->pdo->exec("truncate table productos");
    }









}



?>