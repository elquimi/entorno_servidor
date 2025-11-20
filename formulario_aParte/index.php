<?php
// index.php
session_start();
// 1. Configuración y Cargas
require 'db.php';
require __DIR__ . '/controllers/ProductoController.php';

// 2. Instanciar Controlador
$controlador = new ProductoController($pdo);

// 3. DECIDIR QUÉ HACER (ROUTER BÁSICO)
// Miramos si viene alguna 'accion' por POST o GET. Si no, por defecto es 'index'.
$accion = $_REQUEST['accion'] ?? 'index';

switch ($accion) {
    case 'anadir':
        $controlador->add();
        break;
        
    case 'eliminar':
        
        $controlador->eliminar();
        break;

    case 'form_anadir':
        $controlador->mostrarFormulario();
        break;


    case 'editar':
        $controlador ->mostrarFormularioEditar();
        break;

    default:
        // Si no es ni añadir ni vaciar, mostramos la lista
        $controlador->index();
        break;
}
?>