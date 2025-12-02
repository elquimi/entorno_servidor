<?php

$numero = $_GET['numero'];
echo "El número recibido es: $numero";


for ($i = 0; $i < 11; $i++){
    echo "$numero x $i = " . $numero * $i . "<br>";
}




?>

