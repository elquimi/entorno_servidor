<div class="container">
    <h1>Gestor de Tareas</h1>

    <div class="task-list">
        <ul>
            <?php foreach ($tareas as $i => $tarea): ?>
                <li class="<?= $tarea->isCompleted() ? 'completed' : '' ?>">
                    <strong><?= htmlspecialchars($tarea->getTitulo(), ENT_QUOTES, 'UTF-8') ?></strong> - 
                    <?= $tarea->isCompleted() ? "Completada" : "Pendiente" ?>
                    <?php if ($tarea->getFileName()): ?>
                        - <a href="<?= htmlspecialchars($tarea->getFilePath(), ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                            <?= htmlspecialchars($tarea->getFileName(), ENT_QUOTES, 'UTF-8') ?>
                          </a>
                    <?php endif; ?>

                        


                    <br>
                    <?= nl2br(htmlspecialchars($tarea->getDescripcion(), ENT_QUOTES, 'UTF-8')) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <h2>Añadir nueva tarea</h2>
    <form method="post" enctype="multipart/form-data" class="task-form">
        <label>Título: <input type="text" name="title" required></label><br>
        <label>Descripción: <textarea name="description"></textarea></label><br>
        <label>Archivo adjunto: <input type="file" name="file"></label><br>
        <button type="submit">Añadir tarea</button>
    </form>
    <form action="index.php">
      <button type="submit"></button>
    </form>

    <br>
    <a href="reset.php" class="reset-button">Borrar todas las tareas</a>
</div>
