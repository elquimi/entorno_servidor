<?php


/*Un usuario se registra en NexusCore a través de un formulario. El nombre que introduce es " elena vázquez ". 
Antes de guardarlo, necesitamos normalizarlo. Realiza los siguientes pasos: 
1. Usa trim() para eliminar los espacios en blanco innecesarios al principio y al final. 
2. Usa strtolower() para convertir toda la cadena a minúsculas. 
3. Usa ucwords() (¡investígalo!) para poner en mayúscula la primera letra de cada palabra. 
4. Muestra el nombre original y el nombre normalizado.*/ 



$nombre = $_POST['nombre'];
$nombre_trim = trim($nombre);
$nombre_lower = strtolower($nombre_trim);
echo "Nombre original: " . $nombre . "<br>";
$nombre_normalizado = ucwords($nombre_lower);
echo ( "<p> <strong>Nombre normalizado:  . $nombre_normalizado </strong></p>" );




?>