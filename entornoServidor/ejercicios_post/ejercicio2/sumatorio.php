<?php

$numero = $_POST['numero'];
$suma = 0;
$contador = 1;

while ($contador <= $numero){
    $suma += $contador;
    $contador++;
}

echo "El sumatorio de $numero es: $suma";


?>