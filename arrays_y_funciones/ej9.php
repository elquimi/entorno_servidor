











<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Slug</title>
</head>
<body>
    <h1>Generador de Slug</h1>

    <?php

/*Tenemos un log de accesos en formato [FECHA_HORA] [NIVEL] MENSAJE. 
Queremos crear una tabla HTML que muestre solo los logs de nivel ERROR. 
1. Usa explode() con el delimitador \n (salto de línea) para convertir el string del log en un array de líneas. 
2. Recorre el array de líneas con foreach. 
3. Dentro del bucle, por cada línea: * Usa strpos() para comprobar si la línea contiene la subcadena 
[ERROR]. * Si es un error, usa explode() o preg_match() para separar la fecha, el nivel y el mensaje. * Imprime una fila <tr> de una tabla con los datos extraídos.*/


$logData = <<<LOG
[2025-07-28 10:00:00] [INFO] User 'ana' logged in successfully.
[2025-07-28 10:01:15] [DEBUG] Database query executed.
[2025-07-28 10:02:30] [ERROR] Failed to connect to payment gateway.
[2025-07-28 10:03:00] [INFO] User 'luis' updated his profile.
[2025-07-28 10:05:00] [ERROR] Division by zero in financial report generator.
LOG;


$lienas = explode("\n", $logData);
$contador_error = 0;
foreach ($lienas as $linea){
    if(strpos($linea, '[ERROR]') !== false){
        $contador_error++;
    }
}
echo "total errores =" . $contador_error;
    
    ?>

    
</body>
</html>