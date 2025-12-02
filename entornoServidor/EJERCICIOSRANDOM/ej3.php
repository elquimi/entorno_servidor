<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo PHP y HTML</title>
</head>
<body>
    <?php
$nuevosEmpleados = [
    "Ana García",
    "Carlos Rodríguez",
    "Beatriz Fernández",
    "David Martínez"
];
foreach ($nuevosEmpleados as $empleado ) {
    echo "<p>Bienvenido(a) $empleado a la empresa.</p>";
}




?>
</body>
</html>