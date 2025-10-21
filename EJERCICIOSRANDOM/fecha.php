<!DOCTYPE html>
<html>
<head>
    <title>Selecciona tu año de nacimiento</title>
</head>
<body>
    <form>
        <label for="anio">Año de nacimiento:</label>
        <select name="anio" id="anio">
            <?php
            $anio_actual = date("Y");
            for ($anio = $anio_actual; $anio >= 1900; $anio--) {
                echo "<option value=\"$anio\">$anio</option>";
            }
            ?>
        </select>
    </form>
    <form action="">
        <label for="mes">mes</label>
        <select name="mes" id="mes">
            <?php
            $mes_actual = date("m");
            for ($mes = $mes; $mes <= 12; $mes++) {
                $selected = ($mes == $mes_actual) ? 'selected' : '';
                echo "<option value=\"$mes\">$mes</option>";
            }
            ?>
        </select>

    </form>

<form >
    <label for="dia">dia</label>
    <select name="dia" id="dia">
        <?php
        for ($dia = 1; $dia <= 31; $dia++) {
            echo "<option value=\"$dia\">$dia</option>";
        }
        ?>
    </select>
</form>


</body>
</html>