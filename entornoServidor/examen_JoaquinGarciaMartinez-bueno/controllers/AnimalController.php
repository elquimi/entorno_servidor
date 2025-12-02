
<?php
require_once __DIR__ . '/../models/AnimalModel.php';

class AnimalController {
    private $model;
    private $basePath;

    public function __construct(PDO $pdo){
        $this->model = new AnimalModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    // FUNCIÓN 1: MOSTRAR LA PÁGINA PRINCIPAL
    public function index(){
        // 1. Pedimos los datos al modelo
        $animales = $this->model->getAll();
        
        // 2. Cargamos las vistas
        // Nota: Como redirigimos, el mensaje se pierde, así que lo dejamos vacío o usamos sesiones (avanzado)
        $mensaje = ''; 
        

        require $this->basePath . "/views/header.php";
        require $this->basePath . "/views/animales/formulario.php"; // Muestra la tabla
        require $this->basePath . "/views/animales/lista.php"; // Muestra la tabla
        require $this->basePath . "/views/footer.php";
    }



public function mostrarFormulario(){
   require $this->basePath . "/views/header.php";
    require $this->basePath . "/views/animales/formulario.php"; // Muestra la tabla
    require $this->basePath . "/views/footer.php";
}


public function mostrarFormularioEditar(){



     $id = trim($_POST['id'] ?? null);

    if ($id){

        $animal = $this->model->getById($id);

require $this->basePath . "/views/header.php";
    require $this->basePath . "/views/animales/editar.php"; // Muestra la tabla
    require $this->basePath . "/views/footer.php";
    } else {
        header('Location: index.php');
    }
     
}







    // FUNCIÓN 2: PROCESAR EL AÑADIR / ACTUALIZAR
    public function add(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_animal = trim($_POST['nombre_animal']);
            $especie = trim($_POST['especie']);
            $edad = trim($_POST['edad']);
            $id = trim($_POST['id']) ?? '';
            $archivo = $_FILES['file'] ?? null;
            
           

            if ($archivo && $archivo['error'] === UPLOAD_ERR_OK){
            $nombreArchivo = basename($archivo['name']);
            $rutaDestino = $basePath . '/uploads/' . $nombreArchivo;
            move_uploaded_file($archivo['tmp_name'], $rutaDestino);
        } else {
            $nombreArchivo = '';
            $rutaDestino = '';
        }



            if (!empty($nombre_animal) && !empty($especie) && !empty($edad)) {
                // Preguntamos al modelo
                $animal_encontrado = $this->model->getById($id);

                if ($animal_encontrado) {
                    
                    $this->model->update($animal_encontrado['id'],$nombre_animal, $especie, $edad);
                    
                } else {
                    // NO EXISTE -> CREAR
                    $this->model->create($nombre_animal,$especie,$edad);
                }
                $_SESSION['operaciones']++;
            }
        }
        // TRUCO DEL PDF: Redirigir para limpiar el formulario
        header('Location: index.php');
        exit;
    }

    
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


    public function vaciar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->vaciar();
        }
        // Redirigir al inicio
        header('Location: index.php');
        exit;
    }
}
?>