<?php
// Iniciar sesión y verificar autenticación
session_start();

include 'Conexion.inc.php'; // Asegúrate de que la ruta es correcta

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurarse de que los campos 'name', 'email' y 'password' existan en el array $_POST
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Verificar si la conexión es válida
    if ($conn) {
        // Verificar si el email ya existe
        $sql = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows === 0) {
            // Preparar la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario
            $sql = $conn->prepare('INSERT INTO usuarios (nombre, email, password, fecha, activo) VALUES (?, ?, ?, NOW(), 1)');
            $sql->bind_param('sss', $name, $email, $hashed_password);

            if ($sql->execute()) {
                // Redirigir con el estado de éxito
                echo '<script>alert("Tu cuenta fue creada con exito."); window.location.href = "../login";</script>';
            } else {
                // Redirigir con el estado de error
                echo '<script>alert("Error al crear la cuenta."); window.location.href = "../register";</script>';
            }
        } else {
            // Redirigir con el estado de error si el email ya existe
            echo '<script>alert("Error, email ya registrado."); window.location.href = "../register";</script>';
        }

        $conn->close();

    } else {
        // Redirigir con el estado de error en caso de conexión fallida
        echo '<script>alert("Error de conexion, intente de nuevo."); window.location.href = "../register";</script>';
    }
} else {
    echo 'Método de solicitud no válido.';
}
?>
