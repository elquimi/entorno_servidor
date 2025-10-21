<?php

$tamaño = $_POST['numero'];
$caracter = $_POST['numero1'];

echo "<pre>";
for ($i = 1; $i <= $tamaño; $i++){
    for ($j = 1; $j <= $tamaño; $j++){
        echo ($caracter);
    }
    echo ("</br>");
}
echo "</pre>";

?>