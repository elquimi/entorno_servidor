<h2>Editar Producto</h2>

<form method="POST" action="index.php" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="anadir"> 
    
    <input type="hidden" name="id" value="<?= $producto['id'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre_producto" value="<?= $producto['nombre'] ?>" required><br><br>

    <label>Cantidad:</label>
    <input type="number" name="cantidad" value="<?= $producto['stock'] ?>" required><br><br>

    <label>Precio:</label>
    <input type="number" name="precio" step="0.01" value="<?= $producto['precio'] ?>" required><br><br>

    <label>Categoría:</label>
    <input type="text" name="categoria" value="<?= $producto['categoria'] ?>" required><br><br>

    

    <button type="submit">Guardar Cambios</button>
    <a href="index.php">Cancelar</a>
</form>