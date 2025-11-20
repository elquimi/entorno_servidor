<?php if ($mensaje != ""): ?>
            <div class="mensaje">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <fieldset>
            <legend>Listado paises</legend>
            <ul>
               
            <?php
            foreach ($productos as $producto):
            ?>
              <li <?php if ($producto['stock'] < 5) { echo 'style="color:red; font-weight:bold;"'; } ?>>

    <p>
       Producto: <?= $producto['nombre']?> | Stock: <?= $producto['stock']?>
       
       <?php if ($producto['stock'] < 5) echo "(¡BAJO STOCK!)"; ?>
    </p>

</li>
            <?php
            endforeach;
            ?>
            </ul>
        </fieldset>