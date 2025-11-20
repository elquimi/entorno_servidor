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

    public function update($id,$cantidad,$precio):bool
    {
        $actualizar = $this->pdo->prepare("UPDATE productos SET stock = stock + ? ,precio = ? WHERE id = ?");
        return $actualizar->execute([$cantidad,$precio,$id]);

    }

    public function create($nombre,$cantidad,$precio,$categoria):bool{
        $crear = $this->pdo->prepare("INSERT INTO productos (nombre,categoria,precio,stock) values (?,?,?,?)");
        return $crear->execute([$nombre ?? '',$categoria,$precio,$cantidad ?? 0]);
    }

    public function eliminar($id):bool{
        $eliminar = $this->pdo->prepare("DELETE FROM productos where id = ?");
        return $eliminar->execute([$id]);


        
    }
    public function getById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); // Devuelve el array del producto o false
}









}



?>