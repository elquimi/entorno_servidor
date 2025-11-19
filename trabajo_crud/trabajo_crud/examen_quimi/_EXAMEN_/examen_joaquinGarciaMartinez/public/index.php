<?php
require '../config.php';
require '../templates/header.php';


?>












    

<h1 class="titulo">Paises de la union europea</h1>

<div class="gestionar_paises">
    <p class="titulo">paises de la union europea</p>
    <form action="config.php">
        <label for="pais">pais: </label>
        <input type="text" name="pais">
</br>
</br>
        <label for="capital">Capital: </label>
        <input type="text" name="capital">

</br>
        <button class="añadir_pais">Añadir pais</button>
        <button class="limpiar">Limpiar</button>
    </form>
</div>






<?php
require '../templates/footer.php'; 
?>


</body>
</html>