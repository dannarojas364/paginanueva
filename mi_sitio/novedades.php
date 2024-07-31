<?php
session_start();

include 'app/Conexion.inc.php';

// Obtener todo el contenido de la tabla "contenidos"
$sql = "SELECT titulo, descripcion, imagen, fecha FROM contenidos ORDER BY fecha DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Novedades de la Enciclopedia Cinematográfica">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Novedades - Enciclopedia Cinematográfica</title>
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
        <h2>Novedades</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="list-group">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($row['titulo']); ?></h5>
                            <p class="mb-1"><?php echo nl2br(htmlspecialchars($row['descripcion'])); ?></p>
                            <?php if (!empty($row['imagen'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['imagen']); ?>" alt="imagen" class="img-thumbnail" style="max-width: 200px;">
                            <?php endif; ?>
                        </div>
                        <div>
                            <small>Publicado el <?php echo htmlspecialchars($row['fecha']); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No hay novedades para mostrar.</p>
        <?php endif; ?>

    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; 2024 Enciclopedia Cinematográfica. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
