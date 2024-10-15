<?php 
include 'conexion.php'; // Asegúrate de que la conexión está bien configurada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $antecedentes = $_POST['antecedentes']; // Nuevo campo para antecedentes
    $nombre_acompanante = $_POST['nombre_acompanante']; // Nuevo campo para nombre del acompañante
    $telefono_acompanante = $_POST['telefono_acompanante']; // Nuevo campo para teléfono del acompañante
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Preparar la consulta SQL
    $sql = "INSERT INTO pacientes (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, antecedentes, nombre_acompanante, telefono_acompanante, contrasena)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssss", $nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $antecedentes, $nombre_acompanante, $telefono_acompanante, $contrasena);
        if ($stmt->execute()) {
            echo "Registro de paciente exitoso.";
        } else {
            echo "Error al registrar el paciente: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pacientes</title>
    <link rel="stylesheet" href="registro_paciente.css"> <!-- Asegúrate de tener este archivo CSS -->
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4"> <!-- Cambia la ruta a tu video -->
        Tu navegador no soporta la etiqueta de video.
    </video>

    <h2>Registro de Pacientes</h2>
    <form action="registro_paciente.php" method="post">
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

        <label for="antecedentes">Antecedentes:</label><br>
        <textarea name="antecedentes" rows="4" required></textarea><br> <!-- Nuevo campo para antecedentes -->

        <label for="nombre_acompanante">Nombre del Acompañante:</label><br>
        <input type="text" name="nombre_acompanante" required><br> <!-- Nuevo campo para nombre del acompañante -->

        <label for="telefono_acompanante">Teléfono del Acompañante:</label><br>
        <input type="text" name="telefono_acompanante" required><br> <!-- Nuevo campo para teléfono del acompañante -->

        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>
