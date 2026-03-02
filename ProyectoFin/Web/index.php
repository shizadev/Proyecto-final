<?php
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['crear_ticket'])) {
        $usuario = trim($_POST['usuario']);
        $falla = trim($_POST['falla']);

        if ($usuario !== "" && $falla !== "") {
            $stmt = $pdo->prepare("INSERT INTO tickets (usuario, falla) VALUES (:usuario, :falla)");
            $stmt->execute([
                'usuario' => $usuario,
                'falla' => $falla
            ]);
            header("Location: index.php");
            exit();
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
        $stmt->execute([
            'estado' => $nuevo_estado,
            'id' => $id
        ]);

        header("Location: index.php");
        exit();
    }

    if (isset($_POST['editar_ticket'])) {
        $id = (int) $_POST['id'];
        $usuario = trim($_POST['usuario']);
        $falla = trim($_POST['falla']);

        if ($usuario !== "" && $falla !== "") {
            $stmt = $pdo->prepare("UPDATE tickets SET usuario = :usuario, falla = :falla WHERE id = :id");
            $stmt->execute([
                'usuario' => $usuario,
                'falla' => $falla,
                'id' => $id
            ]);
            header("Location: index.php");
            exit();
        } else {
            $error = "Complete todos los campos";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM tickets ORDER BY fecha DESC");
$tickets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Tickets</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid black; padding: 8px; }
        th { background: #eee; }
        .solucionado { color: green; font-weight: bold; }
        .pendiente { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h1>Sistema de Tickets</h1>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<h2>Crear Ticket</h2>
<form method="POST">
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="text" name="falla" placeholder="Descripción de la falla" required>
    <button type="submit" name="crear_ticket">Crear</button>
</form>

<h2>Lista de Tickets</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Falla</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($tickets as $ticket): ?>
        <tr>
            <td><?= $ticket['id'] ?></td>
            <td><?= htmlspecialchars($ticket['usuario']) ?></td>
            <td><?= htmlspecialchars($ticket['falla']) ?></td>
            <td><?= $ticket['fecha'] ?></td>
            <td>
                <?php if ($ticket['solucionado']): ?>
                    <span class="solucionado">Solucionado</span>
                <?php else: ?>
                    <span class="pendiente">Pendiente</span>
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                    <button type="submit" name="toggle_status">
                        Cambiar Estado
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>