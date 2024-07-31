<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login'); // Redirigir al formulario de inicio de sesión si no está autenticado
    exit();
}

// Incluir archivo de conexión
include 'app/Conexion.inc.php';

// Inicializar variable de mensaje
$mensaje = '';

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentario = $_POST['comentario'];
    $usuario_id = $_SESSION['user_id'];

    if ($conn) {
        // Preparar y ejecutar la consulta
        $sql = $conn->prepare('INSERT INTO comentarios (usuario_id, comentario) VALUES (?, ?)');
        $sql->bind_param('is', $usuario_id, $comentario);

        if ($sql->execute()) {
            $mensaje = 'Comentario enviado exitosamente.';
        } else {
            $mensaje = 'Error al enviar el comentario.';
        }

        $conn->close();
    } else {
        $mensaje = 'Error en la conexión a la base de datos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Explora el fascinante universo del cine a lo largo de los años">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Enciclopedia Cinematográfica</title>
</head>

<body>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('app/check_admin.php')
                .then(response => response.json())
                .then(data => {
                    if (data.authenticated) {
                        // Mostrar el botón de administración si el usuario es un administrador
                        document.querySelector('.admin-button').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error al verificar el rol del usuario:', error));
        });
    </script>

    <header class="bg-dark text-white py-3">
        <div class="container d-flex align-items-center">
            <img src="imagenes/Logo.jpg" alt="Logo de Enciclopedia Cinematográfica" class="img-fluid mr-3" style="max-width: 100px;">
            <h1 class="m-0"><a href="index" class="text-white text-decoration-none">Enciclopedia Cinematográfica</a></h1>
            <a href="app/logout.php" class="btn btn-outline-light ml-auto">Cerrar sesión</a>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <!-- Formulario de búsqueda -->
            <form action="buscar" method="get" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" name="q" placeholder="Buscar" aria-label="Buscar">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
            </form>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="ubicacion ">Ubicación</a></li>
                    <li class="nav-item"><a class="nav-link" href="personajes ">Personajes</a></li>
                    <li class="nav-item"><a class="nav-link" href="actividades ">Actividades</a></li>
                    <li class="nav-item"><a class="nav-link" href="historia ">Historia</a></li>
                    <li class="nav-item"><a class="nav-link" href="novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link" href="creditos ">Créditos</a></li>
                    <li class="nav-item"><a class="nav-link" href="comentarios">Comentarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="perfil">Perfil</a></li>
                    <!-- Botón de administración oculto por defecto -->
                    <li class="nav-item admin-button" style="display: none;">
                        <a class="nav-link" href="admin_page">Administrador</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <h2>Dejar un Comentario</h2>

        <?php if ($mensaje): ?>
            <div class="alert <?php echo strpos($mensaje, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="comentario">Comentario</label>
                <textarea id="comentario" name="comentario" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Comentario</button>
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Enciclopedia Cinematográfica. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
