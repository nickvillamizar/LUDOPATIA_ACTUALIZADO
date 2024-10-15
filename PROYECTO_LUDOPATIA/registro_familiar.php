<?php
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos esté configurada correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $paciente_cedula = $_POST['paciente_cedula']; // Cédula del paciente (campo en el formulario y en la tabla familiares)
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Verificar si el paciente existe en la tabla `pacientes`
    $sql_verificar_paciente = "SELECT * FROM pacientes WHERE cedula = ?";
    
    if ($stmt_paciente = $conn->prepare($sql_verificar_paciente)) {
        // Vincular parámetros
        $stmt_paciente->bind_param("s", $paciente_cedula);
        
        // Ejecutar la consulta
        $stmt_paciente->execute();
        $resultado_paciente = $stmt_paciente->get_result();

        // Si el paciente existe, registrar al familiar
        if ($resultado_paciente->num_rows > 0) {
            // Preparar la consulta SQL para insertar al familiar
            $sql_insert_familiar = "INSERT INTO familiares (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, paciente_cedula, contrasena)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt_familiar = $conn->prepare($sql_insert_familiar)) {
                // Vincular parámetros
                $stmt_familiar->bind_param("sssssssss", $nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $paciente_cedula, $contrasena);
                
                // Ejecutar la consulta y verificar si fue exitosa
                if ($stmt_familiar->execute()) {
                    echo "Registro exitoso.";
                } else {
                    echo "Error al registrar: " . $stmt_familiar->error;
                }
                $stmt_familiar->close(); // Cerrar la declaración
            } else {
                echo "Error en la preparación de la consulta: " . $conn->error;
            }
        } else {
            // El paciente no existe, mostrar mensaje de error
            echo "La cédula del paciente que usted está registrando no se encuentra.";
        }
        $stmt_paciente->close(); // Cerrar la declaración
    } else {
        echo "Error al verificar el paciente: " . $conn->error;
    }
}

$conn->close(); // Cerrar la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Familiares</title>
    <link rel="stylesheet" href="registro_familiar.css"> <!-- Vincular el CSS -->
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4"> <!-- Cambia la ruta a tu video -->
        Tu navegador no soporta la etiqueta de video.
    </video>
    
    <h2>Registro de Familiares</h2>
    <form action="registro_familiar.php" method="post">
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
        
        <label for="paciente_cedula">Cédula del Paciente:</label><br>
        <input type="text" name="paciente_cedula" required><br> <!-- Cambié el nombre del campo a 'paciente_cedula' -->
        
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br>
        
        <input type="submit" value="Registrar">
    </form>
</body>
</html>

