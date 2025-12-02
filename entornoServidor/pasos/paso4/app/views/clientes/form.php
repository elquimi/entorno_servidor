<?php
// paso4/app/views/clientes/form.php
require __DIR__ . '/../header.php';
$editing = (isset($action) && $action === 'edit' && !empty($cliente));
?>
<h2><?= $editing ? 'Editar cliente #' . esc($cliente['id']) : 'Añadir cliente' ?></h2>
<form method="post" action="">
 <?php if ($editing): ?>
 <input type="hidden" name="id" value="<?= esc($cliente['id']) ?>">
 <?php endif; ?>
 <label>Nombre<br><input name="nombre" required style="width:100%" value="<?= $editing ? 
esc($cliente['nombre']) : '' ?>"></label><br><br>
 <label>Email<br><input type="email" name="email" required style="width:100%" value="<?= $editing ? 
esc($cliente['email']) : '' ?>"></label><br><br>
 <label>Teléfono<br><input name="telefono" style="width:100%" value="<?= $editing ? 
esc($cliente['telefono']) : '' ?>"></label><br><br>
 <label>Dirección<br><input name="direccion" style="width:100%" value="<?= $editing ? 
esc($cliente['direccion']) : '' ?>"></label><br><br>
 <button type="submit"><?= $editing ? 'Guardar' : 'Crear' ?></button>
 <a class="btn" href="/clientes">Cancelar</a>
</form>
<?php
require __DIR__ . '/../footer.php';
