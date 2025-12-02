<?php




if(!isset($_POST['numero_rand'])){
    $numero_rand = rand(1,100);
    echo($numero_rand);
} else{
    $numero_rand = $_POST['numero_rand'];
}



$mensaje = "";

if (isset ($_POST['numero'])){
$numerointento = $_POST['numero'];
if ($numero_rand > $numerointento){
    $mensaje = "has fallado, el aleatorio es mayor";
}elseif($numero_rand < $numerointento){
    $mensaje = "has fallado, el numero aleatorio es menor";
}else {$mensaje = "felicidades, has acertado";}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="ej8.php" method="post">
        <label for="numero">Introduce un número:</label>
        <input type="number" id="numero" name="numero" required>
        <input type="hidden" name="numero_rand" value="<?php echo $numero_rand; ?>">
        <button type="submit">Enviar</button>
    </form>
<?php
echo "<p>$mensaje</p>"
?>

</body>
</html>
