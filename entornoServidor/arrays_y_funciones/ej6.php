<?php

/*Se nos ha proporcionado un array con las horas que cada desarrollador ha dedicado a las tareas del sprint actual. 
Necesitamos una función que calcule y devuelva las métricas clave. 
1. Crea una función calcularMetricas(array $horas) que reciba una lista de horas. 
2. Dentro de la función, calcula: * El número total de tareas (count). 
* El total de horas dedicadas (array_sum). * La media de horas por tarea (total horas / total tareas). 
3. La función debe devolver un array asociativo con estas métricas: 
['total_tareas' => ..., 'total_horas' => ..., 'media_horas' => ...]. 
4. Llama a la función y muestra los resultados de forma clara.
*/



 // Suponemos que recibimos un array de horas desde un formulario
 // para mostrar el array


function calcularMetricas(array $horas_array){
    $num_tareas = count($horas_array);
    $total_horas = array_sum($horas_array);
    $media_horas = $total_horas / $num_tareas;
    return [
        'total_tareas' => $num_tareas,
        'total_horas' => $total_horas,
        'media_horas' => $media_horas
    ];

}


if (isset($_POST['horas'])){
    $horas = $_POST['horas'];
    $horas_array = explode(',', $horas); // Convertimos la cadena en un array
    $metricas = calcularMetricas($horas_array);
    echo "Total de tareas: " . $metricas['total_tareas'] . "<br>";
    echo "Total de horas: " . $metricas['total_horas'] . "<br>";
    echo "Media de horas por tarea: " . $metricas['media_horas'] . "<br>";
}else{
    echo "No se han recibido horas. Por favor, usa el formulario.";
    $horas_array = [];
}

?>