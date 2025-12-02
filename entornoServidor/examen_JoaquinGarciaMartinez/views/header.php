<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>RefugioAnimal</title>
    <link rel="stylesheet" href="style.css">
   
    
</head>
<body>
    <div class="container">
        <h1>añadir animal</h1>
        <div style="background: #eee; padding: 5px; border: 1px solid #ccc; margin-bottom: 10px;">
            Operaciones realizadas en esta sesión: 
            <strong>
                <?= $_SESSION['operaciones'] ?? 0 ?>
            </strong>
        </div>