<?php
session_start(); // Iniciar la sesión
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = $_POST['tipo_usuario'];
    $cedula = $_POST['cedula'];
    $contrasena = $_POST['contrasena'];

    // Verificar en la tabla correspondiente según el tipo de usuario
    if ($tipo_usuario == 'paciente') {
        $stmt = $conn->prepare("SELECT * FROM pacientes WHERE cedula = ?");
    } elseif ($tipo_usuario == 'familiar') {
        $stmt = $conn->prepare("SELECT * FROM familiares WHERE cedula = ?");
    } elseif ($tipo_usuario == 'profesional') {
        $stmt = $conn->prepare("SELECT * FROM profesionales WHERE cedula = ?");
    }

    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contrasena, $row['contrasena'])) {
            // Guardar el nombre del usuario en la sesión
            $_SESSION['nombre_completo'] = $row['nombre_completo'];
            $_SESSION['id'] = $row['id']; // Guardar también el ID del usuario

            // Redirigir según el tipo de usuario
            if ($tipo_usuario == 'paciente') {
                header("Location: panel_paciente.php");
            } elseif ($tipo_usuario == 'familiar') {
                header("Location: panel_familiar.php");
            } elseif ($tipo_usuario == 'profesional') {
                header("Location: panel_profesional.php");
            }
            exit();
        } else {
            // Credenciales incorrectas
            $error = "Cédula o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        $error = "Cédula o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <video id="background-video" autoplay loop muted>
        <source src="videos/pasto.mp4" type="video/mp4">
        Tu navegador no soporta video.
    </video>

    <h2>Iniciar Sesión</h2>
    <form action="" method="post">
        <label for="tipo_usuario">Seleccione tipo de usuario:</label><br>
        <select name="tipo_usuario" required>
            <option value="">Seleccione...</option>
            <option value="paciente">Paciente</option>
            <option value="familiar">Familiar</option>
            <option value="profesional">Profesional</option>
        </select><br><br>
        
        <label for="cedula">Cédula:</label><br>
        <input type="text" name="cedula" required><br><br>
        
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>
        
        <input type="submit" value="Iniciar Sesión">
        <button type="button" onclick="location.href='registro.php'">Crear Cuenta</button>
    </form>
    
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
</body>
</html>
