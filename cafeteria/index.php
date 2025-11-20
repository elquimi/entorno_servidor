<?php
require 'db.php';
require __DIR__ . '/controllers/ProductoController.php';

// 2. Instanciar Controlador
$controlador = new ProductoController($pdo);




?>