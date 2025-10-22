<?php
require_once 'task.php';

session_start();



if (!isset($_SESSION['tareas'])){
    $_SESSION['tareas'] = [];
}

$tareas = &$_SESSION['tareas'];







if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$titulo = $_POST['title'] ?? 'titulo no definido';
$descripcion = $_POST['description'] ?? 'descripcion no definida';
$archivo = $_FILES['file'] ?? null;


if ($archivo && $archivo['error'] === UPLOAD_ERR_OK){
    $nombreArchivo = basename($archivo['name']);
    $rutaDestino = 'uploads/' . $nombreArchivo;
    move_uploaded_file($archivo['tmp_name'], $rutaDestino);
} else {
    $nombreArchivo = '';
    $rutaDestino = '';
}


$tarea = new task($titulo, $descripcion, false, $rutaDestino, $nombreArchivo);
$tareas[] = $tarea;

header('Location: index.php');
exit();

}


include 'head.php';
include 'body.php';
include 'foot.php';






?>