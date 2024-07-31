<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit();
}

include 'Conexion.inc.php';

$user_id = $_SESSION['user_id'];
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
        $foto_perfil = $_FILES['foto_perfil'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($foto_perfil["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($foto_perfil["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<script>alert("El archivo no es una imagen."); window.location.href = "../perfil";</script>';
            $uploadOk = 0;
        }

        // Check file size
        if ($foto_perfil["size"] > 500000) {
            echo '<script>alert("El archivo es demasiado grande."); window.location.href = "../perfil";</script>';
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo '<script>alert("Solo se permiten archivos JPG, JPEG y PNG."); window.location.href = "../perfil";</script>';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($foto_perfil["tmp_name"], $target_file)) {
                $sql = $conn->prepare('UPDATE usuarios SET foto_perfil = ? WHERE id = ?');
                $sql->bind_param('si', $foto_perfil["name"], $user_id);
                if ($sql->execute()) {
                    echo '<script>alert("Foto de perfil actualizada."); window.location.href = "../perfil";</script>';
                } else {
                    echo '<script>alert("Error al actualizar la foto de perfil."); window.location.href = "../perfil";</script>';
                }
            } else {
                echo '<script>alert("Error al subir el archivo."); window.location.href = "../perfil";</script>';
            }
        }
    } else {
        echo '<script>alert("No se ha seleccionado ninguna imagen."); window.location.href = "../perfil";</script>';
    }

    }else{
        echo '<script>alert("Error al subir la imagen."); window.location.href = "../perfil";</script>';
    }
?>
