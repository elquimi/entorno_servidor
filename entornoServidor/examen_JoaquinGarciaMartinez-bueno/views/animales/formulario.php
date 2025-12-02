 <fieldset>
            <legend>añadir animal</legend>
            <form method="POST" action="index.php">
                <input type="hidden" name="accion" value="anadir">

                <label>nombre del animal</label>
                <input type="text" name="nombre_animal"><br><br>

                <label>especie</label>
                <input type="text" name="especie"><br><br>

                 <label>edad</label>
                <input type="number" name="edad"><br><br>


                <label>Archivo adjunto: <input type="file" name="file"></label><br>

                 



                <button type="submit">añadir/actualizar</button>
                
                
            </form>
        </fieldset>

       
    </div>