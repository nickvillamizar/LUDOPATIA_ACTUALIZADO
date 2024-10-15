<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}

$nombre_completo = $_SESSION['nombre_completo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Familiar</title>
    <link rel="stylesheet" type="text/css" href="panel_familiar.css">
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4">
        Su navegador no soporta videos.
    </video>

    <div class="content">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_completo); ?>!</h1>
        <p>Este es su panel de apoyo como familiar. Aquí podrá gestionar información y recursos para ayudar a su ser querido en su proceso de ludopatía.</p>

        <p>Recuerde que su apoyo es fundamental. Estamos aquí para brindarle la ayuda que necesite.</p>
        
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>
