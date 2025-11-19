<?php
// paso3/controllers/ClienteController.php
require_once __DIR__ . '/../models/ClienteModel.php';
class ClienteController
{
 private $model;
 private $basePath;
 public function __construct(PDO $pdo)
 {
 $this->model = new ClienteModel($pdo);
 // ruta base del paso (útil para links relativos)
 $this->basePath = dirname(__DIR__);
 }
 /** Mostrar listado */
 public function index()
 {
 $clientes = $this->model->getAll();
 // la vista se encargará de incluir header/footer
 include $this->basePath . '/views/clientes/list.php';
 }
 /** Mostrar formulario y procesar creación */
 public function add()
 {
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $data = [
 'nombre' => trim($_POST['nombre'] ?? ''),
 'email' => trim($_POST['email'] ?? ''),
 'telefono' => trim($_POST['telefono'] ?? ''),
 'direccion' => trim($_POST['direccion'] ?? '')
 ];
 $this->model->create($data);
 header('Location: index.php');
 exit;
 }
 $action = 'add';
 $cliente = null;
 include $this->basePath . '/views/clientes/form.php';
 }
 /** Mostrar formulario y procesar edición */
 public function edit()
 {
 $id = (int)($_GET['id'] ?? 0);
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $id = (int)($_POST['id'] ?? $id);
 $data = [
 'nombre' => trim($_POST['nombre'] ?? ''),
 'email' => trim($_POST['email'] ?? ''),
 'telefono' => trim($_POST['telefono'] ?? ''),
 'direccion' => trim($_POST['direccion'] ?? '')
 ];
 $this->model->update($id, $data);
 header('Location: index.php');
 exit;
 }
 $cliente = $this->model->getById($id);
 $action = 'edit';
 include $this->basePath . '/views/clientes/form.php';
 }
 /** Borrar */
 public function delete()
 {
 $id = (int)($_GET['id'] ?? 0);
 if ($id) {
 $this->model->delete($id);
 }
 header('Location: index.php');
 exit;
 }
}
