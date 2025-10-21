<!-- Formulario para añadir una tarea -->
<form action="index.php" method="post" enctype="multipart/form-data">
  <label for="title">Título (obligatorio):</label><br>
  <input type="text" id="title" name="title" required><br><br>

  <label for="description">Descripción:</label><br>
  <textarea id="description" name="description"></textarea><br><br>

  <label for="file">Archivo adjunto (opcional):</label><br>
  <input type="file" id="file" name="file"><br><br>

  <button type="submit">Añadir tarea</button>
</form>

<hr>

<!-- Aquí luego se mostrará la lista de tareas -->
<div class="task-list">
  <?php if (!empty($tasks)): ?>
    <ul>
      <?php foreach ($tasks as $i => $task): ?>
        <li>
          <?php echo htmlspecialchars($task->title, ENT_QUOTES, 'UTF-8'); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No hay tareas todavía.</p>
  <?php endif; ?>
</div>