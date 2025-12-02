<?php if ($mensaje != ""): ?>
            <div class="mensaje">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <fieldset>
            <legend>Listado animales</legend>
            <ul>
               
            <?php
            foreach ($animales as $animal):
            ?>
              <li>

    <p>
      nombre: <?= $animal['nombre']?> | especie: <?= $animal['especie']?> | edad: <?= $animal['edad']?> 
       
      

        <form method="POST" action="index.php">
            <input type="hidden" name="accion" value="eliminar">
            <input type="hidden" name="id" value="<?= $animal['id']?>">
            
            <button type="submit" accion="index.php">eliminar</button>
       </form>

        <form method="POST" action="index.php">
            <input type="hidden" name="accion" value="form_editar">
            <input type="hidden" name="id" value="<?= $animal['id']?>">
            
            <button type="submit" accion="index.php">editar</button>
       </form>

       

      
       
      
    </p>

</li>
            <?php
            endforeach;
            ?>
            </ul>
        </fieldset>

        <div>
        <form method="POST" action="index.php">
            <input type="hidden" name="accion" value="vaciar">
            
            <button type="submit" accion="index.php">Vaciar Listado Completo</button>
       </form></div>
       
</div>



<?php
