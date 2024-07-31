<?php
include 'Conexion.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if ($conn) {
        // Verificar si el usuario existe
        $sql = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(50));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Insertar el token en la base de datos
            $sql = $conn->prepare('INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)');
            $sql->bind_param('iss', $user['id'], $token, $expiry);
            $sql->execute();

            // Enviar el correo electrónico con el enlace de restablecimiento
            $resetLink = "http://localhost/mi_sitio/reset_password.html?token=$token";
            $subject = "Restablecimiento de contraseña";
            $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: $resetLink";
            $headers = "From: no-reply@mi_sitio.com";

            if (mail($email, $subject, $message, $headers)) {
                echo '<script>alert("Enlace de restablecimiento enviado. Revisa tu correo."); window.location.href = "../login.html";</script>';
            } else {
                echo '<script>alert("Error al enviar el correo. Inténtalo nuevamente."); window.location.href = "../request_reset.html";</script>';
            }
        } else {
            echo '<script>alert("No se encontró una cuenta con ese correo electrónico."); window.location.href = "../request_reset.html";</script>';
        }

        $conn->close();
    } else {
        echo '<script>alert("Error en la conexión a la base de datos."); window.location.href = "../request_reset.html";</script>';
    }
} else {
    echo 'Método de solicitud no válido.';
}
?>
