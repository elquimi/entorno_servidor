<?php

$nombreFichero = 'informe_trimestral.pdf';
$categoria = '';

if (str_ends_with($nombreFichero, '.pdf')) {
    $categoria = 'Documento PDF';
} elseif (str_ends_with($nombreFichero, '.docx')) {
    $categoria = 'Documento de Word';
} elseif (str_ends_with($nombreFichero, '.xlsx')) {
    $categoria = 'Hoja de Cálculo';
} else {
    $categoria = 'Fichero de tipo desconocido';
}

echo "El fichero '$nombreFichero' ha sido clasificado como: '$categoria'.";
?>