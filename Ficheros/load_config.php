<?php
$nombArchivo = "config.txt";
$config = [];

if( is_readable($nombArchivo)){
    $archivo = file($nombArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($archivo as $linea){
        $claveValor = explode("=", $linea, 2);

        $config[trim($claveValor[0])] = trim($claveValor[1]);
    
       
    }
}


foreach($config as $clave => $valor){
    echo "$clave : $valor\n";
}





?>