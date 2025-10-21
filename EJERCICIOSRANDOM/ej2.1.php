<?php
$nivelAcceso = 1;

echo "<h3>Estado de Acceso para Nivel $nivelAcceso:</h3>";

switch ($nivelAcceso) {
    case 1:
        echo "<p>Acceso a Informes de Ventas.</p>";
        break;
    case 2:
        echo "<p>Acceso a Gestión de Clientes.</p>";
    case 3:
        echo "<p>Acceso a Contenidos Públicos.</p>";
    default:
        echo "<p>Permisos básicos de visualización.</p>";
}
?>