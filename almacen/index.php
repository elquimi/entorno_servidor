<?php

require 'db.php';


$mensaje = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST'){

$accion = $_POST['accion'] ?? '';


if ($accion === 'anadir'){
    $cantidad = trim($_POST['cantidad']);
    $nombre_producto = trim($_POST['nombre_producto']);


    if (empty($cantidad) || empty($nombre_producto)){
        $mensaje = "tienes que introducir valores";
    }else{

        $consulta = $pdo ->prepare('SELECT * from productos WHERE nombre = ?');
        $consulta -> execute([$nombre_producto]);
        $producto_encontrado = $consulta->fetch();

        if ($producto_encontrado){
            
        }
    }

}

    
}

































?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Examen Países UE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>PAISES DE LA UNIÓN EUROPEA</h1>

        <?php if ($mensaje != ""): ?>
            <div class="mensaje">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <fieldset>
            <legend>Listado paises</legend>
            <ul>
               
            <?php
            foreach ($paises as $pais):
            ?>
                <li>
              <p>el pais es <?= $pais['nombre']?> y la capital es <?= $pais['capital']?></p>
             
              
            </li>
            <?php
            endforeach;
            ?>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Pais de la unión europea</legend>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="anadir">

                <label>nombre del producto</label>
                <input type="text" name="nombre_producto"><br><br>

                <label>cantidad</label>
                <input type="number" name="cantidad"><br><br>

                <button type="submit">actualizar Stock</button>
                <button type="reset">Limpiar Campos</button>
            </form>
        </fieldset>

        <fieldset>
            <legend>Vaciar Listado</legend>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="vaciar">
                <button type="submit" style="color: red;">Vaciar</button>
            </form>
        </fieldset>
    </div>
</body>
</html>