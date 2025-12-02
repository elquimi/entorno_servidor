<?php if ($mensaje != ""): ?>
            <div class="mensaje">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <fieldset>
            <legend>Listado productos</legend>
            <ul>
               
            <?php
            foreach ($productos as $producto):
            ?>
              <li <?php if ($producto['stock'] < 10) { echo 'style="color:red; font-weight:bold;"'; } ?>>

    <p>
       Producto: <?= $producto['nombre']?> | Stock: <?= $producto['stock']?> | precio: <?= $producto['precio']?> | categoria: <?= $producto['categoria']?> | valor_total <?= $producto['stock'] * $producto['precio']?>
       
       <?php if ($producto['stock'] < 10) echo "(¡BAJO STOCK!)"; ?>

        <form method="POST" action="index.php">
            <input type="hidden" name="accion" value="eliminar">
            <input type="hidden" name="id" value="<?= $producto['id']?>">
            
            <button type="submit" accion="index.php">eliminar</button>
       </form>
      
    </p>

</li>
            <?php
            endforeach;
            ?>
            </ul>
        </fieldset>