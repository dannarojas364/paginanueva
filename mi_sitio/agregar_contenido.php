<?php
session_start();

include 'app/Conexion.inc.php';

// Verificar si el usuario está autenticado y es un administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: index');
    exit();
}

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $mensaje = '';

    // Validar los datos (opcional)
    if (!empty($titulo) && !empty($descripcion)) {
        $imagenNombre = '';

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($imagen["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($imagen["tmp_name"]);
            if ($check !== false) {
                // Check file size (optional)
                if ($imagen["size"] <= 500000) {
                    // Allow certain file formats
                    if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
                        if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
                            $imagenNombre = basename($imagen["name"]); // Guardar solo el nombre del archivo
                        } else {
                            $mensaje = 'Error al subir el archivo de imagen.';
                        }
                    } else {
                        $mensaje = 'Solo se permiten archivos JPG, JPEG y PNG.';
                    }
                } else {
                    $mensaje = 'El archivo es demasiado grande.';
                }
            } else {
                $mensaje = 'El archivo no es una imagen.';
            }
        }

        if (empty($mensaje)) {
            if (!empty($imagenNombre)) {
                $stmt = $conn->prepare('INSERT INTO contenidos (titulo, descripcion, imagen) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $titulo, $descripcion, $imagenNombre);
            } else {
                $stmt = $conn->prepare('INSERT INTO contenidos (titulo, descripcion) VALUES (?, ?)');
                $stmt->bind_param('ss', $titulo, $descripcion);
            }

            if ($stmt->execute()) {
                $mensaje = 'Contenido agregado exitosamente.';
            } else {
                $mensaje = 'Error al agregar el contenido: ' . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        $mensaje = 'Todos los campos son obligatorios.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Agregar contenido a la enciclopedia cinematográfica">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Agregar Contenido</title>
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
        <h2>Agregar Contenido</h2>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <form action="agregar_contenido.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Agregar Contenido</button>
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; 2024 Enciclopedia Cinematográfica. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
