
// ejercicio4.php

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejercicio 4</title>
</head>
<body>

    <h1>Ejercicio 4</h1>
    <?php

$hoy = date("d");
$fecha_objetivo = new Date_Time(2025,09,17);
$dias_llevados = $fecha_objetivo - $hoy;


    for ($i = $hoy; $i < $fecha_objetivo; $i++) {
        echo "<p>faltan $i dias</p>";
    }




    ?>
</body>
</html>