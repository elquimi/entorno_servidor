<?php
// paso3/index.php
require __DIR__ . '/db.php';
require __DIR__ . '/controllers/ClienteController.php';
// crear controlador
$ctrl = new ClienteController($pdo);
// decidir acción (index/list | add | edit | delete)
$action = $_GET['action'] ?? 'index';
// mapear acciones a métodos del controlador
switch ($action) {
 case 'add':
 $ctrl->add();
 break;
 case 'edit':
 $ctrl->edit();
 break;
 case 'delete':
 $ctrl->delete();
 break;
 case 'index':
 case 'list':
 default:
 $ctrl->index();
 break;
}