<?php
// index.php
require 'db.php';

// Aquí irá la lógica PHP más adelante (Paso 3)
$mensaje = ""; 
$paises = []; // Array vacío temporal para que no dé error el HTML

$mensaje = "";



if ($_SERVER['REQUEST_METHOD'] === 'POST'){

$accion = $_POST['accion'] ?? '';


if ($accion === 'anadir'){
   $nombre_pais = trim($_POST['nombre_pais']);
   $nombre_capital = trim($_POST['nombre_capital']);



   if (empty($nombre_pais) || empty($nombre_capital)){
    $mensaje = "!error, los campos tienen que estar completos!";
}else{
    $consulta = $pdo ->prepare("SELECT * FROM paises WHERE nombre = ?");
    $consulta->execute([$nombre_pais]);
    $paisEncontrado = $consulta->fetch();

    if ($paisEncontrado){
        if($paisEncontrado['capital'] !==  $nombre_capital){

            $actualizar  = $pdo->prepare("UPDATE paises SET capital = ? where id = ?");
            $actualizar->execute([$nombre_capital, $paisEncontrado['id']]);
            $mensaje = "el pais si que existe, se ha cambiado la capital a $nombre_capital.";
        }else{
            $mensaje = "el pais ya tiene esa capital, no se hace nada";
        }

        }else {
            $insertar = $pdo->prepare("INSERT INTO paises (nombre, capital) VALUES (?,?)");
            $insertar->execute([$nombre_pais,$nombre_capital]);
            $mensaje = "Pais añadido con exito";
        }


    }

    }

    if ($accion === "vaciar"){
        $pdo->exec("TRUNCATE TABLE paises");
        $mensaje = "lista vaciada.";
    }




}






$sentencia = $pdo->query("select * from paises ORDER BY nombre ASC");
$paises = $sentencia->fetchAll();




















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
              <p>el pais es <?= $pais['nombre']?> y la capital es <?= $pais['capital']?> que tiene el numero <?= $pais['id']?></p>
             
              
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

                <label>Pais:</label>
                <input type="text" name="nombre_pais"><br><br>

                <label>Capital:</label>
                <input type="text" name="nombre_capital"><br><br>

                <button type="submit">Añadir país</button>
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