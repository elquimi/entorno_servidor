<?php
$rol = 'editor';

if ($rol === 'admin') {
    echo "Acceso al panel de administración";
} else {
    echo "Acceso limitado al panel de contenidos";
}
?>