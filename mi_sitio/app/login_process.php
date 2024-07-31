<?php
// Asegúrate de que la ruta es correcta
include 'Conexion.inc.php'; // Asegúrate de que el archivo existe en esta ruta

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurarse de que los campos 'email' y 'password' existan en el array $_POST
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Verificar si la conexión es válida
    if ($conn) {
        // Preparar y ejecutar la consulta
        $sql = $conn->prepare('SELECT id, nombre, password, activo, rol FROM usuarios WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                if ($user['activo'] == 1) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_rol'] = $user['rol'];
                    header('Location: ../index');
                    exit();
                } else {
                    echo '<script>alert("Tu cuenta está inactiva."); window.location.href = "../login";</script>';
                }
            } else {
                echo '<script>alert("Email o contraseña incorrectos."); window.location.href = "../login";</script>';
            }
        } else {
            echo '<script>alert("Email o contraseña incorrectos."); window.location.href = "../login";</script>';
        }

        $conn->close();
    } else {
        echo '<script>alert("Error en la conexión a la base de datos."); window.location.href = "../login";</script>';
    }
} else {
    echo '<script>alert("Método de solicitud no válido."); window.location.href = "../login";</script>';
}
?>
