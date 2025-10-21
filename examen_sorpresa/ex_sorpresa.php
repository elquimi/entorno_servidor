<?php

$capital_inicial = $_POST['capital'];
$tasa = $_POST['tasa'];
$anyos = $_POST['anyos'];





for($i = 1, $i <= $anyos, $i++){



$interes = $capital_inicial * ($tasa / 100);
$balance_acumulado = $capital_inicial + ($interes * $i);
$creacion = echo ("<tr> <td>"+ $i + "</td> <td> "+ $interes +"</td> <td>" + $balance_acumulado +  "</td> </tr>");
}




?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedir un número</title>
</head>
<body>
    <form action="ex_sorpresa.php" method="post">



        <label for="capital">Introduce el capital inicial:</label>
        <input type="number" id="capital" name="capital" required min = "0" >
        
    </br>

        <label for="tasa">introduce la tasa de interes al año:</label>
        <input type="number" id="tasa" name="tasa" required >

    </br>
       
        <label for="anyos">introduce los años de la inversion:</label>
        <input type="number" id="anyos" name="anyos" required >
    
    </br>

        <button type="submit">Enviar</button>

       
    </form>



    <table border="1px">
        <tr>
            <th>año</th>
            <th>interes ganado</th>
            <th> balance final</th>
        </tr>

        
    </table>







</body>
</html>