<?php

















$contrasena_introducida = $_POST['contrasena'];
$contrasena_correcta = 1234;


do {
    if ($contrasena_introducida != $contrasena_correcta) {
        echo "Contraseña incorrecta. Inténtalo de nuevo.<br>";
        break;
    } else {
        echo "Contraseña correcta. Acceso concedido.<br>";
        exit;
    }
} while (true);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de Token</title>
   
</head>
<body>
    
        <form method="post" action="contrasena.php">
            <label for="contrasena">Introduce el token de acceso:</label>
            <input type="number" name="contrasena" id="contrasena" required>
            <button type="submit">Enviar</button>
        </form>
   
</body>
</html>