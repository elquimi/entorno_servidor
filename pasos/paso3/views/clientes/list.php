<?php
// paso3/views/clientes/list.php
// Esta vista espera la variable $clientes (proporcionada por el controlador).
require __DIR__ . '/../header.php';
?>
<p><a class="btn" href="index.php?action=add">Añadir cliente</a></p>
<table border="1" cellpadding="6" cellspacing="0" style="width:100%;border-collapse:collapse">
 <thead>
 
<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Dirección</th><th>Creado</th><t
h>Acciones</th></tr>
 </thead>
 <tbody>
 <?php if (empty($clientes)): ?>
 <tr><td colspan="7">No hay clientes.</td></tr>
 <?php else: ?>
 <?php foreach ($clientes as $c): ?>
 <tr>
 <td><?= htmlspecialchars($c['id']) ?></td>
 <td><?= htmlspecialchars($c['nombre']) ?></td>
 <td><?= htmlspecialchars($c['email']) ?></td>
 <td><?= htmlspecialchars($c['telefono']) ?></td>
 <td><?= htmlspecialchars($c['direccion']) ?></td>
 <td><?= htmlspecialchars($c['creado_at']) ?></td>
 <td>
 <a href="index.php?action=edit&id=<?= $c['id'] ?>">Editar</a> |
 <a href="index.php?action=delete&id=<?= $c['id'] ?>" onclick="return confirm('¿Borrar cliente #<?= 
$c['id'] ?>?')">Borrar</a>
 </td>
 </tr>
 <?php endforeach; ?>
 <?php endif; ?>
 </tbody>
</table>
<?php
require __DIR__ . '/../footer.php';