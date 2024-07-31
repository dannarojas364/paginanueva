<?php
include 'Conexion.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $token = isset($_POST['token']) ? $_POST['token'] : '';

    if ($password !== $confirm_password) {
        echo '<script>alert("Las contraseñas no coinciden."); window.location.href = "reset_password.html?token=' . $token . '";</script>';
        exit();
    }

    if ($conn) {
        // Verificar si el token es válido
        $sql = $conn->prepare('SELECT user_id FROM password_resets WHERE token = ? AND expiry > NOW()');
        $sql->bind_param('s', $token);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 1) {
            $reset = $result->fetch_assoc();
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Actualizar la contraseña del usuario
            $sql = $conn->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
            $sql->bind_param('si', $hashed_password, $reset['user_id']);
            $sql->execute();

            // Eliminar el token de la base de datos
            $sql = $conn->prepare('DELETE FROM password_resets WHERE token = ?');
            $sql->bind_param('s', $token);
            $sql->execute();

            echo '<script>alert("Contraseña restablecida con éxito."); window.location.href = "../login";</script>';
        } else {
            echo '<script>alert("Token inválido o expirado."); window.location.href = "../request_reset.html";</script>';
        }

        $conn->close();
    } else {
        echo '<script>alert("Error en la conexión a la base de datos."); window.location.href = "../reset_password.html?token=' . $token . '";</script>';
    }
} else {
    echo 'Método de solicitud no válido.';
}
?>
