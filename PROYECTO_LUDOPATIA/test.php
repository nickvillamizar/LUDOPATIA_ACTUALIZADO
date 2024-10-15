<?php
$host = 'localhost';
$puerto = '1209';
$base_de_datos = 'apoyo_ludopatia';
$usuario = '';
$contrasena = '';

try {
    $conn = new PDO("mysql:host=$host;port=$puerto;dbname=$base_de_datos", $usuario, $contrasena);
    echo "Conexión exitosa a la base de datos!";
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
