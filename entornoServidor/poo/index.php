<?php

require_once "Proyect.php";

$proyecto = new Proyect(1,"hola","pending");
echo "proyecto: {$proyecto -> name}, Estado: {$proyecto-> status}";


?>