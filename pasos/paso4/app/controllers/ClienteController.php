<?php
// paso4/app/controllers/ClienteController.php
class ClienteController
{
 private $model;
 private $viewsPath;
 public function __construct(PDO $pdo)
 {
 $this->model = new ClienteModel($pdo);
 $this->viewsPath = __DIR__ . '/../views';
 }
 // lista
 public function index()
 {
 $clientes = $this->model->getAll();
 include $this->viewsPath . '/clientes/list.php';
 }
 // crear: mostrar form y procesar POST
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
 // redirigir a la lista (ruta relativa a public/)
 header('Location: /clientes');
 exit;
 }
 $action = 'add';
 $cliente = null;
 include $this->viewsPath . '/clientes/form.php';
 }
 // editar: mostrar form y procesar POST
 // $params puede incluir el id en $params[0]
 public function edit(array $params = [])
 {
 $id = isset($params[0]) ? (int)$params[0] : (int)($_REQUEST['id'] ?? 0);
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $id = (int)($_POST['id'] ?? $id);
 $data = [
 'nombre' => trim($_POST['nombre'] ?? ''),
 'email' => trim($_POST['email'] ?? ''),
 'telefono' => trim($_POST['telefono'] ?? ''),
 'direccion' => trim($_POST['direccion'] ?? '')
 ];
 $this->model->update($id, $data);
 header('Location: /clientes');
 exit;
 }
 $cliente = $this->model->getById($id);
 $action = 'edit';
 include $this->viewsPath . '/clientes/form.php';
 }
 // borrar: id en params[0]
 public function delete(array $params = [])
 {
 $id = isset($params[0]) ? (int)$params[0] : (int)($_REQUEST['id'] ?? 0);
 if ($id) $this->model->delete($id);
 header('Location: /clientes');
 exit;
 }
}