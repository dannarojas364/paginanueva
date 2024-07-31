<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit();
}

include 'Conexion.inc.php';

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    if (!empty($nombre)) {
        $sql = $conn->prepare('UPDATE usuarios SET nombre = ? WHERE id = ?');
        $sql->bind_param('si', $nombre, $user_id);
        if ($sql->execute()) {
            echo '<script>alert("Nombre actualizado correctamente."); window.location.href = "../perfil";</script>';
        } else {
            echo '<script>alert("Error al actualizar el nombre."); window.location.href = "../perfil";</script>';
        }
    } else {
        echo '<script>alert("El nombre no puede estar vacío."); window.location.href = "../perfil";</script>';
    }
} else {
    echo '<script>alert("Solicitud no válida."); window.location.href = "../perfil";</script>';
}
?>
