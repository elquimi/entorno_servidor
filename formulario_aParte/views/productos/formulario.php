 <fieldset>
            <legend>almacen</legend>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="anadir">

                <label>nombre del producto</label>
                <input type="text" name="nombre_producto"><br><br>

                <label>cantidad</label>
                <input type="number" name="cantidad"><br><br>

                 <label>precio</label>
                <input type="number" name="precio"><br><br>

                 <label>categoria</label>
                <input type="text" name="categoria"><br><br>



                <button type="submit">actualizar Stock</button>
                <button type="reset">Limpiar Campos</button>
                <button>
                <a href="index.php" >Cancelar</a>
                </button>
            </form>
        </fieldset>

       
    </div>