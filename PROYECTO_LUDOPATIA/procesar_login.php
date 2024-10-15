<?php
// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'apoyo_ludopatia', 1903);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$cedula = $_POST['cedula'];
$password = $_POST['password'];
$tipo = $_POST['tipo'];

// Verificar las credenciales
if ($tipo === 'paciente') {
    $sql = "SELECT * FROM pacientes WHERE cedula='$cedula' AND password='$password'";
} elseif ($tipo === 'familiar') {
    $sql = "SELECT * FROM familiares WHERE cedula='$cedula' AND password='$password'";
} elseif ($tipo === 'profesional') {
    $sql = "SELECT * FROM profesionales WHERE cedula='$cedula' AND password='$password'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Redirigir al panel del tipo de usuario
    if ($tipo === 'paciente') {
        header("Location: panel_paciente.php");
    } elseif ($tipo === 'familiar') {
        header("Location: panel_familiar.php");
    } elseif ($tipo === 'profesional') {
        header("Location: panel_profesional.php");
    }
    exit();
} else {
    echo "Error: credenciales incorrectas.";
}

$conn->close();
?>
