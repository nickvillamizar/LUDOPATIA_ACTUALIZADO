<?php
$host = 'localhost';
$puerto = '1209'; // Asegúrate de que este sea el puerto correcto
$usuario = 'root'; // Usuario por defecto
$contrasena = ''; // Sin contraseña por defecto en XAMPP

// Intenta establecer la conexión
$conn = new mysqli($host, $usuario, $contrasena, 'apoyo_ludopatia', $puerto);

// Verifica si hay errores en la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa";
}
?>
