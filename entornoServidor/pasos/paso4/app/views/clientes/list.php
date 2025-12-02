<?php
// paso4/app/views/clientes/list.php
require __DIR__ . '/../header.php';
?>
<p>
 <a class="btn" href="/temp/pasos/paso4/public/index.php?clientes/add">Añadir cliente</a>
 <a class="btn" href="/temp/pasos/paso4/public/clientes">Refrescar</a>
</p>
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
 <td><?= esc($c['id']) ?></td>
 <td><?= esc($c['nombre']) ?></td>
 <td><?= esc($c['email']) ?></td>
 <td><?= esc($c['telefono']) ?></td>
 <td><?= esc($c['direccion']) ?></td>
 <td><?= esc($c['creado_at']) ?></td>
 <td>
 <a href="/paso4/public/clientes/edit/<?= $c['id'] ?>">Editar</a> |
 <a href="/paso4/public/clientes/delete/<?= $c['id'] ?>" onclick="return confirm('¿Borrar cliente #<?= $c['id'] 
?>?')">Borrar</a>
 </td>
 </tr>
 <?php endforeach; ?>
 <?php endif; ?>
 </tbody>
</table>
<?php
require __DIR__ . '/../footer.php';
