<?php


require_once "nomina.php";


$nombre = $_POST['nombre'];
$fecha_nacimiento = $_POST['fechaNacimiento'];
$horas_semanales = $_POST['horasRealizadas'];
$salario_hora = $_POST['salarioHora'];
$retencion = $_POST['retencion'];

$empleado = new nomina($nombre, $fecha_nacimiento, $horas_semanales, $salario_hora, $retencion);






function calcularSalarioBruto(String $horas_semanales, float $salario_hora, float $retencion): float{
   $total_salario_bruto = 0;
   $horas = explode("|", $horas_semanales);

for($i=0; $i < count($horas); $i++){
    if($horas[$i] > 8 && $i <= 4){
        $total_salario_bruto += (int)$horas[$i] * (1.5 * $salario_hora);
    }elseif($i > 4){
        $total_salario_bruto += (int)$horas[$i] * (1.25* $salario_hora);
    } else {
    $total_salario_bruto += (int)$horas[$i] * $salario_hora;
    }
}

return $total_salario_bruto;
}

$total_salario_bruto = calcularSalarioBruto($horas_semanales, $salario_hora, $retencion);
echo $empleado;
echo '
<div style="
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1.5rem;
    font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
">
    <h2 style="
        text-align: center;
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        color: #333;
    ">💼 Resumen Salarial</h2>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
        <span style="font-weight: 600; color: #444;">💰 Salario bruto:</span>
        <span style="font-size: 1.1rem; font-weight: bold; color: #222;">' . number_format($total_salario_bruto, 2) . ' €</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-weight: 600; color: #444;">🧾 Salario neto:</span>
        <span style="font-size: 1.1rem; font-weight: bold; color: #1a5fd0;">' . number_format($total_salario_bruto * (1 - $retencion / 100), 2) . ' €</span>
    </div>
</div>
';






?>
