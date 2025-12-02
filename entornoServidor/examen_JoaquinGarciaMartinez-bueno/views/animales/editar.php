<h2>Editar Producto</h2>

<form method="POST" action="index.php" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="anadir"> 
    
    <input type="hidden" name="id" value="<?= $animal['id'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre_animal" value="<?= $animal['nombre'] ?>" required><br><br>

    <label>especie: </label>
    <input type="text" name="especie" value="<?= $animal['especie'] ?>" required><br><br>

    <label>edad: </label>
    <input type="number" name="edad" value="<?= $animal['edad'] ?>" required><br><br>

    

    

    <button type="submit">Guardar Cambios</button>
    <a href="index.php">Cancelar</a>
</form>