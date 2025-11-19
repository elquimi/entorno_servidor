<?php


$nombreARchivoLog = 'events.log';

$mensaje = date('y-m-d H:i:s '). " ------------ se ha ejecutado el script \n";

file_put_contents($nombreARchivoLog, $mensaje, FILE_APPEND | LOCK_EX);

echo "Evento registrado en el log.\n";


?>