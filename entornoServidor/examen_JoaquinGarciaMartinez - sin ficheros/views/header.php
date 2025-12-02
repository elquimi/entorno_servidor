<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>RefugioAnimal</title>
   
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #99ccff; /* Azul claro de fondo */
    padding: 20px;
}
.container {
    width: 800px;
    margin: 0 auto;
}
h1 {
    text-align: center;
}
fieldset {
    border: 1px solid #666;
    margin-bottom: 15px;
    background-color: #99ccff;
    padding: 15px;
}
legend {
    font-weight: bold;
    color: #000;
}
label {
    display: inline-block;
    width: 80px;
    font-weight: bold;
    color: darkblue;
}
input[type="text"] {
    width: 200px;
}
.mensaje {
    background-color: #fff;
    border: 1px solid red;
    color: red;
    padding: 10px;
    margin-bottom: 10px;
    text-align: center;
}
ul { list-style-type: none; padding: 0; }
li {
    border-bottom: 1px solid #666;
    padding: 5px;
    display: flex;
    justify-content: space-between;
}
    </style>
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