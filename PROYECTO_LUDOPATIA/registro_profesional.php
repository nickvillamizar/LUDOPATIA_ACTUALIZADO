<?php
include 'conexion.php'; // Asegúrate de que este archivo contiene la conexión correcta a la base de datos

// Iniciar la sesión para almacenar el mensaje
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = !empty($_POST['celular']) ? $_POST['celular'] : null; // Si está vacío, se establece como NULL
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Preparar la consulta SQL
    $sql = "INSERT INTO profesionales (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, tarjeta_profesional, contrasena)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Aquí verificamos si $celular es NULL para poder usar 'sss' en lugar de 'ssss'
        if ($celular === null) {
            $stmt->bind_param("sssssssss", $nombre_completo, $cedula, $correo, $pais, $ciudad, $direccion, $numero_tarjeta, $contrasena, $celular);
        } else {
            $stmt->bind_param("ssssssssi", $nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $numero_tarjeta, $contrasena);
        }
        
        // Ejecutar y verificar si se insertaron los datos
        if ($stmt->execute()) {
            // Mensaje de éxito
            $_SESSION['registro_exitoso'] = "Registro exitoso.";
        } else {
            $error = "Error al registrar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Profesionales</title>
    <link rel="stylesheet" href="registro_profesionales.css"> <!-- Asegúrate de que el archivo de estilos esté en la ubicación correcta -->
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4"> <!-- Cambia esto a la ruta de tu video -->
        Tu navegador no soporta video.
    </video>

    <h2>Registro de Profesionales</h2>
    
    <?php
    // Mostrar mensaje de conexión exitosa
    

    // Mostrar mensaje de registro exitoso si está disponible
    if (isset($_SESSION['registro_exitoso'])) {
        echo "<p>" . $_SESSION['registro_exitoso'] . "</p>";
        unset($_SESSION['registro_exitoso']); // Limpiar el mensaje después de mostrarlo
    }

    // Mostrar mensaje de error si existe
    if (isset($error)) {
        echo "<p>" . $error . "</p>";
    }
    ?>

    <form action="registro_profesional.php" method="post"> <!-- Asegúrate que el nombre del archivo sea correcto -->
        <label for="nombre_completo">Nombre Completo:</label><br>
        <input type="text" name="nombre_completo" required><br>
        
        <label for="cedula">Cédula:</label><br>
        <input type="text" name="cedula" required><br>
        
        <label for="correo">Correo:</label><br>
        <input type="email" name="correo" required><br>
        
        <label for="celular">Celular:</label><br>
        <input type="text" name="celular"><br>
        
        <label for="pais">País:</label><br>
        <input type="text" name="pais" required><br>
        
        <label for="ciudad">Ciudad:</label><br>
        <input type="text" name="ciudad" required><br>
        
        <label for="direccion">Dirección:</label><br>
        <input type="text" name="direccion" required><br>
        
        <label for="numero_tarjeta">Número de Tarjeta Profesional:</label><br>
        <input type="text" name="numero_tarjeta" required><br>
        
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br>
        
        <input type="submit" value="Registrar">
    </form>
</body>
</html>
