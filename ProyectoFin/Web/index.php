<? php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['dar_ticket'])){
        $usuario = trim($_POST['usuario']);
        $falla = trim($_POST['falla']);

        if($usuario !== "&& $falla !=="){
            $stmt = $pdo->prepare("INSERT INTO tickets (usuario, falla) VALUES (:usuario, :falla)");
            $stmt->execute(['usuario'=> $usuario, 'falla' => $falla]);
            header("Location: index.php");
            exit();
        }else{
            $error = "Rellene todos los Datos";
        }
    }
    if (isset($_POST['toggle_status'])){
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("SELECT solucionado FROM tickets WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $estado = $stmt->fetchColumn();


        $estado_n = $actual ? 0:1;
        $stmt = $pdo->prepare("UPDATE tickets SET solucionado = :estado WHERE id = :id");
        $stmt->execute(['estadon'=> $estado_n, 'id' => $id]);
        header("Location: index.php");
        exit();
    }
    if (isset($_POST['editar_ticket'])){
        $id = (int)$_POST['id'];
        $usuario = trim($_POST['usuario']);
        $falla = trim($_POST['falla']);

        if($usuario !== "&& $falla !=="){
            $stmt = $pdo->prepare("UPDATE tickets SET usuario = :usuario, falla = :falla WHERE id = :id");
            $stmt->execute(['usuario'=> $usuario, 'falla' => $falla, 'id' => $id]);
            header("Location: index.php");
            exit();
        }else{
            $error = "Rellene todos los Datos";
        }
    }
}
$stmt = $pdo->query("SELECT * FROM tickets ORDER BY fecha DESC");
$tickets = $stmt->fetchAll();
?>

