<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit();
}

include 'app/Conexion.inc.php';

$user_id = $_SESSION['user_id'];
$sql = $conn->prepare('SELECT nombre, email, foto_perfil FROM usuarios WHERE id = ?');
$sql->bind_param('i', $user_id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "Error al obtener los datos del usuario.";
    exit();
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

    
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Perfil de Usuario
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($user['nombre']); ?></h5>
                <p class="card-text">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <img src="uploads/<?php echo htmlspecialchars($user['foto_perfil']); ?>" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 200px;">
                
                <form action="app/update_foto.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <div class="form-group">
                        <label for="foto_perfil">Actualizar Foto de Perfil:</label>
                        <input type="file" name="foto_perfil" id="foto_perfil" class="form-control-file">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Foto</button>
                </form>
                
                <form action="app/update_nombre.php" method="POST" class="mt-3">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Nombre</button>
                </form>
                
                <form action="app/update_password.php" method="POST" class="mt-3">
                    <div class="form-group">
                        <label for="password">Nueva Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                </form>
                
                <a href="app/logout.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Enciclopedia Cinematográfica. Todos los derechos reservados.</p>
    </footer>
    
</body>
</html>
