<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit();
}

include 'Conexion.inc.php';

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $password = trim($_POST['password']);
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conn->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
        $sql->bind_param('si', $hashed_password, $user_id);
        if ($sql->execute()) {
            echo '<script>alert("Contraseña actualizada correctamente."); window.location.href = "../perfil";</script>';
        } else {
            echo '<script>alert("Error al actualizar la contraseña."); window.location.href = "../perfil";</script>';
        }
    } else {
        echo '<script>alert("La contraseña no puede estar vacía."); window.location.href = "../perfil";</script>';
    }
} else {
    echo '<script>alert("Solicitud no válida."); window.location.href = "../perfil";</script>';
}
?>
