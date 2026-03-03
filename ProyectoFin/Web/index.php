<?php
require_once 'db.php';

$error = "";
$succes = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['crear_ticket'])) {
        $usuario = trim($_POST['usuario']);
        $falla = trim($_POST['falla']);

        if ($usuario !== "" && $falla !== "") {
            $stmt = $pdo->prepare("INSERT INTO tickets (usuario, falla) VALUES (:usuario, :falla)");
            $stmt->execute(['usuario' => $usuario, 'falla' => $falla]);
            $succes = "Ticket creado";
        } else {
            $error = "Complete todos los campos";
        }
    }

    if (isset($_POST['toggle_status'])) {
        $id = (int) $_POST['id'];
        $stmt = $pdo->prepare("SELECT solucionado FROM tickets WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $estado_actual = $stmt->fetchColumn();

        $nuevo_estado = $estado_actual ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE tickets SET solucionado = :estado WHERE id = :id");
        $stmt->execute([':estado' => $nuevo_estado, ':id' => $id]);
        $succes = "Estado actualizado";
    }

    if (isset($_POST['editar_ticket'])) { 
        $id = (int) $_POST['id'];
        $usuario = trim($_POST['usuario']);
        $falla = trim($_POST['falla']);

        if ($usuario !== "" && $falla !== "") {
            $stmt = $pdo->prepare("UPDATE tickets SET usuario = :usuario, falla = :falla WHERE id = :id");
            $stmt->execute([':usuario' => $usuario, ':falla' => $falla, ':id' => $id]);
            $succes = "Ticket editado";
        } else {
            $error = "Complete todos los campos al editar";
        }
    }


    if (isset($_POST['eliminar_ticket'])) {
        $id = (int) $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $succes = "Ticket eliminado";
    }
}

$stmt = $pdo->query("SELECT * FROM tickets ORDER BY fecha DESC");
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Tickets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        .exito { color: #2ecc71; background: #e8f8f0; padding: 10px; border-radius: 5px; }
        .error { color: #e74c3c; background: #fdeaea; padding: 10px; border-radius: 5px; }
        .solucionado { color: green; font-weight: bold; }
        .pendiente { color: orange; font-weight: bold; }
    </style>
</head>
<body class="container">

<h1>Gestión de Tickets</h1>

<?php if ($error): ?> <p class="error"><?= htmlspecialchars($error) ?></p> <?php endif; ?>
<?php if ($succes): ?> <p class="exito"><?= htmlspecialchars($succes) ?></p> <?php endif; ?>

<section>
    <h2>Crear Nuevo Ticket</h2>
    <form method="POST">
        <div class="grid">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required>
            <input type="text" name="falla" placeholder="Descripción del problema" required>
        </div>
        <button type="submit" name="crear_ticket">Crear Ticket</button>
    </form>
</section>

<hr>

<section>
    <h2>Lista de Tickets</h2>
    <table class="striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Falla</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= $ticket['id'] ?></td>
                <td><?= htmlspecialchars($ticket['usuario']) ?></td>
                <td><?= htmlspecialchars($ticket['falla']) ?></td>
                <td>
                    <?= $ticket['solucionado'] ? 'Solucionado' : 'Pendiente' ?>
                </td>
                <td>
                    <div role="group">
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                            <button type="submit" name="toggle_status" class="outline">Estado</button>
                        </form>
                        
                        <button class="contrast" onclick="abrirEditor(<?= $ticket['id'] ?>, '<?= addslashes($ticket['usuario']) ?>', '<?= addslashes($ticket['falla']) ?>')">
                            Editar
                        </button>

                        <form method="POST" style="margin:0;" onsubmit="return confirm('¿Eliminar ticket?');">
                            <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                            <button type="submit" name="eliminar_ticket" class="secondary">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<dialog id="modalEditar">
  <article>
    <header>
      <button aria-label="Close" rel="prev" onclick="cerrarEditor()"></button>
      <h3>Modificar Ticket</h3>
    </header>
    <form method="POST">
        <input type="hidden" name="id" id="edit_id">
        <label for="edit_usuario">Usuario</label>
        <input type="text" name="usuario" id="edit_usuario" required>
        <label for="edit_falla">Falla</label>
        <input type="text" name="falla" id="edit_falla" required>
        <footer>
          <button type="button" class="secondary" onclick="cerrarEditor()">Cancelar</button>
          <button type="submit" name="editar_ticket">Guardar Cambios</button>
        </footer>
    </form>
  </article>
</dialog>

<script>
const modal = document.getElementById('modalEditar');

function abrirEditor(id, usuario, falla) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_usuario').value = usuario;
    document.getElementById('edit_falla').value = falla;
    modal.setAttribute('open', true);
}

function cerrarEditor() {
    modal.removeAttribute('open');
}

window.onclick = function(event) {
    if (event.target == modal) cerrarEditor();
}
</script>

</body>
</html>