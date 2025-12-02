<?php

$numero = $_POST['numero'];

$factorial = 1;
for ($i = $numero; $i >=1 ; $i--){
    $factorial *= $i;
}

echo ($factorial)



?>