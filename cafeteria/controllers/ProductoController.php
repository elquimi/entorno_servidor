
<?php
require_once __DIR__ . '/../models/ProductoModel.php';

class ProductoController {
    private $model;
    private $basePath;

    public function __construct(PDO $pdo){
        $this->model = new ProductoModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    // FUNCIÓN 1: MOSTRAR LA PÁGINA PRINCIPAL
    public function index(){
        // 1. Pedimos los datos al modelo
        $productos = $this->model->getAll();
        
        // 2. Cargamos las vistas
        // Nota: Como redirigimos, el mensaje se pierde, así que lo dejamos vacío o usamos sesiones (avanzado)
        $mensaje = ''; 
        $valor_total = 0;

        require $this->basePath . "/views/header.php";
        require $this->basePath . "/views/productos/lista.php"; // Muestra la tabla
        require $this->basePath . "/views/productos/formulario.php"; // Muestra el formulario
        require $this->basePath . "/views/footer.php";
    }

    // FUNCIÓN 2: PROCESAR EL AÑADIR / ACTUALIZAR
    public function add(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cantidad = trim($_POST['cantidad']);
            $nombre_producto = trim($_POST['nombre_producto']);
            $categoria = trim($_POST['categoria']);
            $precio = trim($_POST['precio']);

            if (!empty($cantidad) && !empty($nombre_producto) && !empty($categoria) && !empty($precio)) {
                // Preguntamos al modelo
                $producto_encontrado = $this->model->getByName($nombre_producto);

                if ($producto_encontrado) {
                    // SI EXISTE -> ACTUALIZAR
                    $this->model->update($producto_encontrado['id'], $cantidad, $precio);
                } else {
                    // NO EXISTE -> CREAR
                    $this->model->create($nombre_producto, $cantidad,$precio,$categoria);
                }
                $_SESSION['operaciones']++;
            }
        }
        // TRUCO DEL PDF: Redirigir para limpiar el formulario
        header('Location: index.php');
        exit;
    }

    // FUNCIÓN 3: VACIAR
    public function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

           $id = trim($_POST['id']);


            $this->model->eliminar($id);
            $_SESSION['operaciones']++;
        }
        // Redirigir al inicio
        header('Location: index.php');
        exit;
    }
}
?>