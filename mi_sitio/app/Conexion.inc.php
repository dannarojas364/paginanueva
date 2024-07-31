<?php

$host = 'localhost';  
$usuario = 'root'; 
$contraseña = ''; 
$nombre_base_datos = 'blog'; 

// Crear la conexión
$conn = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Comprobar si la conexión fue exitosa
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Opcional: Configurar el conjunto de caracteres
$conn->set_charset('utf8');

?>
