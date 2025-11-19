<?php
// paso2/index.php
// Paso 2 — Usamos ClienteModel para todas las operaciones.
// incluir conexión y modelo
require __DIR__ . '/db.php';
require __DIR__ . '/models/ClienteModel.php';
// instanciar modelo
$model = new ClienteModel($pdo);
// acción: list | add | edit | delete
$action = $_REQUEST['action'] ?? 'list';
// --- Crear (ADD)
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
 $data = [
 'nombre' => trim($_POST['nombre'] ?? ''),
 'email' => trim($_POST['email'] ?? ''),
 'telefono' => trim($_POST['telefono'] ?? ''),
 'direccion' => trim($_POST['direccion'] ?? '')
 ];
 $model->create($data);
 // PRG: redirigir a la lista
 header('Location: index.php');
 exit;
}
// --- Editar (UPDATE)
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
 $id = (int)($_POST['id'] ?? 0);
 $data = [
 'nombre' => trim($_POST['nombre'] ?? ''),
 'email' => trim($_POST['email'] ?? ''),
 'telefono' => trim($_POST['telefono'] ?? ''),
 'direccion' => trim($_POST['direccion'] ?? '')
 ];
 $model->update($id, $data);
 header('Location: index.php');
 exit;
}
// --- Borrar (DELETE)
if ($action === 'delete') {
 $id = (int)($_GET['id'] ?? 0);
 if ($id) $model->delete($id);
 header('Location: index.php');
 exit;
}
// --- Obtener cliente para editar (GET)
$cliente = null;
if ($action === 'edit' && isset($_GET['id'])) {
 $cliente = $model->getById((int)$_GET['id']);
}
// --- Preparar datos para vistas
if ($action === 'list') {
 $clientes = $model->getAll();
}
// incluir header (título del paso)
require __DIR__ . '/views/header.php';
// incluir vista correspondiente
if ($action === 'list') {
 include __DIR__ . '/views/clientes/list.php';
} elseif ($action === 'add') {
 include __DIR__ . '/views/clientes/form.php';
} elseif ($action === 'edit') {
 include __DIR__ . '/views/clientes/form.php';
} else {
 echo "<p>Acción desconocida.</p>";
}
// incluir footer (estructura del paso)
require __DIR__ . '/views/footer.php';