 <fieldset>
            <legend>almacen</legend>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="anadir">

                <label>nombre del producto</label>
                <input type="text" name="nombre_producto"><br><br>

                <label>cantidad</label>
                <input type="number" name="cantidad"><br><br>

                <button type="submit">actualizar Stock</button>
                <button type="reset">Limpiar Campos</button>
            </form>
        </fieldset>

        <fieldset>
            <legend>Vaciar Listado</legend>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="vaciar">
                <button type="submit" style="color: red;">Vaciar</button>
            </form>
        </fieldset>
    </div>