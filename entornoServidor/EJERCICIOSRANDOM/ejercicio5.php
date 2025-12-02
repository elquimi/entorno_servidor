<?php

$departamentoId = 2;

$nombreDepartamento = match ($departamentoId) {
    1 => 'Tecnología',
    2 => 'Recursos Humanos',
    3 => 'Marketing',
    default => 'Desconocido',
};

echo "El departamento seleccionado es: $nombreDepartamento";
?>