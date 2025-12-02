<?php
// paso0/index.php
// PASO 0 — Código monolítico: conexión, lógica y vistas en un solo fichero.
// NO incluir header/footer en este paso (se hará a partir del paso1).
// ---------- CONFIGURACIÓN DE LA BD (ajusta si tu usuario/clave difieren) ------
$host = '127.0.0.1';
$db = 'dwes_mvc_ej';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// ---------- LÓGICA (acción = list | add | edit | delete) ----------
$action = $_REQUEST['action'] ?? 'list';

// ADD: insertar nuevo cliente
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre,email,telefono,direccion) VALUES (?,?,?,?)");
    $stmt->execute([$nombre, $email, $telefono, $direccion]);
    $action = 'list';
}

// EDIT: actualizar cliente
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $stmt = $pdo->prepare("UPDATE clientes SET nombre=?, email=?, telefono=?, direccion=? WHERE id=?");
    $stmt->execute([$nombre, $email, $telefono, $direccion, $id]);
    $action = 'list';
}

// DELETE: borrar cliente
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id=?");
        $stmt->execute([$id]);
    }
    $action = 'list';
}

// Obtener datos para editar
$cliente = null;
if ($action === 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id=?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ---------- ORDENACIÓN ----------
$sort = $_GET['sort'] ?? 'id';
$order = strtoupper($_GET['order'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

$valid_columns = ['id', 'nombre', 'email', 'telefono', 'direccion', 'creado_at'];
if (!in_array($sort, $valid_columns)) {
    $sort = 'id';
}

$order = $order === 'ASC' ? 'ASC' : 'DESC';
$order_icon = $order === 'ASC' ? '↑' : '↓';

// Construir URL base para ordenación
function sort_url($col) {
    global $sort, $order;
    $new_order = ($sort === $col && $order === 'DESC') ? 'ASC' : 'DESC';
    return "?action=list&sort=$col&order=$new_order";
}

// ---------- VISTA (HTML mezclado con PHP) ----------
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Paso 0 — Código Monolítico (CRUD Clientes)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: left; }
        th { background: #f4f4f4; cursor: pointer; user-select: none; }
        th a { text-decoration: none; color: inherit; display: block; }
        th:hover { background: #eaeaea; }
        .actions a { margin: 0 0.4rem; color: #007bff; }
        .actions a:hover { text-decoration: underline; }
        .button { display: inline-block; padding: 0.5rem 1rem; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .button:hover { background: #0056b3; }
        .small { font-size: 0.8rem; color: #666; }
        .note { color: #d00; font-weight: bold; }
        input, button { padding: 0.4rem; margin: 0.2rem 0; }
        label { display: block; margin: 0.5rem 0 0.2rem; }
    </style>
</head>
<body>
<header>
    <h1>Paso 0 — Código Monolítico (CRUD Clientes)</h1>
    <p class="note">Todo en un único fichero: conexión, lógica y vista. No hay header/footer separados en este paso.</p>
</header>

<?php if ($action === 'list'): ?>
    <p><a class="button" href="?action=add">Añadir cliente</a></p>

    <?php
    // Consulta con ordenación dinámica
    $sql = "SELECT * FROM clientes ORDER BY $sort $order";
    $stmt = $pdo->query($sql);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table>
        <thead>
            <tr>
                <th><a href="<?= sort_url('id') ?>">ID <?= ($sort === 'id') ? $order_icon : '' ?></a></th>
                <th><a href="<?= sort_url('nombre') ?>">Nombre <?= ($sort === 'nombre') ? $order_icon : '' ?></a></th>
                <th><a href="<?= sort_url('email') ?>">Email <?= ($sort === 'email') ? $order_icon : '' ?></a></th>
                <th><a href="<?= sort_url('telefono') ?>">Teléfono <?= ($sort === 'telefono') ? $order_icon : '' ?></a></th>
                <th><a href="<?= sort_url('direccion') ?>">Dirección <?= ($sort === 'direccion') ? $order_icon : '' ?></a></th>
                <th><a href="<?= sort_url('creado_at') ?>">Creado <?= ($sort === 'creado_at') ? $order_icon : '' ?></a></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clientes)): ?>
                <tr><td colspan="7">No hay clientes todavía.</td></tr>
            <?php else: ?>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['id']) ?></td>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= htmlspecialchars($c['direccion']) ?></td>
                        <td><?= htmlspecialchars($c['creado_at']) ?></td>
                        <td class="actions">
                            <a href="?action=edit&id=<?= $c['id'] ?>" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?= $c['id'] ?>" 
                               onclick="return confirm('¿Estás seguro de que deseas borrar el cliente #<?= $c['id'] ?> (<?= htmlspecialchars($c['nombre']) ?>)?.');" 
                               title="Borrar" style="color: #dc3545;">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

<?php elseif ($action === 'add'): ?>
    <h2>Añadir cliente</h2>
    <form method="post" action="?action=add">
        <label>Nombre<br><input name="nombre" required></label><br><br>
        <label>Email<br><input name="email" type="email" required></label><br><br>
        <label>Teléfono<br><input name="telefono"></label><br><br>
        <label>Dirección<br><input name="direccion"></label><br><br>
        <button type="submit">Crear</button>
        <a class="button" href="?action=list">Volver</a>
    </form>

<?php elseif ($action === 'edit' && $cliente): ?>
    <h2>Editar cliente #<?= htmlspecialchars($cliente['id']) ?></h2>
    <form method="post" action="?action=edit">
        <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id']) ?>">
        <label>Nombre<br><input name="nombre" required value="<?= htmlspecialchars($cliente['nombre']) ?>"></label><br><br>
        <label>Email<br><input name="email" type="email" required value="<?= htmlspecialchars($cliente['email']) ?>"></label><br><br>
        <label>Teléfono<br><input name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>"></label><br><br>
        <label>Dirección<br><input name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>"></label><br><br>
        <button type="submit">Guardar</button>
        <a class="button" href="?action=list">Volver</a>
    </form>

<?php else: ?>
    <p>Acción no reconocida. <a href="?action=list">Volver a la lista</a></p>
<?php endif; ?>

<hr>
<p class="small">Estructura de este paso:<br><code>paso0/ index.php (todo en un solo fichero)</code></p>
</body>
</html>